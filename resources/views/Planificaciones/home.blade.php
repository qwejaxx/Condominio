@extends('layouts.menu')
@section('stylesExtra')
    <link href="{{ asset('Resources/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Resources/css/select2-bootstrap-5-theme.css') }}" rel="stylesheet">
@endsection
@section('scriptsExtra')
    <script src="{{ asset('Resources/js/planificacion.js') }}"></script>
    <script src="{{ asset('Resources/js/select2.min.js') }}"></script>
@endsection
@section('controllerLinks')
    <input id="url-user" type="hidden" name="url-user" value="{{ route('indexRepRsdt') }}">
    <input id="url-index" type="hidden" name="url-index" value="{{ route('indexPlan') }}">
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
            <button class="btn btn-secondary btn-sm mb-3" type="button" id="btnAgregar" data-bs-toggle="modal"
                data-bs-target="#modalMain">
                <i class="fa-solid fa-plus me-2"></i>Nueva Actividad
            </button>
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
                                <span>Administrar Participantes</span>
                            </div>
                            <div>
                                <i id="asignacionBtn" class="fas fa-user-plus modal-close" data-bs-toggle="modal"
                                    data-bs-target="#modalAsignaciones"></i>
                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                            </div>
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
                            <div id="botonesModalParticipantes">
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

            <div class="modal fade" id="modalAsignaciones" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <div class="header">
                            <div>Agregar Participantes</div>
                            <div>
                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                            </div>
                        </div>
                        <div class="body">
                            <select id="SelParti"></select>
                            <hr class="mt-3 mb-2">
                            <p class="mb-0">
                                Nuevos Participantes
                                <span class="fw-bold"> · </span>
                                <span id="contadorNuevosParticipantes"></span>
                            </p>
                            <div id="participantesSeleccionadosContainer"></div>
                        </div>
                        <div class="footer">
                            <div id="botonesModalAsignaciones">
                                <button id="btnRegresarAsig" type="button" class="btn btn-sm btn-outline-light"
                                    data-bs-toggle="modal" data-bs-target="#modalParticipantes">Regresar</button>
                                <button name="btnAsignar" id="btnAsignar" class="btn btn-sm btn-secondary ms-1"
                                    disabled>Asignar</button>
                            </div>
                        </div>
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
@endsection
