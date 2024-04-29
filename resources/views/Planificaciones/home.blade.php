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
    <input id="url-index" type="hidden" name="url-index" value="{{ route('indexPlan') }}">
    <input id="url-store" type="hidden" name="url-store" value="{{ route('storePlan') }}">
    <input id="url-show" type="hidden" name="url-show" value="{{ route('Planificaciones') }}">
@endsection
@section('Titulo')
    <i class="fa-solid fa-list me-2"></i>Lista de Planificaciones
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
                <i class="fa-solid fa-plus me-2"></i>Nueva Planificación
            </button>
            <div class="modal fade" id="modalMain" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <input id="id_plan" type="hidden" name="id_plan">
                        <form id="rsdtForm">
                            <div class="header">
                                <div><i class="fa-solid fa-sitemap me-2"></i><span id="modal-titulo">Nueva
                                        Planificación</span>
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
                                    <textarea class="form-control form-control-sm" id="descripcion_plan" name="descripcion_plan" rows="3"></textarea>
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="area_plan" class="label-form">Area:</label>
                                    <input type="text" class="form-control form-control-sm" id="area_plan"
                                        name="area_plan">
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="pago_plan" class="label-form">Pago:</label>
                                    <input type="text" class="form-control form-control-sm" id="pago_plan"
                                        name="pago_plan">
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
            <div class="modal fade" id="modalDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <div class="header">
                            <div><i class="fa-solid fa-folder-open me-2"></i><span>Detalles del
                                    Documento</span></div>
                            <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                        </div>
                        <div class="body">
                            <div id="seccionSeguimiento" class="d-flex flex-column align-items-center">
                                <!-- CARDS DE CONTENIDO AQUI -->
                            </div>
                        </div>
                        <div class="footer"></div>
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
