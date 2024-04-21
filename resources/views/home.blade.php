@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @role('Administrador')
                    <p>Hola {{ Auth::user()->roles->first()->name }}, bienvenido</p>
                    @endrole
                    @role('Residente')
                    <p>Hola {{ Auth::user()->roles->first()->name }}, bienvenido</p>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
