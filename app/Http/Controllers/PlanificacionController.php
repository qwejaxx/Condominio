<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planificacion;

class PlanificacionController extends Controller
{
    public function showIndex()
    {
        return view('Planificaciones.home');
    }
    public function index(Request $request)
    {
        try {
            $planificaciones = Planificacion::paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $planificaciones
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en index: ' . $e->getMessage(),
                'data' => []
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function store(Request $request)
    {
        try {
            $planificacion = Planificacion::create($request->all());

            $response = [
                'state' => true,
                'message' => 'Se ha creado una nueva planificación.',
                'data' => $planificacion
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
            $planificacion = Planificacion::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $planificacion
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
            $planificacion = Planificacion::findOrFail($id);
            $planificacion->update($request->all());

            $response = [
                'state' => true,
                'message' => 'La planificación ha sido actualizada con éxito.',
                'data' => $planificacion
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
            Planificacion::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'La planificación ha sido eliminada correctamente.'
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
