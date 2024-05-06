@extends('layouts.menu')
@section('stylesExtra')
    <link href="{{ asset('Resources/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Resources/css/select2-bootstrap-5-theme.css') }}" rel="stylesheet">
@endsection
@section('scriptsExtra')
    <script src="{{ asset('Resources/js/registroVisita.js') }}"></script>
    <script src="{{ asset('Resources/js/select2.min.js') }}"></script>
@endsection
@section('controllerLinks')
    <input id="url-index" type="hidden" name="url-index" value="{{ route('indexRgVst') }}">
    <input id="url-store" type="hidden" name="url-store" value="{{ route('storeRgVst') }}">
    <input id="url-show" type="hidden" name="url-show" value="{{ route('ControlVisitas') }}">
    <input id="url-get-res" type="hidden" name="url-get-res" value="{{ route('getRes') }}">
    <input id="url-get-vis" type="hidden" name="url-get-vis" value="{{ route('getVis') }}">
@endsection
@section('Titulo')
    <i class="fa-solid fa-list me-2"></i>Registro de Visitas
@endsection
@section('Contenido')
    <div class="row justify-content-between">
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control form-control-sm" placeholder="Buscar" name="search"
                    id="search">
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3 d-flex justify-content-end">
            <button class="btn btn-secondary btn-sm mb-3" type="button" id="btnAgregar" data-bs-toggle="modal"
                data-bs-target="#modalMain">
                <i class="fa-solid fa-plus me-2"></i>Nueva Visita
            </button>
            <div class="modal fade" id="modalMain" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <form id="rsdtForm">
                            <input id="id_vis" type="hidden" name="id_vis">
                            <div class="header">
                                <div><i class="fa-solid fa-sitemap me-2"></i><span id="modal-titulo">Registrar Visita</span>
                                </div>
                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                            </div>
                            <div class="body">
                                <div class="text-center" id="modal-mensaje"></div>
                                @csrf
                                <div class="d-flex flex-column mb-1">
                                    <label for="visitante_id_vis" class="label-form">Visitante:</label>
                                    <select class="form-select form-select-sm" id="visitante_id_vis"
                                        name="visitante_id_vis">
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="visitado_id_vis" class="label-form">Residente Visitado:</label>
                                    <select class="form-select form-select-sm" id="visitado_id_vis" name="visitado_id_vis">
                                    </select>
                                </div>
                                <div id="seccionFecha" class="d-flex flex-column mb-1 d-none">
                                    <label for="fecha_visita" class="label-form">Fecha:</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="fecha_visita"
                                        name="fecha_visita">
                                </div>
                            </div>
                            <div class="footer">
                                <div id="botonesModal">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="store" id="btnCrud"
                                        class="btn btn-sm btn-outline-light">Registrar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="index-error" class="text-small text-center"></div>
    <div id="index-table">
        <div class="table-responsive rounded shadow-sm">
            <table id="tabla"
                class="table text-nowrap table-sm table-striped table-bordered text-center align-middle table-hover m-0">
            </table>
        </div>
    </div>
    <div id="seccionTotalResultados" class="mt-3 d-none">
        <nav class="row g-3 justify-content-between">
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3">
                <ul id="pagination-container"
                    class="pagination pagination-sm justify-content-center justify-content-sm-start m-0">
                </ul>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3">
                <div class="input-group input-group-sm justify-content-center justify-content-sm-end">
                    <label class="input-group-text border-secondary bg-gray" for="totalResultados">N° Resultados:</label>
                    <select class="form-select border-secondary page-select" id="totalResultados" name="totalResultados">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
        </nav>
    </div>
    <script>
        $(document).ready(function() {
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

            let urlGetResidentes = $('#url-get-res').val();
            let urlGetVisitantes = $('#url-get-vis').val();
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
                setTimeout(function() {
                    alerta.alert("close");
                }, segundos * 1000);
            }

            function resetAllOnModal() {
                $('#rsdtForm')[0].reset();
                $('#rsdtForm').find(':input').prop('disabled', false);
                $('#botonesModal').removeClass('opacity-0');
                $('#visitante_id_vis').val($('#visitante_id_vis option:first').val()).trigger('change');
                $('#visitado_id_vis').val($('#visitado_id_vis option:first').val()).trigger('change');
                $('#seccionFecha').addClass('d-none');
                tituloModal.text('Registrar Visita');
                mensajeModal.html('');
                btnCrud.attr('name', 'store');
                btnCrud.text('Registrar');
            }

            function getResidentesOnSelect() {
                let _token = $('meta[name="csrf-token"]').attr('content');
                let select = $("#visitado_id_vis");

                $.ajax({
                    url: urlGetResidentes,
                    type: 'GET',
                    data: {
                        _token: _token
                    },
                    dataType: 'json',
                    success: function(response) {
                        let Residentes = response.data;

                        if (Residentes.length > 0) {
                            select.empty();
                            select.append($("<option>", {
                                value: 0,
                                text: 'Seleccionar Residente:',
                                selected: true,
                                disabled: true
                            }));
                            Residentes.forEach(Residente => {
                                let idResidente = Residente.id_rsdt;
                                let nombreResidente = Residente.nombre_rsdt + ' ' + Residente
                                    .apellidop_rsdt + ' ' + Residente.apellidom_rsdt;

                                let option = `
                                    <option value='${idResidente}'>${nombreResidente}</option>
                                `;
                                select.append(option);
                            });
                        } else {
                            select.empty();
                            select.append($("<option>", {
                                value: 0,
                                text: 'No hay Residentes disponibles.',
                                selected: true,
                                disabled: true
                            }));
                        }
                    },
                    error: function(xhr, status, error) {
                        MostrarNotificacion('danger', xhr.responseText, 5);
                    }
                });
            }

            function getVisitantesOnSelect() {
                let _token = $('meta[name="csrf-token"]').attr('content');
                let select = $("#visitante_id_vis");

                $.ajax({
                    url: urlGetVisitantes,
                    type: 'GET',
                    data: {
                        _token: _token
                    },
                    dataType: 'json',
                    success: function(response) {
                        let Residentes = response.data;

                        if (Residentes.length > 0) {
                            select.empty();
                            select.append($("<option>", {
                                value: 0,
                                text: 'Seleccionar Visitante:',
                                selected: true,
                                disabled: true
                            }));
                            Residentes.forEach(Residente => {
                                let idResidente = Residente.id_rsdt;
                                let nombreResidente = Residente.nombre_rsdt + ' ' + Residente
                                    .apellidop_rsdt + ' ' + Residente.apellidom_rsdt;

                                let option = `
                                    <option value='${idResidente}'>${nombreResidente}</option>
                                `;
                                select.append(option);
                            });
                        } else {
                            select.empty();
                            select.append($("<option>", {
                                value: 0,
                                text: 'No hay visitantes disponibles.',
                                selected: true,
                                disabled: true
                            }));
                        }
                    },
                    error: function(xhr, status, error) {
                        MostrarNotificacion('danger', xhr.responseText, 5);
                    }
                });
            }

            function revisarSeleccion() {
                let visitado = $("#visitado_id_vis").val();
                let visitante = $("#visitante_id_vis").val();
                if (visitado !== null && visitado !== '0' && visitante !== null && visitante !== '0') {
                    btnCrud.prop("disabled", false);
                } else {
                    btnCrud.prop("disabled", true);
                }
            }

            //#endregion

            //#region Funciones Prepare
            function prepareSelect(id) {
                tituloModal.text('Detalles sobre la Visista');
                $('#rsdtForm').find(':input').prop('disabled', true);
                $('#password').attr('type', 'password');
                $('#botonesModal').addClass('opacity-0');
                show(id);
            }

            function prepareEdit(id) {
                tituloModal.text('Editar Visita');
                $('#rsdtForm').find(':input').prop('disabled', false);
                $('#password').attr('type', 'password');
                show(id);
            }

            function prepareDelete(id) {
                tituloModal.text('Eliminar Visita');
                mensajeModal.html('La siguiente visita se eliminará a continuación,<br>¿Está seguro?');
                $('#rsdtForm').find(':input:not(button)').prop('disabled', true);

                $('#password').attr('type', 'password');
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
                        let pageButton = $(
                            '<li class="page-item"><button class="page-link border-secondary pag-item">' + i +
                            '</button></li>');
                        if (i === currentPage) {
                            pageButton.addClass('active');
                        }
                        pageButton.click(function() {
                            if (currentPage !== i) {
                                index(search, url + '&page=' + i);
                            }
                        });
                        paginationContainer.append(pageButton);
                    }
                    return; // Salir de la función después de generar los botones de número de página
                }

                // Botón para ir a la primera página
                let firstPageButton = $('<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') +
                    '"><button class="page-link border-secondary pag-item">&laquo;</button></li>');
                firstPageButton.click(function() {
                    if (currentPage !== 1) {
                        index(search, url + '&page=1');
                    }
                });
                paginationContainer.append(firstPageButton);

                // Botón para ir a la página anterior
                let previousPageButton = $('<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') +
                    '"><button class="page-link border-secondary pag-item">&lt;</button></li>');
                previousPageButton.click(function() {
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
                    let pageButton = $('<li class="page-item ' + (i === currentPage ? 'active' : '') +
                        '"><button class="page-link border-secondary pag-item">' + i + '</button></li>');
                    pageButton.click(function() {
                        if (currentPage !== i) {
                            index(search, url + '&page=' + i);
                        }
                    });
                    paginationContainer.append(pageButton);
                }

                // Botón para ir a la página siguiente
                let nextPageButton = $('<li class="page-item ' + (currentPage === lastPage ? 'disabled' : '') +
                    '"><button class="page-link border-secondary pag-item">&gt;</button></li>');
                nextPageButton.click(function() {
                    if (currentPage < lastPage) {
                        let nextPage = currentPage + 1;
                        index(search, url + '&page=' + nextPage);
                    }
                });
                paginationContainer.append(nextPageButton);

                // Botón para ir a la última página
                let lastPageButton = $('<li class="page-item ' + (currentPage === lastPage ? 'disabled' : '') +
                    '"><button class="page-link border-secondary pag-item">&raquo;</button></li>');
                lastPageButton.click(function() {
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
                    data: {
                        _token: _token,
                        search: search,
                        totalResultados: totalResultados
                    },
                    dataType: 'json',
                    success: function(response) {
                        tableIndex.empty();
                        if (response.data.data.length > 0) {
                            error.html('');
                            html = `
                        <thead class="table-secondary fw-semibold">
                            <tr>
                                <th class="align-middle" rowspan="2">ID</th>
                                <th colspan="2">VISITANTE</th>
                                <th colspan="2">VISITADO</th>
                                <th class="align-middle" rowspan="2">FECHA DE VISITA</th>
                                <th class="align-middle" rowspan="2" width="200">ACCIONES</th>
                            </tr>
                            <tr>
                                <th>CI</th>
                                <th>NOMBRE</th>
                                <th>CI</th>
                                <th>NOMBRE</th>
                            </tr>
                        </thead>
                        <tbody>
                    `;
                            response.data.data.forEach(regvisita => {
                                let visitante = regvisita.visitante;
                                let visitado = regvisita.visitado;
                                html += `
                            <tr>
                                <td>${regvisita.id_vis}</td>
                                <td>${visitante.ci_rsdt}</td>
                                <td>${visitante.nombre_rsdt} ${visitante.apellidop_rsdt} ${visitante.apellidom_rsdt}</td>
                                <td>${visitado.ci_rsdt}</td>
                                <td>${visitado.nombre_rsdt} ${visitado.apellidop_rsdt} ${visitado.apellidom_rsdt}</td>
                                <td>${regvisita.fecha_vis}</td>
                                <td>
                                    <button id='btnSelect' data-id="${regvisita.id_vis}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                    <button id='btnEdit' data-id="${regvisita.id_vis}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                    @role('Administrador')
                                    <button id='btnDelete' data-id="${regvisita.id_vis}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
                                    @endrole
                                </td>
                            </tr>
                        `;
                            });
                            html += `</tbody>`;
                            tableIndex.append(html);

                            // Llamar a la función para generar los botones de paginación
                            generatePaginationButtons(response.data.current_page, response.data
                                .last_page, url, search);
                            seccionTotalResultados.removeClass('d-none');
                        } else {
                            error.html('No se encontraron resultados.')
                            let paginationContainer = $('#pagination-container');
                            paginationContainer.empty();
                            seccionTotalResultados.addClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
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
                    success: function(response) {
                        if (response.state) {
                            MostrarNotificacion('success', response.message, 5);
                            searchInput.val(response.data.id_vis);
                            index(response.data.id_vis);
                            modalMain.modal('hide');
                            setTimeout(function() {
                                searchInput.focus();
                            }, 700);
                        } else {
                            MostrarNotificacion('danger', response.message, 5);
                        }
                    },
                    error: function(xhr, status, error) {
                        MostrarNotificacion('danger', xhr.responseText, 5);
                    }
                });
            }

            function llenarFormulario(data) {
                let regvisita = data;
                $('#id_vis').val(regvisita.id_vis);
                $('#visitante_id_vis').val(regvisita.visitante_id_vis).trigger('change');
                $('#visitado_id_vis').val(regvisita.visitado_id_vis).trigger('change');
                $('#seccionFecha').removeClass('d-none');
                $('#fecha_visita').prop('disabled', true);
                $('#fecha_visita').val(regvisita.fecha_vis);
            }

            function show(id) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: urlShow + id,
                    type: 'GET',
                    data: {
                        _token: _token
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.state) {
                            llenarFormulario(response.data);
                            modalMain.modal('show');
                        } else {
                            MostrarNotificacion('danger', response.message, 5);
                        }
                    },
                    error: function(xhr, status, error) {
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
                    success: function(response) {
                        if (response.state) {
                            MostrarNotificacion('success', response.message, 5);
                            searchInput.val(response.data.id_vis);
                            index(response.data.id_vis);
                            modalMain.modal('hide');
                            setTimeout(function() {
                                searchInput.focus();
                            }, 700);
                        } else {
                            MostrarNotificacion('danger', response.message, 5);
                        }
                    },
                    error: function(xhr, status, error) {
                        MostrarNotificacion('danger', xhr.responseText, 5);
                    }
                });
            }

            function destroy(id) {
                let _token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: urlDelete + id,
                    type: 'DELETE',
                    data: {
                        _token: _token
                    },
                    success: function(response) {
                        if (response.state) {
                            MostrarNotificacion('success', response.message, 5);
                            searchInput.val('');
                            index();
                            modalMain.modal('hide');
                        } else {
                            MostrarNotificacion('danger', response.message, 5);
                        }
                    },
                    error: function(xhr, status, error) {
                        MostrarNotificacion('danger', xhr.responseText, 5);
                    }
                });
            }
            //#endregion

            //#region Interacción DOM
            //#region Funciones de Carga Inicial
            index();
            getVisitantesOnSelect();
            getResidentesOnSelect();
            revisarSeleccion();
            //#endregion

            //#region Busqueda
            searchInput.on('input', function() {
                index($(this).val());
            });

            $('#totalResultados').on('input', function() {
                index(searchInput.val());
            });
            //#endregion

            //#region Select2 con Buscador
            $("#visitado_id_vis").select2({
                theme: "bootstrap-5",
                selectionCssClass: "select2--small",
                dropdownCssClass: "select2--small",
                dropdownParent: $('#modalMain')
            });

            $("#visitante_id_vis").select2({
                theme: "bootstrap-5",
                selectionCssClass: "select2--small",
                dropdownCssClass: "select2--small",
                dropdownParent: $('#modalMain')
            });

            $("#visitado_id_vis, #visitante_id_vis").on('change', function() {
                revisarSeleccion();
            });
            //#endregion

            //#region CRUD
            btnCrud.click(function(e) {
                e.preventDefault();
                let action = $(this).attr('name');
                let id = $('#id_vis').val();
                switch (action) {
                    case "store": {
                        store();
                    }
                    break;
                    case "edit": {
                        update(id);
                    }
                    break;
                    case "delete": {
                        destroy(id);
                    }
                    break;
                }
            });
            //#endregion
            //#endregion

            //#region Activacion de botones de CRUD
            tableIndex.on('click', '#btnSelect', function() {
                let id = $(this).data('id');
                btnCrud.attr('name', 'show');
                btnCrud.text('Show');
                prepareSelect(id);
            });

            tableIndex.on('click', '#btnEdit', function() {
                let id = $(this).data('id');
                btnCrud.attr('name', 'edit');
                btnCrud.text('Editar');
                prepareEdit(id);
            });

            tableIndex.on('click', '#btnDelete', function() {
                let id = $(this).data('id');
                btnCrud.attr('name', 'delete');
                btnCrud.text('Eliminar');
                prepareDelete(id);
            });
            //#endregion

            //#region Configuracion en Modales
            $("#modalMain").on("shown.bs.modal", function() {

            });

            modalMain.on('hidden.bs.modal', function() {
                resetAllOnModal();
            });
            //#endregion
        });
    </script>
@endsection
