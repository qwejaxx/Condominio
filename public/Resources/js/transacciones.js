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

    let urlGetResidentes = $('#url-get-rep').val();
    let urlGetPlanificaciones = $('#url-get-plan').val();
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

    function resetAllOnModal() {
        $('#rsdtForm')[0].reset();
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#plan_id_tr').val($('#plan_id_tr option:first').val()).trigger('change');
        $('#residente_id_tr').val($('#residente_id_tr option:first').val()).trigger('change');
        $('#seccionFecha').addClass('d-none');
        tituloModal.text('Registrar Visita');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Registrar');
    }

    function getResidentesOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#residente_id_tr");

        $.ajax(
            {
                url: urlGetResidentes,
                type: 'GET',
                data: { _token: _token, totalResultados: 100 },
                dataType: 'json',
                success: function (response) {
                    let Residentes = response.data;

                    if (Residentes.length > 0) {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'Seleccionar Residente:', selected: true, disabled: true
                            }));
                        Residentes.forEach(Residente => {
                            let idResidente = Residente.id_rsdt;
                            let nombreResidente = Residente.nombre_rsdt + ' ' + Residente.apellidop_rsdt + ' ' + Residente.apellidom_rsdt;

                            let option = `
                                <option value='${idResidente}'>${nombreResidente}</option>
                            `;
                            select.append(option);
                        });
                    }
                    else {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'No hay Residentes disponibles.', selected: true, disabled: true
                            }));
                    }
                },
                error: function (xhr, status, error) {
                    MostrarNotificacion('danger', xhr.responseText, 5);
                }
            });
    }

    function getPlanificacionesOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#plan_id_tr");

        $.ajax(
            {
                url: urlGetPlanificaciones,
                type: 'GET',
                data: { _token: _token, totalResultados: 100 },
                dataType: 'json',
                success: function (response) {
                    let actividades = response.data.data;
                    console.log(response);
                    if (actividades.length > 0) {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'Seleccionar actividad:', selected: true, disabled: true
                            }));
                        actividades.forEach(actividad => {
                            let idResidente = actividad.id_plan;
                            let nombreResidente = actividad.motivo_plan;

                            let option = `
                                <option value='${idResidente}'>${nombreResidente}</option>
                            `;
                            select.append(option);
                        });
                    }
                    else {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'No hay actividades disponibles.', selected: true, disabled: true
                            }));
                    }
                },
                error: function (xhr, status, error) {
                    MostrarNotificacion('danger', xhr.responseText, 5);
                }
            });
    }

    function revisarSeleccion() {
        let residente = $("#residente_id_tr").val();
        let planificacion = $("#plan_id_tr").val();
        if (residente !== null && residente !== '0' && planificacion !== null && planificacion !== '0') {
            btnCrud.prop("disabled", false);
        } else {
            btnCrud.prop("disabled", true);
        }
    }

    //#endregion

    //#region Funciones Prepare
    function prepareSelect(id) {
        tituloModal.text('Detalles sobre la Transacción');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id) {
        tituloModal.text('Editar Transacción');
        $('#rsdtForm').find(':input').prop('disabled', false);
        show(id);
    }

    function prepareDelete(id) {
        tituloModal.text('Eliminar Transacción');
        mensajeModal.html('La siguiente transacción se eliminará a continuación,<br>¿Está seguro?');
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
                            <th class="align-middle" rowspan="2">ID</th>
                            <th class="align-middle" rowspan="2">ACTIVIDAD</th>
                            <th colspan="2">RESIDENTE</th>
                            <th class="align-middle" rowspan="2">FECHA</th>
                            <th class="align-middle" rowspan="2">TRANSACCIÓN</th>
                            <th class="align-middle" rowspan="2">MONTO BS.</th>
                            <th class="align-middle" rowspan="2" width="200">ACCIONES</th>
                        </tr>
                        <tr>
                            <th>CI</th>
                            <th>NOMBRE</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                    response.data.data.forEach(transaccion => {
                        let residente = transaccion.residente;
                        let planificacion = transaccion.planificacion;
                        html += `
                        <tr>
                            <td>${transaccion.id_tr}</td>
                            <td>${planificacion.motivo_plan}</td>
                            <td>${residente.ci_rsdt}</td>
                            <td>${residente.nombre_rsdt} ${residente.apellidop_rsdt} ${residente.apellidom_rsdt}</td>
                            <td>${transaccion.fecha_tr}</td>
                            <td>${transaccion.tipo_tr}</td>
                            <td>${transaccion.tipo_tr == 'Embolso' ? transaccion.monto_tr : '- ' + transaccion.monto_tr}</td>
                            <td>
                                <button id='btnSelect' data-id="${transaccion.id_tr}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                <button id='btnEdit' data-id="${transaccion.id_tr}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                <button id='btnDelete' data-id="${transaccion.id_tr}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
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
                    searchInput.val(response.data.id_tr);
                    index(response.data.id_tr);
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
        let transaccion = data;
        $('#id_tr').val(transaccion.id_tr);
        $('#plan_id_tr').val(transaccion.plan_id_tr).trigger('change');
        $('#residente_id_tr').val(transaccion.residente_id_tr).trigger('change');
        $('#monto_tr').val(transaccion.monto_tr);
        $('#tipo_tr').val(transaccion.tipo_tr);
        $('#seccionFecha').removeClass('d-none');
        $('#fechaTransaccion').prop('disabled', true);
        $('#fechaTransaccion').val(transaccion.fecha_tr);
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
                    MostrarNotificacion('danger', response.message, 5);
                }
            },
            error: function (xhr, status, error) {
                MostrarNotificacion('danger', xhr.responseText, 5);
            }
        });
    }

    function update(id) {
        let formData = $('#rsdtForm').serialize();
        console.log(urlUpdate, id);

        $.ajax({
            url: urlUpdate + id,
            type: 'PUT',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.state) {
                    MostrarNotificacion('success', response.message, 5);
                    searchInput.val(response.data.id_tr);
                    index(response.data.id_tr);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                } else {
                    MostrarNotificacion('danger', response.message, 5);
                }
            },
            error: function (xhr, status, error) {
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
                    MostrarNotificacion('danger', response.message, 5);
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
    getPlanificacionesOnSelect();
    getResidentesOnSelect();
    revisarSeleccion();
    //#endregion

    //#region Busqueda
    searchInput.on('input', function () {
        index($(this).val());
    });

    $('#totalResultados').on('input', function () {
        index(searchInput.val());
    });
    //#endregion

    //#region Select2 con Buscador
    $("#residente_id_tr").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
        dropdownParent: $('#modalMain')
    });

    $("#plan_id_tr").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
        dropdownParent: $('#modalMain')
    });

    $("#residente_id_tr, #plan_id_tr").on('change', function () {
        revisarSeleccion();
    });
    //#endregion

    //#region CRUD
    btnCrud.click(function (e) {
        e.preventDefault();
        let action = $(this).attr('name');
        let id = $('#id_tr').val();
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
