<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parqueo;

class ParqueoController extends Controller
{
    public function showIndex()
    {
        return view('Parking.home');
    }

    public function index(Request $request)
    {
        try {
            $parqueos = Parqueo::paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $parqueos
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
            $parqueo = Parqueo::create($request->all());

            $response = [
                'state' => true,
                'message' => 'Se ha creado un nuevo parqueo.',
                'data' => $parqueo
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
            $parqueo = Parqueo::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $parqueo
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
            $parqueo = Parqueo::findOrFail($id);
            $parqueo->update($request->all());

            $response = [
                'state' => true,
                'message' => 'El parqueo ha sido actualizado con éxito.',
                'data' => $parqueo
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
            Parqueo::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'El parqueo ha sido eliminado correctamente.'
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
