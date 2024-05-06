$(document).ready(function () {
    //#region Configuraciones Iniciales
    let modalMain = $('#modalMain');
    let tituloModal = $('#modal-titulo');
    let mensajeModal = $('#modal-mensaje');
    let searchInput = $('#search');
    let btnCrud = $('#btnCrud');
    let cardsDep = $('#cardsDep');
    let urlIndex = $('#url-index').val();
    let urlStore = $('#url-store').val();
    let urlShow = $('#url-show').val() + '/';
    let urlUpdate = urlShow;
    let urlDelete = urlShow;
    //#endregion

    //#region Funciones Extras
    function MostrarNotificacion(clase, texto, segundos) {
        let aux = $("#notificacion");
        if (aux.html() != undefined) {
            aux.remove();
        }
        let html = `
        <div id="notificacion" class="alerta show fade alert p-0 alert-dismissible alert-${clase}">
            <div class="d-flex justify-content-between align-items-center py-1 px-2 gap-2">
                <div class="text-justify" style="font-size: 12.6px">Notificación.</div>
                <button class="btn btn-sm p-0 text-${clase}" id="btnCerrarAlerta" type="button" data-bs-dismiss="alert" data-bs-target="#notificacion">
                    <i class="fa-solid fa-xmark text-dark"></i>
                </button>
            </div>
            <hr class="m-0">
            <div class="py-1 px-2">
                <div class="text-justify alerta-text">` + texto + `</div>
            </div>
        </div>`;
        $("body").append(html);
        let alerta = $("#notificacion");
        setTimeout(function () {
            alerta.alert("close");
        }, segundos * 1000);
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
                    select.empty();
                    select.append($("<option>",
                        {
                            value: 0, text: "Sin Parqueo"
                        }));
                    if (parqueos.length > 0) {
                        parqueos.forEach(parqueo => {
                            select.append($("<option>",
                                {
                                    value: parqueo.id_park, text: parqueo.codigo_park
                                }));
                        });
                    }
                },
                error: function (xhr, status, error) {
                    MostrarNotificacion('danger', xhr.responseText, 5);
                }
            });
    }

    function resetAllOnModal() {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#campos_parqueo').removeClass('show');
        tituloModal.text('Nuevo Departamento');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Agregar');
        $('#seccionAdquisiciones').addClass('d-none')
    }
    //#endregion

    //#region Funciones Prepare
    function prepareSelect(id) {
        tituloModal.text('Detalles sobre el Departamento');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id) {
        tituloModal.text('Editar Departamento');
        $('#rsdtForm').find(':input').prop('disabled', false);
        show(id);
    }

    function prepareDelete(id) {
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
                    error.html('');
                    html = ``;
                    response.data.data.forEach(departamento => {
                        let adquisicion = departamento.adquisiciones.length > 0 ? departamento.adquisiciones[0] : null;
                        let residente = adquisicion ? adquisicion.residente : null;
                        let nombreResidente = residente ? `Por: ${residente.nombre_rsdt} ${residente.apellidop_rsdt}` : 'Sin residente';
                        let parqueo = departamento.parqueo;
                        let estado = 'DISPONIBLE';
                        let fechaFin = adquisicion ? new Date(adquisicion.fin_reg) : null;
                        let fechaHoy = new Date();

                        if (adquisicion && adquisicion.tipoadq_reg == 'Compra') {
                            estado = 'COMPRADO';
                        } else {
                            if (fechaFin) {
                                if (fechaHoy > fechaFin) {
                                    estado = 'DISPONIBLE'
                                    nombreResidente = 'Sin residente';
                                }
                                else {
                                    estado = 'ALQUILADO'
                                }
                            }
                            else {
                                estado = 'DISPONIBLE'
                            }
                        }

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
                                            <h2 class="m-0">${estado}</h2>
                                            <div>${nombreResidente}</div>
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
                MostrarNotificacion('danger', xhr.responseText, 5);
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
                if (response.state) {
                    MostrarNotificacion('success', response.message, 5);
                    searchInput.val(response.data.codigo_dpto);
                    index(response.data.codigo_dpto);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                }
                else {
                    MostrarNotificacion('danger', response.message, 5);
                }
            },
            error: function (xhr, status, error) {
                MostrarNotificacion('danger', xhr.responseText, 5);
            }
        });
    }

    function llenarFormulario(data) {
        let departamento = data;
        $('#id_dpto').val(departamento.id_dpto);
        $('#codigo_dpto').val(departamento.codigo_dpto);
        $('#precio_dpto').val(departamento.precio_dpto);
        $('#precioa_dpto').val(departamento.precioa_dpto);
        if (departamento.parqueo !== null) {
            let parqueo = departamento.parqueo;
            let select = $('#parqueo_id_dpto');
            select.append($("<option>",
                {
                    value: parqueo.id_park, text: parqueo.codigo_park, selected: true
                }));
        }
        if (btnCrud.attr('name') == 'show') {
            let adquisiciones = departamento.adquisiciones;
            if (adquisiciones.length > 0) {
                let adquisicion = adquisiciones[0];
                let residente = adquisicion.residente;
                $('#ci_adq').text(residente.ci_rsdt);
                $('#rsdt_adq').text(`${residente.nombre_rsdt} ${residente.apellidop_rsdt}`);
                $('#inicio_adq').text(adquisicion.inicio_reg);
                $('#fin_adq').text(adquisicion.fin_reg ?? 'Sin fecha');
                $('#tipo_adq').text(adquisicion.tipoadq_reg);
                $('#pago_adq').text(adquisicion.pago_reg);
                $('#seccionAdquisiciones').removeClass('d-none');
            }
            else {
                $('#seccionAdquisiciones').addClass('d-none');
            }
        }
        else {
            $('#seccionAdquisiciones').addClass('d-none');
        }
    }

    function show(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: urlShow + id,
            type: 'GET',
            data: { _token: _token },
            dataType: 'json',
            success: function (response) {
                if (response.state) {
                    llenarFormulario(response.data);
                    modalMain.modal('show');
                } else {
                    MostrarNotificacion('danger', response.message, 5);;
                }
            },
            error: function (xhr, status, error) {
                MostrarNotificacion('danger', xhr.responseText, 5);
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
                    MostrarNotificacion('success', response.message, 5);
                    searchInput.val(response.data.codigo_dpto);
                    index(response.data.codigo_dpto);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                } else {
                    MostrarNotificacion('danger', response.message, 5);;
                }
            },
            error: function (xhr, status, error) {
                // Si hay un error en la solicitud AJAX, muestra el mensaje de error en la consola
                MostrarNotificacion('danger', xhr.responseText, 5);
            }
        });
    }

    function destroy(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlDelete + id,
            type: 'DELETE',
            data: { _token: _token },
            success: function (response) {
                if (response.state) {
                    MostrarNotificacion('success', response.message, 5);
                    searchInput.val('');
                    index();
                    modalMain.modal('hide');
                } else {
                    MostrarNotificacion('danger', response.message, 5);;
                }
            },
            error: function (xhr, status, error) {
                MostrarNotificacion('danger', xhr.responseText, 5);
            }
        });
    }
    //#endregion

    //#region Interacción DOM
    //#region Funciones de Carga Inicial
    index();
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
    $('#btnAgregar').on('click', function () {
        getParqueosOnSelect();
    });

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
        getParqueosOnSelect();
        prepareSelect(id);
    });

    cardsDep.on('click', '#btnEdit', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'edit');
        btnCrud.text('Editar');
        getParqueosOnSelect();
        prepareEdit(id);
    });

    cardsDep.on('click', '#btnDelete', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'delete');
        btnCrud.text('Eliminar');
        getParqueosOnSelect();
        prepareDelete(id);
    });
    //#endregion

    //#region Configuracion en Modales
    $("#modalMain").on("show.bs.modal", function () {

    });

    modalMain.on('hidden.bs.modal', function () {
        resetAllOnModal();
    });
    //#endregion
});
