@extends('layouts.menu')
@section('stylesExtra')
    <link href="{{ asset('Resources/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Resources/css/select2-bootstrap-5-theme.css') }}" rel="stylesheet">
@endsection
@section('scriptsExtra')
    <script src="{{ asset('Resources/js/transacciones.js') }}"></script>
    <script src="{{ asset('Resources/js/select2.min.js') }}"></script>
@endsection
@section('controllerLinks')
    <input id="url-index" type="hidden" name="url-index" value="{{ route('indexTr') }}">
    <input id="url-store" type="hidden" name="url-store" value="{{ route('storeTr') }}">
    <input id="url-show" type="hidden" name="url-show" value="{{ route('Transacciones') }}">
    <input id="url-get-rep" type="hidden" name="url-get-rep" value="{{ route('indexRepMas') }}">
    <input id="url-get-plan" type="hidden" name="url-get-plan" value="{{ route('indexPlan') }}">
@endsection
@section('Titulo')
    <i class="fa-solid fa-list me-2"></i>Registro de transacciones
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
                <i class="fa-solid fa-plus me-2"></i>Nueva transacción
            </button>
            <div class="modal fade" id="modalMain" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-custom">
                        <input id="id_tr" type="hidden" name="id_tr">
                        <form id="rsdtForm">
                            <div class="header">
                                <div><i class="fa-solid fa-sitemap me-2"></i><span id="modal-titulo">Registrar transacción</span>
                                </div>
                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                            </div>
                            <div class="body">
                                <div class="text-center" id="modal-mensaje"></div>
                                @csrf
                                <div class="d-flex flex-column mb-1">
                                    <label for="plan_id_tr" class="label-form">Actividad:</label>
                                    <select class="form-select form-select-sm" id="plan_id_tr"
                                        name="plan_id_tr">
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="residente_id_tr" class="label-form">Residente:</label>
                                    <select class="form-select form-select-sm" id="residente_id_tr" name="residente_id_tr">
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="tipo_tr" class="label-form">Tipo:</label>
                                    <select class="form-select form-select-sm" id="tipo_tr" name="tipo_tr">
                                        <option value="Embolso">Embolso</option>
                                        <option value="Desembolso">Desembolso</option>
                                    </select>
                                </div>
                                <div class="d-flex flex-column mb-1">
                                    <label for="monto_tr" class="label-form">Monto Bs.:</label>
                                    <input type="text" class="form-control form-control-sm" id="monto_tr"
                                        name="monto_tr">
                                </div>
                                <div class="d-flex flex-column mb-1 d-none" id="seccionFecha">
                                    <label for="fechaTransaccion" class="label-form">Fecha:</label>
                                    <input type="text" class="form-control form-control-sm" id="fechaTransaccion"
                                        name="fechaTransaccion">
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
@endsection
