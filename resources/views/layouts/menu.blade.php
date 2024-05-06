@extends('layouts.app')
@section('styles')
    <link href="{{ asset('Resources/css/menu.css') }}" rel="stylesheet">
    @yield('stylesExtra')
@endsection
@section('scripts')
    <script src="{{ asset('Resources/js/icons.js') }}"></script>
    <script src="{{ asset('Resources/js/menu.js') }}"></script>
    <script src="{{ asset('Resources/js/logout.js') }}"></script>
    @yield('scriptsExtra')
@endsection
<input id="url-logout" type="hidden" value="{{ route('logout') }}">
@yield('controllerLinks')
@section('content')
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
                    {{-- Acceso para Administradores --}}
                    @role('Administrador')
                        <li class="nav-link">
                            <a class="{{ request()->is('Residentes*') ? 'active' : '' }}" href="{{ route('Residentes') }}">
                                <i class="fa-solid fa-user icon"></i>
                                <span class="text nav-text">Residentes</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Departamentos*') ? 'active' : '' }}"
                                href="{{ route('Departamentos') }}">
                                <i class="fa-solid fa-building icon"></i>
                                <span class="text nav-text">Departamentos</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Parking*') ? 'active' : '' }}" href="{{ route('Parking') }}">
                                <i class="fa-solid fa-car icon"></i>
                                <span class="text nav-text">Parking</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Adquisiciones*') ? 'active' : '' }}"
                                href="{{ route('Adquisiciones') }}">
                                <i class="fa-solid fa-building-user icon"></i>
                                <span class="text nav-text">Adquisiciones</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Mascotas*') ? 'active' : '' }}" href="{{ route('Mascotas') }}">
                                <i class="fa-solid fa-paw icon"></i>
                                <span class="text nav-text">Mascotas</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Personal*') ? 'active' : '' }}" href="{{ route('Personal') }}">
                                <i class="fa-solid fa-users icon"></i>
                                <span class="text nav-text">Personal de Servicio</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Visitas*') ? 'active' : '' }}" href="{{ route('Visitas') }}">
                                <i class="fa-solid fa-people-robbery icon"></i>
                                <span class="text nav-text">Visitantes</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('ControlVisitas*') ? 'active' : '' }}"
                                href="{{ route('ControlVisitas') }}">
                                <i class="fa-solid fa-address-card icon"></i>
                                <span class="text nav-text">Registro de Visitas</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Planificaciones*') ? 'active' : '' }}"
                                href="{{ route('Planificaciones') }}">
                                <i class="fa-solid fa-calendar-days icon"></i>
                                <span class="text nav-text">Actividades</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Asignaciones*') ? 'active' : '' }}"
                                href="{{ route('Asignaciones') }}">
                                <i class="fa-solid fa-clipboard-list icon"></i>
                                <span class="text nav-text">Asignación de Actividades</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('Transacciones*') ? 'active' : '' }}"
                                href="{{ route('Transacciones') }}">
                                <i class="fa-solid fa-money-bill icon"></i>
                                <span class="text nav-text">Transacciones</span>
                            </a>
                        </li>
                    @endrole
                    {{-- Acceso para el personal de seguridad --}}
                    @role('Personal de Seguridad')
                        <li class="nav-link">
                            <a class="{{ request()->is('Visitas*') ? 'active' : '' }}" href="{{ route('Visitas') }}">
                                <i class="fa-solid fa-people-robbery icon"></i>
                                <span class="text nav-text">Visitantes</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a class="{{ request()->is('ControlVisitas*') ? 'active' : '' }}"
                                href="{{ route('ControlVisitas') }}">
                                <i class="fa-solid fa-address-card icon"></i>
                                <span class="text nav-text">Registro de Visitas</span>
                            </a>
                        </li>
                    @endrole
                    {{-- Acceso para residentes y otros del personal de servicio --}}
                    @unless(auth()->user()->hasAnyRole(['Administrador', 'Personal de Seguridad']))
                    <li class="nav-link">
                        <a class="{{ request()->is('Actividades*') ? 'active' : '' }}"
                            href="{{ route('ResActividades') }}">
                            <i class="fa-solid fa-calendar-days icon"></i>
                            <span class="text nav-text">Actividades</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a class="{{ request()->is('ResidentesHome*') ? 'active' : '' }}"
                            href="{{ route('ResHome') }}">
                            <i class="fa-solid fa-money-bill icon"></i>
                            <span class="text nav-text">Transacciones</span>
                        </a>
                    </li>
                    @endunless
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
                    <a id="logoutBtn" class="btn-logout" href="#">
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
                            <h6 class="text-white fw-semibold ms-2 lh-16 title-ecem-app">CONDOMINIO "LOS PINOS"</h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        {{-- <div class="switchMenu mode">
                        <div class="sun-moon icon-movil">
                            <i class="fa-solid fa-sun sun"></i>
                            <i class="fa-solid fa-moon moon"></i>
                        </div>
                    </div> --}}
                        <i class="fa-solid fa-bars icon icon-movil" data-bs-toggle="collapse"
                            data-bs-target="#menu-nav-bar" aria-expanded="false"></i>
                    </div>
                </div>
                <div id="menu-nav-bar" class="collapse">
                    <div class="submenu">
                        <ul class="submenu-list">
                            @role('Administrador')
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Residentes*') ? 'active' : '' }}"
                                        href="{{ route('Residentes') }}">
                                        <i class="fa-solid fa-user icono me-2"></i>
                                        <span class="submenu-text-link">Residentes</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Departamentos*') ? 'active' : '' }}"
                                        href="{{ route('Departamentos') }}">
                                        <i class="fa-solid fa-building icono me-2"></i>
                                        <span class="submenu-text-link">Departamentos</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Parking*') ? 'active' : '' }}"
                                        href="{{ route('Parking') }}">
                                        <i class="fa-solid fa-car icono me-2"></i>
                                        <span class="submenu-text-link">Parking</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Adquisiciones*') ? 'active' : '' }}"
                                        href="{{ route('Adquisiciones') }}">
                                        <i class="fa-solid fa-building-user icono me-2"></i>
                                        <span class="submenu-text-link">Adquisiciones</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Mascotas*') ? 'active' : '' }}"
                                        href="{{ route('Mascotas') }}">
                                        <i class="fa-solid fa-paw icono me-2"></i>
                                        <span class="submenu-text-link">Mascotas</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Personal*') ? 'active' : '' }}"
                                        href="{{ route('Personal') }}">
                                        <i class="fa-solid fa-users icono me-2"></i>
                                        <span class="submenu-text-link">Personal de Servicio</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Visitas*') ? 'active' : '' }}"
                                        href="{{ route('Visitas') }}">
                                        <i class="fa-solid fa-people-robbery icono me-2"></i>
                                        <span class="submenu-text-link">Visitantes</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('ControlVisitas*') ? 'active' : '' }}"
                                        href="{{ route('ControlVisitas') }}">
                                        <i class="fa-solid fa-address-card icono me-2"></i>
                                        <span class="submenu-text-link">Registro de Visitas</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Planificaciones*') ? 'active' : '' }}"
                                        href="{{ route('Planificaciones') }}">
                                        <i class="fa-solid fa-calendar-days icono me-2"></i>
                                        <span class="submenu-text-link">Actividades</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Asignaciones*') ? 'active' : '' }}"
                                        href="{{ route('Asignaciones') }}">
                                        <i class="fa-solid fa-clipboard-list icono me-2"></i>
                                        <span class="submenu-text-link">Asignación de Actividades</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Transacciones*') ? 'active' : '' }}"
                                        href="{{ route('Transacciones') }}">
                                        <i class="fa-solid fa-money-bill icono me-2"></i>
                                        <span class="submenu-text-link">Transacciones</span>
                                    </a>
                                </li>
                            @endrole
                            @role('Personal de Seguridad')
                                <li class="submenu-link">
                                    <a class="{{ request()->is('Visitas*') ? 'active' : '' }}"
                                        href="{{ route('Visitas') }}">
                                        <i class="fa-solid fa-people-robbery icono me-2"></i>
                                        <span class="submenu-text-link">Visitantes</span>
                                    </a>
                                </li>
                                <li class="submenu-link">
                                    <a class="{{ request()->is('ControlVisitas*') ? 'active' : '' }}"
                                        href="{{ route('ControlVisitas') }}">
                                        <i class="fa-solid fa-address-card icono me-2"></i>
                                        <span class="submenu-text-link">Registro de Visitas</span>
                                    </a>
                                </li>
                            @endrole
                            @unless(auth()->user()->hasAnyRole(['Administrador', 'Residente']))
                            <li class="submenu-link">
                                <a class="{{ request()->is('Actividades*') ? 'active' : '' }}"
                                    href="{{ route('ResActividades') }}">
                                    <i class="fa-solid fa-calendar-days icono me-2"></i>
                                    <span class="submenu-text-link">Actividades</span>
                                </a>
                            </li>
                            <li class="submenu-link">
                                <a class="{{ request()->is('ResidentesHome*') ? 'active' : '' }}"
                                    href="{{ route('ResHome') }}">
                                    <i class="fa-solid fa-money-bill icono me-2"></i>
                                    <span class="submenu-text-link">Transacciones</span>
                                </a>
                            </li>
                            @endunless
                            <li class="submenu-link">
                                <a id="logoutBtn" class="btn-logout" href="#">
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
                    <div id="submenu" class="bg-primary py-3 px-4 text-white rounded mb-3 shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="fw-semibold">CONDOMINIO "LOS PINOS"</div>
                            @auth
                                @if (Auth::user()->residente)
                                <div class="d-flex flex-column text-end" style="font-size: 12.6px;">
                                    <div>
                                        {{ 'Usuario: ' . Auth::user()->residente->nombre_rsdt . ' ' . Auth::user()->residente->apellidop_rsdt }}
                                    </div>
                                    <div>
                                        {{ 'Rol: ' . Auth::user()->getRoleNames()->first() }}
                                    </div>
                                </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <div class="shadow-sm my-2">
                        <div class="bg-secondary py-2 px-4 text-white fw-semibold rounded-top">
                            @yield('Titulo')
                        </div>
                        <div class="bg-light py-4 px-4 rounded-bottom shadow-sm">
                            @yield('Contenido')
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
@endsection
