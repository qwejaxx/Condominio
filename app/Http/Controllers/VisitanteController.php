<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;

class VisitanteController extends Controller
{
    public function showIndex()
    {
        return view('Visitas.home');
    }

    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        try {
            $visitantes = Residente::whereNull('usuario_id_rsdt')
                ->whereNull('rep_fam_id_rsdt')
                ->where(function ($query) use ($search) {
                    $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt, ' ', apellidom_rsdt) LIKE ?", [$search])
                        ->orWhere('ci_rsdt', 'LIKE', '%' . $search . '%');
                })
                ->paginate($request->totalResultados);


            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $visitantes
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
            $residente = Residente::create([
                'ci_rsdt' => $request->ci_rsdt,
                'nombre_rsdt' => $request->nombre_rsdt,
                'apellidop_rsdt' => $request->apellidop_rsdt,
                'apellidom_rsdt' => $request->apellidom_rsdt,
                'fechanac_rsdt' => $request->fechanac_rsdt,
                'telefono_rsdt' => $request->telefono_rsdt,
                'usuario_id_rsdt' => null,
                'rep_fam_id_rsdt' => null
            ]);

            $response = [
                'state' => true,
                'message' => 'Visitante creado exitosamente.',
                'data' => $residente
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error al crear el visitante: ' . $e->getMessage(),
                'data' => null
            ];
        }
        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $residente = Residente::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residente,
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
            $residente = Residente::findOrFail($id);

            $residente->ci_rsdt = $request->ci_rsdt;
            $residente->nombre_rsdt = $request->nombre_rsdt;
            $residente->apellidop_rsdt = $request->apellidop_rsdt;
            $residente->apellidom_rsdt = $request->apellidom_rsdt;
            $residente->fechanac_rsdt = $request->fechanac_rsdt;
            $residente->telefono_rsdt = $request->telefono_rsdt;

            $residente->save();

            $response = [
                'state' => true,
                'message' => 'Visitante actualizado exitosamente.',
                'data' => $residente
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error al actualizar el visitante: ' . $e->getMessage(),
                'data' => null
            ];
        }
        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $residente = Residente::findOrFail($id);
            $residente->delete();

            $response = [
                'state' => true,
                'message' => 'El visitante se eliminó correctamente.',
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error al eliminar el visitante: ' . $e->getMessage(),
            ];
        }
        return response()->json($response);
    }
}
