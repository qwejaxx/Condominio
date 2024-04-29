<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Residente;
use App\Models\Parqueo;

class DepartamentoController extends Controller
{
    public function showIndex()
    {
        return view('Departamentos.home');
    }

    public function getRepresentantes()
    {
        try {
            $representantes = Residente::whereNull('rep_fam_id_rsdt')
                ->whereHas('usuario', function ($query) {
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->whereIn('name', ['Administrador', 'Residente']);
                    });
                })->get();

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $representantes,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getRepresentantes: ' . $e->getMessage(),
                'data' => [],
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function getParqueos()
    {
        try {
            $parqueos = Parqueo::all();

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $parqueos,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getParqueos: ' . $e->getMessage(),
                'data' => [],
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function index(Request $request)
    {
        try {
            $search = '%' . $request->search . '%';

            $departamentos = Departamento::with('adquisicion')->with('residente')->with('parqueo')
                ->where('id_dpto', 'LIKE', $search)
                ->orWhere('codigo_dpto', 'LIKE', $search)
                ->paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $departamentos
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en index: ' . $e->getMessage(),
                'data' => []
            ];
        }
        return response()->json($response);
    }


    public function store(Request $request)
    {
        try {
            $departamento = Departamento::create($request->all());

            $response = [
                'state' => true,
                'message' => 'Se ha creado un nuevo departamento.',
                'data' => $departamento
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en store: ' . $e->getMessage(),
                'data' => null
            ];
        }

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $departamento = Departamento::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $departamento
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en show: ' . $e->getMessage(),
                'data' => null
            ];
        }

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        try {
            $departamento = Departamento::findOrFail($id);
            $departamento->update($request->all());

            $response = [
                'state' => true,
                'message' => 'El departamento ha sido actualizado con éxito.',
                'data' => $departamento
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en update: ' . $e->getMessage(),
                'data' => null
            ];
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            Departamento::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'El departamento ha sido eliminado correctamente.'
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en destroy: ' . $e->getMessage()
            ];
        }

        return response()->json($response);
    }
}
