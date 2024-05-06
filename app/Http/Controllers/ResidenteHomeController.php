<?php

namespace App\Http\Controllers;

use App\Models\Planificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidenteHomeController extends Controller
{
    public function showIndex()
    {
        return view('ResidentesHome.home');
    }

    public function showIndexActividades()
    {
        return view('ResidentesHome.Actividades');
    }


    public function index(Request $request)
    {
        $id_rsdt = $request->idResidente;
        try {
            $planificaciones = Planificacion::whereHas('asignaciones.participante', function ($query) use ($id_rsdt) {
                $query->where('id_rsdt', $id_rsdt);
            })
                ->with(['asignaciones' => function ($query) use ($id_rsdt) {
                    $query->whereHas('participante', function ($query) use ($id_rsdt) {
                        $query->where('id_rsdt', $id_rsdt);
                    });
                }])
                ->paginate($request->totalResultados);

            foreach ($planificaciones as $planificacion) {
                foreach ($planificacion->asignaciones as $asignacion) {
                    $totalPagado = DB::table('transacciones')
                        ->where('plan_id_tr', $asignacion->planificacion->id_plan)
                        ->where('residente_id_tr', $asignacion->participante->id_rsdt)
                        ->where('tipo_tr', 'Embolso')
                        ->sum('monto_tr');

                    $asignacion->totalPagado = number_format((float) $totalPagado, 2);
                    $asignacion->restante = number_format((float) $asignacion->planificacion->cuota_plan - $totalPagado, 2);
                }
            }

            $planificaciones->transform(function ($planificacion) {
                $planificacion->asignaciones->transform(function ($asignacion) {
                    $asignacion->makeHidden('planificacion');
                    return $asignacion;
                });
                return $planificacion;
            });

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
}
