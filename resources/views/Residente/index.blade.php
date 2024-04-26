@extends('layouts.app')
@section('scripts')
<script src="{{ asset('Resources/js/residente.js') }}"></script>
@endsection
@section('content')
    <div>
        <form id="rsdtForm" data-url="{{ route('storeRsdt') }}" method="POST">
            @csrf
            <input type="text" id="ci_rsdt" name="ci_rsdt">
            <input type="text" id="nombre_rsdt" name="nombre_rsdt">
            <input type="text" id="apellidop_rsdt" name="apellidop_rsdt">
            <input type="text" id="apellidom_rsdt" name="apellidom_rsdt">
            <input type="date" id="fechanac_rsdt" name="fechanac_rsdt">
            <input type="text" id="telefono_rsdt" name="telefono_rsdt">
            <input type="checkbox" id="es_representante" name="es_representante">
            <select id="rep_fam_id_rsdt" name="rep_fam_id_rsdt">
                <option value="1">Ivan Rosales</option>
                <option value="2">Jonas Alanes</option>
            </select>
            <input type="checkbox" id="tiene_usuario" name="tiene_usuario">
            <input type="email" id="email" name="email">
            <input type="text" id="password" name="password">
            <select id="rol" name="rol">
                <option value="Administrador">Administrador</option>
                <option value="Personal de Seguridad">Personal de Seguridad</option>
                <option value="Residente">Residente</option>
            </select>
            <button type="button" id="storeBtn">Registrar</button>
        </form>
    </div>
@endsection
