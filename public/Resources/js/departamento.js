$(document).ready(function () {
    //#region Configuraciones Iniciales
    let modalMain = $('#modalMain');
    let tituloModal = $('#modal-titulo');
    let mensajeModal = $('#modal-mensaje');
    let searchInput = $('#search');
    let btnCrud = $('#btnCrud');
    /* let tableIndex = $('#tabla'); */
    let cardsDep = $('#cardsDep');
    let urlIndex = $('#url-index').val();
    let urlStore = $('#url-store').val();
    let urlShow = $('#url-show').val() + '/';
    let urlUpdate = urlShow;
    let urlDelete = urlShow;
    //#endregion

    //#region Funciones Extras
    function collapseWithCheck(inputCheck, seccionVisible, inputVisible, seccionNoVisible, inputNoVisible) {
        let temporizador;
        if (inputCheck.is(':checked')) {
            inputCheck.prop('disabled', true);
            clearTimeout(temporizador);
            temporizador = setTimeout(function () {
                seccionVisible.collapse('hide');
                seccionNoVisible.collapse('show');
                setTimeout(function () {
                    inputNoVisible.focus();
                    inputCheck.prop('disabled', false);
                    if (btnCrud.attr('name') == "show" || btnCrud.attr('name') == "delete")
                    inputCheck.prop('disabled', true);
                }, 250);
            }, 200);
        }
        else {
            inputCheck.prop('disabled', true);
            temporizador = setTimeout(function () {
                seccionVisible.collapse('show');
                setTimeout(function () {
                    inputVisible.focus();
                    inputCheck.prop('disabled', false);
                    if (btnCrud.attr('name') == "show" || btnCrud.attr('name') == "delete")
                    inputCheck.prop('disabled', true);
                }, 250);
                seccionNoVisible.collapse('hide');
            }, 200);
        }
    }

    function getRepresentantesOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#residente_id_dpto");
        let url = select.data('url');

        $.ajax(
            {
                url: url,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let representantes = response.data;

                    if (representantes.length > 0) {
                        select.empty();
                        representantes.forEach(representante => {
                            select.append($("<option>",
                                {
                                    value: representante.id_rsdt, text: representante.nombre_rsdt + ' ' + representante.apellidop_rsdt
                                }));
                        });
                    }
                    else {
                        console.log("No hay resultados");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX: " + error);
                }
            });
    }

    function getParqueosOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#parqueo_id_dpto");
        let url = select.data('url');

        $.ajax(
            {
                url: url,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let parqueos = response.data;

                    if (parqueos.length > 0) {
                        select.empty();
                        parqueos.forEach(parqueo => {
                            select.append($("<option>",
                                {
                                    value: parqueo.id_park, text: parqueo.codigo_park
                                }));
                        });
                    }
                    else {
                        console.log("No hay resultados");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX: " + error);
                }
            });
    }

    function resetAllOnModal()
    {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#campos_propietario').addClass('show');
        $('#campos_parqueo').removeClass('show');
        $('#rep_fam_id_rsdt').val($('#rep_fam_id_rsdt option:first').val()).trigger('change');
        tituloModal.text('Nuevo Residente');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Agregar');
    }
    //#endregion

    //#region Funciones Prepare
    function prepareSelect(id)
    {
        tituloModal.text('Detalles sobre el Departamento');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id)
    {
        tituloModal.text('Editar Departamento');
        $('#rsdtForm').find(':input').prop('disabled', false);
        show(id);
    }

    function prepareDelete(id)
    {
        tituloModal.text('Eliminar Departamento');
        mensajeModal.html('El siguiente departamento se eliminará a continuación,<br>¿Está seguro?');
        $('#rsdtForm').find(':input:not(button)').prop('disabled', true);
        show(id);
    }
    //#endregion

    //#region Paginación
    function generatePaginationButtons(currentPage, lastPage, url, search) {
        let paginationContainer = $('#pagination-container');
        paginationContainer.empty();

        let maxVisibleButtons = 3; // Número máximo de botones visibles en la paginación

        // Verificar si hay menos de 4 páginas en total para mostrar solo los numeros de paginación
        if (lastPage <= maxVisibleButtons) {
            for (let i = 1; i <= lastPage; i++) {
                let pageButton = $('<li class="page-item"><button class="page-link border-secondary pag-item">' + i + '</button></li>');
                if (i === currentPage) {
                    pageButton.addClass('active');
                }
                pageButton.click(function () {
                    if (currentPage !== i) {
                        index(search, url + '&page=' + i);
                    }
                });
                paginationContainer.append(pageButton);
            }
            return; // Salir de la función después de generar los botones de número de página
        }

        // Botón para ir a la primera página
        let firstPageButton = $('<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&laquo;</button></li>');
        firstPageButton.click(function () {
            if (currentPage !== 1) {
                index(search, url + '&page=1');
            }
        });
        paginationContainer.append(firstPageButton);

        // Botón para ir a la página anterior
        let previousPageButton = $('<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&lt;</button></li>');
        previousPageButton.click(function () {
            if (currentPage > 1) {
                let prevPage = currentPage - 1;
                index(search, url + '&page=' + prevPage);
            }
        });
        paginationContainer.append(previousPageButton);

        // Botones para las páginas intermedias
        let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
        let endPage = Math.min(lastPage, startPage + maxVisibleButtons - 1);

        if (endPage - startPage < maxVisibleButtons - 1) {
            startPage = Math.max(1, endPage - maxVisibleButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            let pageButton = $('<li class="page-item ' + (i === currentPage ? 'active' : '') + '"><button class="page-link border-secondary pag-item">' + i + '</button></li>');
            pageButton.click(function () {
                if (currentPage !== i) {
                    index(search, url + '&page=' + i);
                }
            });
            paginationContainer.append(pageButton);
        }

        // Botón para ir a la página siguiente
        let nextPageButton = $('<li class="page-item ' + (currentPage === lastPage ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&gt;</button></li>');
        nextPageButton.click(function () {
            if (currentPage < lastPage) {
                let nextPage = currentPage + 1;
                index(search, url + '&page=' + nextPage);
            }
        });
        paginationContainer.append(nextPageButton);

        // Botón para ir a la última página
        let lastPageButton = $('<li class="page-item ' + (currentPage === lastPage ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&raquo;</button></li>');
        lastPageButton.click(function () {
            if (currentPage !== lastPage) {
                index(search, url + '&page=' + lastPage);
            }
        });
        paginationContainer.append(lastPageButton);
    }
    //#endregion

    //#region Funciones CRUD
    function index(search = "", url = urlIndex + '?page=1') {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let totalResultados = $('#totalResultados').val();
        let error = $('#index-error');
        let seccionTotalResultados = $('#seccionTotalResultados');
        let html = "";

        $.ajax({
            url: url,
            type: 'GET',
            data: { _token: _token, search: search, totalResultados: totalResultados },
            dataType: 'json',
            success: function (response) {
                cardsDep.empty();
                if (response.data.data.length > 0) {
                    console.log(response);
                    error.html('');
                    html = ``;
                    response.data.data.forEach(departamento => {
                        let adquisicion = departamento.adquisicion;
                        let residente = departamento.residente;
                        let parqueo = departamento.parqueo;

                        html += `
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="m-0">DEPARTAMENTO | ${departamento.codigo_dpto}</h6>
                                            <div class="dropend">
                                                <button class="btn-more-options" type="button" data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </button>
                                                <ul class="dropdown-menu p-0" style="width: auto; line-height: 1px;">
                                                    <li>
                                                        <button id="btnSelect" data-id="${departamento.id_dpto}" class="dropdown-item rounded-top px-2 py-2" href="#"><i
                                                                class="fa-solid fa-clipboard me-2"></i>Detalles</button>
                                                    </li>
                                                    <li>
                                                        <button id="btnEdit" data-id="${departamento.id_dpto}" class="dropdown-item px-2 py-2" href="#"><i
                                                                class="fa-solid fa-pen-to-square me-2"></i>Editar</button>
                                                    </li>
                                                    <li>
                                                        <button id="btnDelete" data-id="${departamento.id_dpto}" class="dropdown-item rounded-bottom px-2 py-2" href="#"><i
                                                                class="fa-solid fa-trash me-2"></i>Eliminar</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="m-0">${adquisicion ? (adquisicion.tipoadq_reg == 'Compra' ? 'COMPRADO' : (adquisicion.tipoadq_reg == 'Alquiler' ? 'ALQUILADO' : 'DISPONIBLE')) : 'DISPONIBLE'}</h2>
                                            <div>${residente ? `Por: ${residente.nombre_rsdt} ${residente.apellidop_rsdt}` : 'Sin residente'}</div>
                                            <hr>
                                            <h6 class="m-0">PARQUEO ASIGNADO | ${parqueo ? (parqueo.codigo_park ?? 'NINGUNO') : 'NINGUNO'}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    cardsDep.append(html);

                    // Llamar a la función para generar los botones de paginación
                    generatePaginationButtons(response.data.current_page, response.data.last_page, url, search);
                    seccionTotalResultados.removeClass('d-none');
                } else {
                    error.html('No se encontraron resultados.')
                    let paginationContainer = $('#pagination-container');
                    paginationContainer.empty();
                    seccionTotalResultados.addClass('d-none');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function store() {
        let formData = $('#rsdtForm').serialize();

        $.ajax({
            url: urlStore,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.state) {
                    searchInput.val(response.data.codigo_dpto);
                    index(response.data.codigo_dpto);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                }
                else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function llenarFormulario(data) {
        let departamento = data;
        $('#id_dpto').val(departamento.id_dpto);
        $('#codigo_dpto').val(departamento.codigo_dpto);
        $('#precio_dpto').val(departamento.precio_dpto);
        $('#precioa_dpto').val(departamento.precioa_dpto);
        $('#residente_id_dpto').val(departamento.residente_id_dpto);
        $('#parqueo_id_dpto').val(departamento.parqueo_id_dpto);
    }

    function show(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: urlShow + id,
            type: 'GET',
            data: { _token: _token },
            dataType: 'json',
            success: function(response) {
                if (response.state) {
                    llenarFormulario(response.data);
                    modalMain.modal('show');
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function update(id) {
        let formData = $('#rsdtForm').serialize();

        $.ajax({
            url: urlUpdate + id,
            type: 'PUT',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.state) {
                    // Actualización exitosa
                    console.log("¡Actualización exitosa!");
                    searchInput.val(response.data.codigo_dpto);
                    index(response.data.codigo_dpto);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Si hay un error en la solicitud AJAX, muestra el mensaje de error en la consola
                console.error(xhr.responseText);
            }
        });
    }

    function destroy(id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlDelete + id,
            type: 'DELETE',
            data: { _token: _token },
            success: function(response) {
                if (response.state) {
                    console.log(response.message);
                    searchInput.val('');
                    index();
                    modalMain.modal('hide');
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    //#endregion

    //#region Interacción DOM
    //#region Funciones de Carga Inicial
    index();
    getRepresentantesOnSelect();
    getParqueosOnSelect();
    //#endregion

    //#region Busqueda
    searchInput.on('input', function () {
        index($(this).val());
    });

    $('#totalResultados').on('input', function () {
        index(searchInput.val());
    });
    //#endregion

    //#region Store
    btnCrud.click(function (e) {
        e.preventDefault();
        let action = $(this).attr('name');
        let id = $('#id_dpto').val();
        switch (action) {
            case "store":
                {
                    store();
                }
                break;
            case "edit":
                {
                    update(id);
                }
                break;
            case "delete":
                {
                    destroy(id);
                }
                break;
        }
    });
    //#endregion
    //#endregion

    //#region Activacion de botones de CRUD
    cardsDep.on('click', '#btnSelect', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'show');
        btnCrud.text('Show');
        prepareSelect(id);
    });

    cardsDep.on('click', '#btnEdit', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'edit');
        btnCrud.text('Editar');
        prepareEdit(id);
    });

    cardsDep.on('click', '#btnDelete', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'delete');
        btnCrud.text('Eliminar');
        prepareDelete(id);
    });
    //#endregion

    //#region Configuracion en Modales
    $("#modalMain").on("shown.bs.modal", function () {

    });

    modalMain.on('hidden.bs.modal', function () {
        resetAllOnModal();
    });
    //#endregion
});
