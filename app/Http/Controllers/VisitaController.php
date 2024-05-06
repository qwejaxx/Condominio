<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\Visita;
use Illuminate\Support\Carbon;

class VisitaController extends Controller
{
    public function showIndex()
    {
        return view('RegistroVisita.home');
    }

    public function getResidentes()
    {
        try {
            $residentes = Residente::whereNotNull('usuario_id_rsdt')
                ->whereNull('rep_fam_id_rsdt')
                ->whereHas('usuario', function ($query) {
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->whereIn('name', ['Administrador', 'Residente']);
                    });
                })
                ->orWhere(function ($query) {
                    $query->whereNull('usuario_id_rsdt')
                        ->whereNotNull('rep_fam_id_rsdt');
                })->get();

            $residentes->load('usuario.roles');

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residentes,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getResidentes: ' . $e->getMessage(),
                'data' => [],
                'motivo' => null
            ];
        }
        return response()->json($response);
    }

    public function getVisitantes()
    {
        try {
            $residentes = Residente::whereNull('usuario_id_rsdt')
                ->whereNull('rep_fam_id_rsdt')->get();

            $residentes->load('usuario.roles');

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residentes,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getResidentes: ' . $e->getMessage(),
                'data' => [],
                'motivo' => null
            ];
        }
        return response()->json($response);
    }

    /* public function storeVisitas(Request $request)
    {
        try {
            $visita = Visita::create([
                'visitante_id_vis' => $request->visitante_id_vis,
                'visitado_id_vis' => $request->visitado_id_vis,
                'fecha_vis' => Carbon::now(), // Utiliza Carbon::now() para obtener la hora actual
            ]);

            $response = [
                'state' => true,
                'message' => 'Se ha registrado una nueva visita.',
                'data' => $visita,
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
    } */

    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        try {
            $visitas = Visita::with(['visitante', 'visitado'])
                ->where(function ($query) use ($search) {
                    $query->whereHas('visitante', function ($query) use ($search) {
                        $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt) LIKE ?", [$search])
                            ->orWhere('ci_rsdt', 'LIKE', $search);
                    })
                        ->orWhereHas('visitado', function ($query) use ($search) {
                            $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt) LIKE ?", [$search])
                                ->orWhere('ci_rsdt', 'LIKE', $search);
                        })
                        ->orWhere('fecha_vis', 'LIKE', $search)
                        ->orWhere('id_vis', 'LIKE', $search);
                })
                ->paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $visitas
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
            $visita = Visita::create([
                'visitante_id_vis' => $request->visitante_id_vis,
                'visitado_id_vis' => $request->visitado_id_vis,
                'fecha_vis' => Carbon::now()
            ]);

            $response = [
                'state' => true,
                'message' => 'Se ha registrado una nueva visita.',
                'data' => $visita
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en store: ' . $e->getMessage(),
                'data' => [
                    'visita' => null
                ]
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function show($id)
    {
        try {
            $visita = Visita::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $visita
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en show: ' . $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        try {
            $visita = Visita::findOrFail($id);

            $visita->visitante_id_vis = $request->visitante_id_vis;
            $visita->visitado_id_vis = $request->visitado_id_vis;
            $visita->fecha_vis = Carbon::now();

            $visita->save();

            $response = [
                'state' => true,
                'message' => 'La visita ha sido actualizada con éxito.',
                'data' => $visita
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
            Visita::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'La visita ha sido eliminada correctamente.'
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
