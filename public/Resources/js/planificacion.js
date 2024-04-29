$(document).ready(function () {
    //#region Configuraciones Iniciales
    let modalMain = $('#modalMain');
    let tituloModal = $('#modal-titulo');
    let mensajeModal = $('#modal-mensaje');
    let searchInput = $('#search');
    let btnCrud = $('#btnCrud');
    let tableIndex = $('#tabla');
    let urlIndex = $('#url-index').val();
    let urlStore = $('#url-store').val();
    let urlShow = $('#url-show').val() + '/';
    let urlUpdate = urlShow;
    let urlDelete = urlShow;
    //#endregion

    //#region Funciones Extras
    function resetAllOnModal() {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#propietario_id_mas').val($('#propietario_id_mas option:first').val()).trigger('change');
        tituloModal.text('Nueva Planificación');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Agregar');
    }
    //#endregion

    //#region Funciones Prepare
    function prepareSelect(id) {
        tituloModal.text('Detalles sobre la Planificación');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id) {
        tituloModal.text('Editar Planificación');
        $('#rsdtForm').find(':input').prop('disabled', false);
        show(id);
    }

    function prepareDelete(id) {
        tituloModal.text('Eliminar Planificación');
        mensajeModal.html('La siguiente Planificación se eliminará a continuación,<br>¿Está seguro?');
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
                tableIndex.empty();
                if (response.data.data.length > 0) {
                    error.html('');
                    html = `
                    <thead class="table-secondary fw-semibold">
                        <tr>
                            <th>ID</th>
                            <th>MOTIVO</th>
                            <th>DESCRIPCIÓN</th>
                            <th>AREA</th>
                            <th>PAGO</th>
                            <th>INICIO</th>
                            <th>FIN</th>
                            <th width="200">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                    response.data.data.forEach(planificacion => {
                        html += `
                        <tr>
                            <td>${planificacion.id_plan}</td>
                            <td>${planificacion.motivo_plan}</td>
                            <td>${planificacion.descripcion_plan}</td>
                            <td>${planificacion.area_plan}</td>
                            <td>${planificacion.pago_plan}</td>
                            <td>${planificacion.inicio_plan}</td>
                            <td>${planificacion.fin_plan}</td>
                            <td>
                                <button id='btnSelect' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                <button id='btnEdit' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                <button id='btnDelete' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
                            </td>
                        </tr>
                    `;
                    });
                    html += `</tbody>`;
                    tableIndex.append(html);

                    // Llamar a la función para generar los botones de paginación
                    generatePaginationButtons(response.data.current_page, response.data.last_page, url, search);
                    seccionTotalResultados.removeClass('d-none');
                } else {
                    let paginationContainer = $('#pagination-container');
                    paginationContainer.empty();
                    seccionTotalResultados.addClass('d-none');
                    error.html('No se encontraron resultados.')
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
                    searchInput.val(response.data.id_plan);
                    index(response.data.id_plan);
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
        let planificacion = data;
        $('#id_plan').val(planificacion.id_plan);
        $('#motivo_plan').val(planificacion.motivo_plan);
        $('#descripcion_plan').val(planificacion.descripcion_plan);
        $('#area_plan').val(planificacion.area_plan);
        $('#pago_plan').val(planificacion.pago_plan);
        $('#inicio_plan').val(planificacion.inicio_plan);
        $('#fin_plan').val(planificacion.fin_plan);
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
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
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
                    searchInput.val(response.data.id_plan);
                    index(response.data.id_plan);
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

    function destroy(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlDelete + id,
            type: 'DELETE',
            data: { _token: _token },
            success: function (response) {
                if (response.state) {
                    console.log(response.message);
                    searchInput.val('');
                    index();
                    modalMain.modal('hide');
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    //#endregion

    //#region Interacción DOM
    //#region Extras
    //#region Select2 con Buscador
    $("#propietario_id_mas").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
        dropdownParent: $('#modalMain')
    });
    //#endregion
    //#endregion

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
    btnCrud.click(function (e) {
        e.preventDefault();
        let action = $(this).attr('name');
        let id = $('#id_plan').val();
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
    tableIndex.on('click', '#btnSelect', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'show');
        btnCrud.text('Show');
        prepareSelect(id);
    });

    tableIndex.on('click', '#btnEdit', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'edit');
        btnCrud.text('Editar');
        prepareEdit(id);
    });

    tableIndex.on('click', '#btnDelete', function () {
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
