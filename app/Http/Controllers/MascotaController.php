<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mascota;
use App\Models\Residente;

class MascotaController extends Controller
{
    public function showIndex()
    {
        return view('Mascotas.home');
    }

    public function getRepresentantes()
    {
        try {
            $representantes = Residente::whereNull('rep_fam_id_rsdt')->get();

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $representantes,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getMascotas: ' . $e->getMessage(),
                'data' => [],
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        try {
            $mascotas = Mascota::select('mascota.*', 'residente.nombre_rsdt', 'residente.apellidop_rsdt')
                ->join('residente', 'mascota.propietario_id_mas', '=', 'residente.id_rsdt')
                ->where('mascota.nombre_mas', 'LIKE', $search)
                ->orWhere('mascota.tipo_mas', 'LIKE', $search)
                ->orWhere('mascota.id_mas', 'LIKE', $search)
                ->paginate($request->totalResultados);
            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $mascotas
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
            $mascota = Mascota::create([
                'nombre_mas' => $request->nombre_mas,
                'tipo_mas' => $request->tipo_mas,
                'propietario_id_mas' => $request->propietario_id_mas,
            ]);

            $response = [
                'state' => true,
                'message' => 'Se ha agregado una nueva mascota.',
                'data' => $mascota,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en store: ' . $e->getMessage(),
                'data' => null,
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function show($id)
    {
        try {
            $mascota = Mascota::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $mascota,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en show: ' . $e->getMessage(),
                'data' => null,
            ];
        }

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        try {
            $mascota = Mascota::findOrFail($id);

            $mascota->nombre_mas = $request->nombre_mas;
            $mascota->tipo_mas = $request->tipo_mas;
            $mascota->propietario_id_mas = $request->propietario_id_mas;

            $mascota->save();

            $response = [
                'state' => true,
                'message' => 'La operación se realizó con éxito.',
                'data' => $mascota,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en update: ' . $e->getMessage(),
                'data' => null,
            ];
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            // Encuentra la mascota por su ID
            $mascota = Mascota::findOrFail($id);

            // Elimina la mascota
            $mascota->delete();

            // Prepara la respuesta exitosa
            $response = [
                'state' => true,
                'message' => 'La mascota se eliminó correctamente.',
            ];
        } catch (\Exception $e) {
            // Prepara la respuesta en caso de error
            $response = [
                'state' => false,
                'message' => 'Error al eliminar la mascota: ' . $e->getMessage(),
            ];
        }

        // Devuelve la respuesta como JSON
        return response()->json($response);
    }
}
