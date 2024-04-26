@extends('layouts.app')
@section('styles')
    <link href="{{ asset('Resources/css/menu.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script src="{{ asset('Resources/js/icons.js') }}"></script>
    <script src="{{ asset('Resources/js/menu.js') }}"></script>
    <script src="{{ asset('Resources/js/residente.js') }}"></script>
@endsection
<input id="url-index" type="hidden" name="url-index" value="{{ route('indexRsdt') }}">
<nav class="sidebar d-none d-sm-block m-2 rounded">
    <header>
        <div class="image-text custom-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="text-center">
                            <img class="logo" src="{{ asset('Resources/imgs/Logo.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text name text-center">
                            <hr class="my-2" style="opacity: 1">
                            <div style="font-size: 14px; font-weight: 600">

                            </div>
                            <div style="font-size: 14px; font-weight: 400; opacity: 0.9">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <i class="fa-solid fa-chevron-right toggle"></i>
    </header>
    <div class="menu-bar">
        <div class="menu">
            <ul class="p-0 menu-links">
                <li class="nav-link">
                    <a class="active" href="#">
                        <i class="fa-solid fa-user icon"></i>
                        <span class="text nav-text">Residentes</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="fa-solid fa-building icon"></i>
                        <span class="text nav-text">Departamentos</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="fa-solid fa-users icon"></i>
                        <span class="text nav-text">Personal de Servicio</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="fa-solid fa-paw icon"></i>
                        <span class="text nav-text">Mascotas</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="fa-solid fa-people-robbery icon"></i>
                        <span class="text nav-text">Visitas</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="fa-solid fa-clipboard icon"></i>
                        <span class="text nav-text">Planificaciones</span>
                    </a>
                </li>
                <li class="nav-link">
                    <a href="#">
                        <i class="fa-solid fa-car icon"></i>
                        <span class="text nav-text">Parking</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="bottom-content">
            {{-- <li class="mode">
                <a class="switchMenu">
                    <div class="sun-moon">
                        <i class="fa-solid fa-moon icon moon"></i>
                        <i class="fa-solid fa-sun icon sun"></i>
                    </div>
                    <span class="mode-text text">Modo noche</span>
                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </a>
            </li> --}}
            <li>
                <a class="btn-logout" href="#">
                    <i class="fa-solid fa-arrow-right-from-bracket icon"></i>
                    <span class="text nav-text">Cerrar Sesión</span>
                </a>
            </li>
        </div>
    </div>
</nav>
<main class="home">
    <div class="menu-nav d-block d-sm-none fixed-top">
        <div class="container">
            <div class="menu-navbar">
                <div class="d-flex justify-content-start align-items-center">
                    <img class="img-icon" src="{{ asset('Resources/imgs/Logo.png') }}" alt="logo">
                    <div style="height: 41px;" class="d-flex align-items-center">
                        <h6 class="text-white fw-semibold ms-2 lh-16 title-ecem-app">CONDOMINIO LOS PINOS</h6>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    {{-- <div class="switchMenu mode">
                        <div class="sun-moon icon-movil">
                            <i class="fa-solid fa-sun sun"></i>
                            <i class="fa-solid fa-moon moon"></i>
                        </div>
                    </div> --}}
                    <i class="fa-solid fa-bars icon icon-movil" data-bs-toggle="collapse" data-bs-target="#menu-nav-bar"
                        aria-expanded="false"></i>
                </div>
            </div>
            <div id="menu-nav-bar" class="collapse">
                <div class="submenu">
                    <ul class="submenu-list">
                        <li class="submenu-link">
                            <a class="active" href="#">
                                <i class="fa-solid fa-user icono me-2"></i>
                                <span class="submenu-text-link">Residentes</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a href="#">
                                <i class="fa-solid fa-building icono me-2"></i>
                                <span class="submenu-text-link">Departamentos</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a href="#">
                                <i class="fa-solid fa-users icono me-2"></i>
                                <span class="submenu-text-link">Personal de Servicio</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a href="#">
                                <i class="fa-solid fa-paw icono me-2"></i>
                                <span class="submenu-text-link">Mascotas</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a href="#">
                                <i class="fa-solid fa-people-robbery icono me-2"></i>
                                <span class="submenu-text-link">Visitas</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a href="#">
                                <i class="fa-solid fa-clipboard icono me-2"></i>
                                <span class="submenu-text-link">Planificaciones</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a href="#">
                                <i class="fa-solid fa-car icono me-2"></i>
                                <span class="submenu-text-link">Parking</span>
                            </a>
                        </li>
                        <li class="submenu-link">
                            <a class="btn-logout" href="#">
                                <i class="fa-solid fa-arrow-right-from-bracket icono me-2"></i>
                                <span class="submenu-text-link">Cerrar Sesión</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="main margin-menu-movil">
        <div class="container-fluid pt-2 pb-4">
            <div class="">
                <div id="submenu"
                    class="bg-primary py-3 px-4 text-white fw-semibold rounded mb-3 text-uppercase shadow-sm">
                    Los Pinos
                </div>
                <div class="shadow-sm my-2">
                    <div class="bg-secondary py-2 px-4 text-white fw-semibold rounded-top">
                        <i class="fa-solid fa-list me-2"></i>Lista de Residentes
                    </div>
                    <div class="bg-light py-4 px-4 rounded-bottom shadow-sm">
                        <div class="row justify-content-between">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-sm" placeholder="Buscar"
                                        name="search" id="search">
                                </div>
                            </div>
                            <div
                                class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3 d-flex justify-content-end">
                                <button class="btn btn-secondary btn-sm mb-3" type="button" id="btnAgregar"
                                    data-bs-toggle="modal" data-bs-target="#modalMain">
                                    <i class="fa-solid fa-plus me-2"></i>Nuevo Residente
                                </button>
                                <div class="modal fade" id="modalMain" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-custom">
                                            <form id="formulario">
                                                <div class="header">
                                                    <div><i class="fa-solid fa-sitemap me-2"></i><span
                                                            id="modal-titulo">Nuevo Residente</span></div>
                                                    <i class="fa-solid fa-xmark modal-close"
                                                        data-bs-dismiss="modal"></i>
                                                </div>
                                                <div class="body">
                                                    @csrf
                                                    <div class="d-flex flex-column mb-1">
                                                        <label for="ci_rsdt" class="label-form">CI:</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="ci_rsdt" name="ci_rsdt" required>
                                                    </div>
                                                    <div class="d-flex flex-column mb-1">
                                                        <label for="nombre_rsdt" class="label-form">Nombre:</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="nombre_rsdt" name="nombre_rsdt" required>
                                                    </div>
                                                    <div class="d-flex flex-column mb-1">
                                                        <label for="apellidop_rsdt" class="label-form">Apellido
                                                            Paterno:</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="apellidop_rsdt" name="apellidop_rsdt" required>
                                                    </div>
                                                    <div class="d-flex flex-column mb-1">
                                                        <label for="apellidom_rsdt" class="label-form">Apellido
                                                            Materno:</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="apellidom_rsdt" name="apellidom_rsdt">
                                                    </div>
                                                    <div class="d-flex flex-column mb-1">
                                                        <label for="fechanac_rsdt" class="label-form">Fecha de
                                                            Nacimiento:</label>
                                                        <input type="date" class="form-control form-control-sm"
                                                            id="fechanac_rsdt" name="fechanac_rsdt" required>
                                                    </div>
                                                    <div class="d-flex flex-column mb-1">
                                                        <label for="telefono_rsdt"
                                                            class="label-form">Teléfono:</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            id="telefono_rsdt" name="telefono_rsdt" required>
                                                    </div>

                                                    <!-- Opciones para el usuario -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-check-label" for="tiene_usuario"
                                                            style="line-height: 1;">¿Crear usuario?</label>
                                                        <div id="seccionCheckUnidad"
                                                            class="form-check form-switch mb-0 d-flex justify-content-end">
                                                            <input id="tiene_usuario" class="form-check-input"
                                                                type="checkbox" role="switch">
                                                        </div>
                                                    </div>

                                                    <!-- Campos adicionales si se crea un usuario -->
                                                    <div id="campos_usuario">
                                                        <div class="d-flex flex-column mb-1">
                                                            <label for="email" class="label-form">Correo
                                                                Electrónico:</label>
                                                            <input type="email" class="form-control form-control-sm"
                                                                id="email" name="email">
                                                        </div>
                                                        <div class="d-flex flex-column mb-1">
                                                            <label for="password"
                                                                class="label-form">Contraseña:</label>
                                                            <input type="password"
                                                                class="form-control form-control-sm" id="password"
                                                                name="password">
                                                        </div>
                                                        <div class="d-flex flex-column mb-1">
                                                            <label for="rol" class="label-form">Rol:</label>
                                                            <select class="form-select form-select-sm mb-1" id="rol"
                                                                name="rol">
                                                                <option value="Administrador">Administrador</option>
                                                                <option value="Usuario">Usuario</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Opciones para representante de familia -->
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-1">
                                                        <label class="form-check-label" for="es_representante"
                                                            style="line-height: 1;">¿Es representante de
                                                            familia?</label>
                                                        <div id="seccionCheckRepresentante"
                                                            class="form-check form-switch mb-0 d-flex justify-content-end">
                                                            <input id="es_representante" class="form-check-input"
                                                                type="checkbox" role="switch">
                                                        </div>
                                                    </div>

                                                    <!-- Campo adicional si es representante de familia -->
                                                    <div id="campos_representante">
                                                        <div class="d-flex flex-column mb-1">
                                                            <label for="rep_fam_id_rsdt"
                                                                class="label-form">Representante Familiar:</label>
                                                            <select class="form-select form-select-sm mb-1"
                                                                id="rep_fam_id_rsdt" name="rep_fam_id_rsdt">
                                                            </select>
                                                        </div>
                                                    </div>
<<<<<<< HEAD
=======

>>>>>>> 4acdc273725e4edbd338d7efbe8b0565f617658b
                                                </div>
                                                <div class="footer">
                                                    <div id="botonesModal">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" name="agregar" id="btnCrud"
                                                            class="btn btn-sm btn-primary" disabled>Agregar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modalDetails" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-custom">
                                            <div class="header">
                                                <div><i class="fa-solid fa-folder-open me-2"></i><span>Detalles del
                                                        Documento</span></div>
                                                <i class="fa-solid fa-xmark modal-close" data-bs-dismiss="modal"></i>
                                            </div>
                                            <div class="body">
                                                <div id="seccionSeguimiento"
                                                    class="d-flex flex-column align-items-center">
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
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>CI</th>
                                            <th>NOMBRE</th>
                                            <th>FECHA DE NACIMIENTO</th>
                                            <th>TELÉFONO</th>
                                            <th>REPRESENTANTE</th>
                                            <th width="250">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody id="index-tbody">
                                    </tbody>
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
                                        <label class="input-group-text border-secondary bg-gray"
                                            for="totalResultados">N° Resultados:</label>
                                        <select class="form-select border-secondary page-select" id="totalResultados"
                                            name="totalResultados">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="15" selected>15</option>
                                            <option value="20">20</option>
                                        </select>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div id="loader-background" class="d-none">
    <div id="loader"></div>
    <div id="text-loader">Cargando<span id="loader-carga"></span></div>
</div>
