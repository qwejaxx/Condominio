@extends('layouts.app')
@section('content')
    <div>
        <form id="loginForm" method="POST" data-url="{{ route('login') }}">
            @csrf
            <input id="email" type="email" name="email">
            <input id="password" type="password" name="password">
            <input type="checkbox" name="remember" id="remember">
            <button id="loginBtn" type="submit">
                Iniciar Sesión
            </button>
            <div id="login-error"></div>
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('¿Olvidaste tu contraseña?') }}
            </a>
        </form>
    </div>
@endsection
