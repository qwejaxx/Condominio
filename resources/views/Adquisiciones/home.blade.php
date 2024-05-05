@extends('layouts.menu')
@section('stylesExtra')
    <link href="{{ asset('Resources/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Resources/css/select2-bootstrap-5-theme.css') }}" rel="stylesheet">
@endsection
@section('scriptsExtra')
    <script src="{{ asset('Resources/js/adquisicion.js') }}"></script>
    <script src="{{ asset('Resources/js/select2.min.js') }}"></script>
@endsection
@section('controllerLinks')
    <input id="url-index" type="hidden" name="url-index" value="{{ route('indexAdq') }}">
    <input id="url-store" type="hidden" name="url-store" value="{{ route('storeAdq') }}">
    <input id="url-show" type="hidden" name="url-show" value="{{ route('Adquisiciones') }}">
    <input id="url-get-dptos-disp" type="hidden" name="url-get-dptos" value="{{ route('getDptos') }}">
    <input id="url-get-rep" type="hidden" name="url-get-rep" value="{{ route('getRep') }}">
    <input id="url-dpto" type="hidden" name="url-dpto" value="{{ route('Departamentos') }}">
@endsection
@section('Titulo')
    <i class="fa-solid fa-list me-2"></i>Lista de Adquisiciones
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
                <i class="fa-solid fa-plus me-2"></i>Nueva Adquisición
            </button>
            <div class="modal fade" id="modalMain" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <form id="rsdtForm">
                            <input id="id_reg" type="hidden" name="id_reg">
                            <div class="header">
                                <div><i class="fa-solid fa-sitemap me-2"></i><span id="modal-titulo">Nueva
                                        Adquisición</span>
                                </div>
                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                            </div>
                            <div class="body">
                                <div class="text-center" id="modal-mensaje"></div>
                                <div id="seccionAdquisicion">
                                    @csrf
                                    <div class="d-flex flex-column mb-1">
                                        <label for="departamento_id_reg" class="label-form">Departamento:</label>
                                        <select class="form-select form-select-sm" id="departamento_id_reg"
                                            name="departamento_id_reg">
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column mb-1">
                                        <label for="residente_id_reg" class="label-form">Residente:</label>
                                        <select class="form-select form-select-sm" id="residente_id_reg"
                                            name="residente_id_reg">
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column mb-1">
                                        <label for="tipoadq_reg" class="label-form">Tipo de Adquisición:</label>
                                        <select class="form-select form-select-sm" id="tipoadq_reg" name="tipoadq_reg">
                                            <option value="Alquiler">Alquiler</option>
                                            <option value="Compra" selected>Compra</option>
                                        </select>
                                    </div>
                                    <div class="d-flex flex-column mb-1">
                                        <label for="inicio_reg" class="label-form">Fecha de Inicio:</label>
                                        <input type="date" class="form-control form-control-sm" id="inicio_reg"
                                            name="inicio_reg">
                                    </div>
                                    <div id="seccion_fecha_fin" class="collapse">
                                        <div class="d-flex flex-column mb-1">
                                            <label for="fin_reg" class="label-form">Fecha de Finalización:</label>
                                            <input type="date" class="form-control form-control-sm" id="fin_reg"
                                                name="fin_reg">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column mb-1">
                                        <label id="labelPago" for="pago_reg" class="label-form"></label>
                                        <input type="text" class="form-control form-control-sm" id="pago_reg"
                                            name="pago_reg" disabled>
                                    </div>
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
    <div id="seccionTotalResultados" class="mt-3">
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
