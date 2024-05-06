@extends('layouts.menu')
@section('stylesExtra')
    <link href="{{ asset('Resources/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Resources/css/select2-bootstrap-5-theme.css') }}" rel="stylesheet">
@endsection
@section('scriptsExtra')
    <script src="{{ asset('Resources/js/select2.min.js') }}"></script>
@endsection
@section('controllerLinks')
    <input id="url-user" type="hidden" name="url-user" value="{{ route('indexRepRsdt') }}">
    <input id="url-index" type="hidden" name="url-index" value="{{ route('indexActividades') }}">
    <input id="url-store" type="hidden" name="url-store" value="{{ route('storePlan') }}">
    <input id="url-show" type="hidden" name="url-show" value="{{ route('Planificaciones') }}">
    <input id="url-store-asignaciones" type="hidden" name="url-store-asignaciones"
        value="{{ route('storeParticipantes') }}">
    <input id="url-update-asignaciones" type="hidden" name="url-update-asignaciones"
        value="{{ route('updateParticipantes') }}">
@endsection
@section('Titulo')
    <i class="fa-solid fa-list me-2"></i>Lista de Actividades
@endsection
@section('Contenido')
    <div class="row justify-content-between">
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3">
            <div class="input-group mb-3">
                <input type="text" class="form-control form-control-sm" placeholder="Buscar" name="search"
                    id="search">
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3 d-flex justify-content-end">
            <div class="modal fade" id="modalMain" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <input id="id_plan" type="hidden" name="id_plan">
                        <form id="rsdtForm">
                            <div class="header">
                                <div><i class="fa-solid fa-sitemap me-2"></i><span id="modal-titulo">Nueva
                                        Actividad</span>
                                </div>
                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                            </div>
                            <div class="body">
                                <div class="text-center" id="modal-mensaje"></div>
                                @csrf
                                <div class="d-flex flex-column mb-1">
                                    <label for="motivo_plan" class="label-form">Motivo:</label>
                                    <input type="text" class="form-control form-control-sm" id="motivo_plan"
                                        name="motivo_plan">
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="descripcion_plan" class="label-form">Descripción:</label>
                                    <textarea class="area form-control form-control-sm" id="descripcion_plan" name="descripcion_plan" rows="3"></textarea>
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="area_plan" class="label-form">Area:</label>
                                    <input type="text" class="form-control form-control-sm" id="area_plan"
                                        name="area_plan">
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="cuota_plan" class="label-form">Cuota por residente Bs.:</label>
                                    <input type="text" class="form-control form-control-sm" id="cuota_plan"
                                        name="cuota_plan">
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="inicio_plan" class="label-form">Inicio:</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="inicio_plan"
                                        name="inicio_plan">
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="fin_plan" class="label-form">Fin:</label>
                                    <input type="datetime-local" class="form-control form-control-sm" id="fin_plan"
                                        name="fin_plan">
                                </div>
                            </div>
                            <div class="footer">
                                <div id="botonesModal">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" name="store" id="btnCrud"
                                        class="btn btn-sm btn-outline-light">Agregar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalParticipantes" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <div class="header">
                            <div>
                                <i class="fa-solid fa-list me-2"></i>Lista de Participantes
                            </div>
                            <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                        </div>
                        <div class="body">
                            <div class="text-center"></div>
                            <div>
                                <h4 id="titulo-asignacion" class="m-0 text-center text-uppercase"></h4>
                                <hr class="my-2">
                                <input type="text" class="form-control form-control-sm mt-2 mb-2" placeholder="Buscar"
                                    name="searchParticipantes" id="searchParticipantes">
                                <div id="noResultsMessage" class="mb-2 d-none">No se encontraron resultados.</div>
                                <p class="mb-0">
                                    Participantes
                                    <span class="fw-bold"> · </span>
                                    <span id="contadorParticipantes"></span>
                                </p>
                                <div id="participantesContainer"></div>
                                <div class="mt-2 mb-2" id="etiquetaOculta" hidden>
                                    Participantes a eliminar
                                    <span class="fw-bold"> · </span>
                                    <span id="contadorParticipantesEliminados"></span>
                                </div>
                                <div id="participantesEliminadosContainer"></div>
                            </div>
                        </div>
                        <div class="footer">
                            <div id="botonesModalParticipantes" class="opacity-0">
                                <button type="button" class="btn btn-sm btn-outline-light"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button disabled name="btnUpdateAsignaciones" id="btnUpdateAsignaciones"
                                    class="btn btn-sm btn-secondary ms-1">Guardar
                                    Cambios</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div id="cardsDep" class="row gy-3"></div>
        </div>
    </div>
    <div id="index-error" class="text-small text-center"></div>
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
    let urlNoAsignaciones = urlShow + 'NoAsignaciones/';
    let urlAsignaciones = urlShow + 'Asignaciones/';
    let urlStoreAsignaciones = $('#url-store-asignaciones').val();
    let urlUpdateAsignaciones = $('#url-update-asignaciones').val();

    let modalParticipantes = $('#modalParticipantes');
    let modalAsignaciones = $('#modalAsignaciones');
    let btnAsignar = $('#btnAsignar');
    let btnUpdateAsignaciones = $('#btnUpdateAsignaciones');

    let arrayParticipantes = [];
    let arrayParticipantesOriginal = [];
    let arrayParticipantesNuevos = []; // Array para participantes nuevos
    let arrayParticipantesEliminados = [];
    let contenedorParticipantes = $('#participantesContainer'); // Div para participantes ya registrados
    let contenedorSeleccionados = $('#participantesSeleccionadosContainer') // Div para nuevos participantes
    let contenedorEliminados = $('#participantesEliminadosContainer'); // Div para eliminar participantes
    //#endregion

    //#region Funciones Extras
    function resetAllOnModal() {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#propietario_id_mas').val($('#propietario_id_mas option:first').val()).trigger('change');
        tituloModal.text('Nueva Actividad');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Agregar');
    }

    function resetAllOnModalParticipantes() {
        $('#searchParticipantes').val('');
        arrayParticipantesOriginal = [];
        arrayParticipantes = [];
        arrayParticipantesEliminados = [];
        actualizarContadores();
        btnUpdateAsignaciones.prop('disabled', true);
        contenedorParticipantes.html('');
        contenedorEliminados.html('');
    }

    function resetAllOnModalAsignaciones()
    {
        arrayParticipantesNuevos = [];
        contenedorSeleccionados.html('');
        actualizarContadorNuevos();
        btnAsignar.prop('disabled', true);
    }

    function resetSelect() {
        $('#SelParti').val(0);
    }

    function moverParticipante(participanteAMover, accion, divParticipante) {
        divParticipante.remove();
        if (accion == 'Eliminar') {
            arrayParticipantes = arrayParticipantes.filter(idParticipante => idParticipante !== participanteAMover.id_rsdt);
            if (esParticipanteUnico(participanteAMover.id_rsdt, arrayParticipantesEliminados)) {
                arrayParticipantesEliminados.push(participanteAMover.id_rsdt);
                let nuevoDiv = crearDivParticipante(participanteAMover, 'Agregar');
                contenedorEliminados.append(nuevoDiv);
            }
            else {
                console.log('El participante ya se encuentra en la lista de eliminados.');
            }
        }
        else {
            arrayParticipantesEliminados = arrayParticipantesEliminados.filter(idParticipante => idParticipante !== participanteAMover.id_rsdt);
            if (esParticipanteUnico(participanteAMover.id_rsdt, arrayParticipantes)) {
                arrayParticipantes.push(participanteAMover.id_rsdt);
                let nuevoDiv = crearDivParticipante(participanteAMover, 'Eliminar');
                contenedorParticipantes.append(nuevoDiv);
            }
            else {
                console.log('El participante ya se encuentra en la actividad.');
            }
        }
        actualizarContadores();
    }

    function actualizarContadores() {
        let contadorParticipantes = arrayParticipantes.length;
        $('#contadorParticipantes').text(contadorParticipantes);
        let contadorParticipantesEliminados = arrayParticipantesEliminados.length;
        $('#contadorParticipantesEliminados').text(contadorParticipantesEliminados);
        $('#etiquetaOculta').attr('hidden', contadorParticipantesEliminados < 1);

        btnUpdateAsignaciones.prop('disabled', contadorParticipantes === arrayParticipantesOriginal.length)
    }

    function actualizarContadorNuevos() {
        let contadorNuevos = arrayParticipantesNuevos.length;
        $('#contadorNuevosParticipantes').text(contadorNuevos);

        btnAsignar.prop('disabled', contadorNuevos < 1)
    }

    function esParticipanteUnico(idParticipanteComparado, array) {
        let esUnico = true;
        array.forEach(idParticipante => {
            if (idParticipanteComparado === idParticipante) {
                esUnico = false;
            }
        });
        return esUnico;
    }

    function crearDivParticipante(participante, accion) {
        let idParticipante = participante.id_rsdt;
        let nombreParticipante = participante.apellidop_rsdt ? participante.nombre_rsdt + ' ' + participante.apellidop_rsdt : participante.nombre_rsdt;
        let rolParticipante = participante.usuario.roles[0].name;
        let pagadoParticipante = participante.totalPagado;
        let restanteParticipante = participante.restante;
        let icono = '<i class="fas fa-times"></i>';
        if (accion == 'Agregar') {
            icono = '<i class="fas fa-plus"></i>';
        }

        let nuevoDiv = $(`
            <div class="datoUser mt-2 d-flex justify-content-center align-items-stretch">
                <div class="datoS card text-bg-gray w-100 rounded-end-0 shadow-sm">
                    <div class="card-body border-secondary px-2 py-1">
                        <div class="d-flex align-items-center bg-gray">
                            <div class="user-iconAP" style="background-image: url(Resources/imgs/perfil.jpg)"></div>
                            <div class="ms-2 w-100">
                                <div class="id_participante" hidden>${idParticipante}</div>
                                <div class="nombre_participante fw-medium">${nombreParticipante}</div>
                                <div class="fw-normal rol_participante" style="font-size: 10px; line-height: 10px;">${rolParticipante}</div>
                                <div class="d-flex justify-content-between ${pagadoParticipante ? '' : 'd-none'}" style="font-size: 12px;">
                                    <div class="fw-medium">Pagado Bs.: <span class="pagado_participante ">${pagadoParticipante}</span></div>
                                    <div class="fw-medium">Debe Bs.: <span class="restante_participante ">${restanteParticipante}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        return nuevoDiv;
    }
    //#endregion

    //#region Funciones Prepare
    function prepareAsignacion(id) {
        $('#id_plan').val(id);
        getParticipantes(id);
    }

    function prepareSelect(id) {
        tituloModal.text('Detalles sobre la Actividad');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id) {
        tituloModal.text('Editar Actividad');
        $('#rsdtForm').find(':input').prop('disabled', false);
        show(id);
    }

    function prepareDelete(id) {
        tituloModal.text('Eliminar Actividad');
        mensajeModal.html('La siguiente actividad se eliminará a continuación,<br>¿Está seguro?');
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
    function getParticipantes(id, search = "") {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlAsignaciones + id,
            type: 'GET',
            data: { _token: _token, search: search },
            dataType: 'json',
            success: function (response) {
                if (response.state) {
                    $('#titulo-asignacion').text(response.motivo);
                    let asignaciones = response.data;
                    contadorParticipantes = asignaciones.length;
                    if (contadorParticipantes > 0) {
                        asignaciones.forEach(asignacion => {
                            let participante = asignacion.participante;
                            if (esParticipanteUnico(participante.id_rsdt, arrayParticipantes)) {
                                arrayParticipantes.push(participante.id_rsdt);
                                let divParticipante = crearDivParticipante(participante, 'Eliminar');
                                contenedorParticipantes.append(divParticipante);
                                modalParticipantes.modal('show');
                            }
                            else {
                                console.log('El participante ya se encuentra en la actividad.');
                            }
                        });
                        arrayParticipantesOriginal = arrayParticipantes.slice();
                    }
                    else {
                        console.log('Aún no hay participantes registrados.');
                    }
                    actualizarContadores();
                } else {
                    console.error(response.message);
                }

            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function getNoParticipantesOnSelect(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#SelParti");

        $.ajax(
            {
                url: urlNoAsignaciones + id,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let participantes = response.data;
                    if (participantes.length > 0) {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'Seleccionar Participante:', selected: true, disabled: true
                            }));
                        participantes.forEach(participante => {
                            let idParticipante = participante.id_rsdt;
                            let nombreParticipante = participante.nombre_rsdt + ' ' + participante.apellidop_rsdt;
                            let rolParticipante = participante.usuario.roles[0].name;

                            let option = `
                                <option data-rol='${rolParticipante}' value='${idParticipante}'>${nombreParticipante}</option>
                            `;
                            select.append(option);
                        });
                        modalParticipantes.modal('show');
                        actualizarContadorNuevos();
                    }
                    else {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'No hay participantes disponibles.', selected: true, disabled: true
                            }));
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX: " + error);
                }
            });
    }

    function index(search = "", url = urlIndex + '?page=1') {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let totalResultados = $('#totalResultados').val();
        let error = $('#index-error');
        let seccionTotalResultados = $('#seccionTotalResultados');
        let html = "";
        let idResidente = {{ Auth::user()->id }};

        $.ajax({
            url: url,
            type: 'GET',
            data: { _token: _token, search: search, totalResultados: totalResultados, idResidente: idResidente },
            dataType: 'json',
            success: function (response) {
                $('#cardsDep').empty();
                if (response.data.data.length > 0) {
                    error.html('');
                    response.data.data.forEach(planificacion => {
                        let asignacion = planificacion.asignaciones[0];
                        html += `
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 col-xxl-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="m-0">${planificacion.motivo_plan}</h6>
                                            <div class="dropend">
                                                <button class="btn-more-options" type="button" data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis"></i>
                                                </button>
                                                <ul class="dropdown-menu p-0" style="width: auto; line-height: 1px;">
                                                    <li>
                                                        <button id="btnSelect" data-id="${planificacion.id_plan}" class="dropdown-item rounded-top px-2 py-2"><i
                                                                class="fa-solid fa-clipboard me-3"></i>Detalles</button>
                                                    </li>
                                                    <li>
                                                        <button id='btnAdd' data-id="${planificacion.id_plan}" class='dropdown-item rounded-top px-2 py-2'><i class='fas fa-users me-2'></i>Ver Participantes</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <h3 class="m-0">PAGADO: ${asignacion.totalPagado} Bs.</h3>
                                            <div>Debe: ${asignacion.restante} Bs.</div>
                                            <hr class="mt-1">
                                            <h6 class="m-0">Inicio: <span class="fw-normal">${planificacion.inicio_plan}</span></h6>
                                            <h6 class="m-0">Conclusión: <span class="fw-normal">${planificacion.fin_plan}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $('#cardsDep').append(html);

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

    function llenarFormulario(data) {
        let planificacion = data;
        $('#id_plan').val(planificacion.id_plan);
        $('#motivo_plan').val(planificacion.motivo_plan);
        $('#descripcion_plan').val(planificacion.descripcion_plan);
        $('#area_plan').val(planificacion.area_plan);
        $('#cuota_plan').val(planificacion.cuota_plan);
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
    //#endregion

    //#region Interacción DOM

    //#region Extras
    $("#SelParti").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
        dropdownParent: $('#modalAsignaciones')
    });
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

    //#region Activacion de botones de CRUD
    $('#cardsDep').on('click', '#btnAdd', function () {
        let id = $(this).data('id');
        prepareAsignacion(id);
    });

    $('#cardsDep').on('click', '#btnSelect', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'show');
        btnCrud.text('Show');
        prepareSelect(id);
    });
    //#endregion

    //#region Configuracion en Modales
    modalMain.on('hidden.bs.modal', function () {
        resetAllOnModal();
    });

    modalParticipantes.on('hidden.bs.modal', function () {
        resetAllOnModalParticipantes();
    });
    //#endregion

    $('#searchParticipantes').on('input', function() {
        let searchText = $(this).val().toLowerCase();
        let resultadosEncontrados = false;
        $('#participantesContainer .datoUser').each(function() {
            let nombreParticipante = $(this).find('.nombre_participante').text().toLowerCase();
            if (nombreParticipante.includes(searchText)) {
                $(this).removeClass('d-none');
                resultadosEncontrados = true;
            } else {
                $(this).addClass('d-none');
            }
        });

        if (!resultadosEncontrados) {
            $('#noResultsMessage').removeClass('d-none');
        } else {
            $('#noResultsMessage').addClass('d-none');
        }
    });

    //#region Interaccion con Cards de Participantes en el Modal Participantes
    modalParticipantes.on('click', '.btn-card-participante', function () {
        let divParticipante = $(this).closest('.datoUser');
        let idParticipante = parseInt(divParticipante.find('.id_participante').text());
        let nombreParticipante = divParticipante.find('.nombre_participante').text();
        let rolParticipante = divParticipante.find('.rol_participante').text();
        let pagadoParticipante = divParticipante.find('.pagado_participante').text();
        let restanteParticipante = divParticipante.find('.restante_participante').text();
        let participante = {
            id_rsdt: idParticipante,
            nombre_rsdt: nombreParticipante,
            totalPagado: pagadoParticipante,
            restante: restanteParticipante,
            usuario: { roles: [{ name: rolParticipante }] }
        };
        let accion = $(this).attr('name');

        moverParticipante(participante, accion, divParticipante);
    });

    modalAsignaciones.on('click', '.btn-card-participante', function() {
        let divParticipante = $(this).closest('.datoUser');
        let idParticipanteDiv = parseInt(divParticipante.find('.id_participante').text());
        let accion = $(this).attr('name');

        if (accion == 'Eliminar')
        {
            arrayParticipantesNuevos = arrayParticipantesNuevos.filter(idParticipante => idParticipante !== idParticipanteDiv);
            actualizarContadorNuevos();
            divParticipante.remove();
        }
    });
    //#endregion
});

    </script>
@endsection
