<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\Planificacion;
use App\Models\Asignacion_plan;
use Illuminate\Support\Facades\DB;

class AsignacionPlanController extends Controller
{
    public function getParticipantes($id, Request $request)
    {
        try {
            $search = '%' . $request->search . '%';
            $planificacion = Planificacion::findOrFail($id);
            $asignaciones = $planificacion->asignaciones;

            $residentes_participantes = $asignaciones->pluck('participante_id_asip')->toArray();
            $residentes_participantes = Residente::with('usuario.roles')
                ->where(function ($query) use ($search) {
                    $query->where('nombre_rsdt', 'like', $search)
                        ->orWhere('apellidop_rsdt', 'like', $search);
                })
                ->whereIn('id_rsdt', $residentes_participantes)
                ->get();

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residentes_participantes,
                'motivo' => $planificacion ? $planificacion->motivo_plan : null
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getNoParticipantes: ' . $e->getMessage(),
                'data' => [],
                'motivo' => null
            ];
        }
        return response()->json($response);
    }

    public function getNoParticipantes($id)
    {

        try {
            $planificacion = Planificacion::findOrFail($id);
            $asignaciones = $planificacion->asignaciones;
            $residentes_participantes = $asignaciones->pluck('participante_id_asip')->toArray();
            $residentes_no_participantes = Residente::with('usuario.roles')->whereNotIn('id_rsdt', $residentes_participantes)
                ->whereHas('usuario')
                ->get();

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residentes_no_participantes,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getNoParticipantes: ' . $e->getMessage(),
                'data' => [],
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function storeParticipantes(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->idPlanificacion;
            $planificacion = Planificacion::findOrFail($id);
            $pagoTotal = $planificacion->pago_plan;
            $asignaciones = $planificacion->asignaciones;
            $idsAsignacionesViejas = $asignaciones->pluck('participante_id_asip')->toArray();
            $idsNuevosParticipantes = $request->idsParticipantes;
            $idsParticipantes = array_merge($idsAsignacionesViejas, $idsNuevosParticipantes);

            foreach ($asignaciones as $asignacion) {
                $asignacion->delete();
            }

            $cuota = count($idsParticipantes) > 0 ? $pagoTotal / count($idsParticipantes) : 0;

            $nuevasAsignaciones = [];
            foreach ($idsParticipantes as $idParticipante) {
                $nuevasAsignaciones[] = [
                    'planificacion_id_asip' => $id,
                    'participante_id_asip' => $idParticipante,
                    'cuota_asip' => $cuota,
                    'pagado_asip' => 0
                ];
            }

            Asignacion_plan::insert($nuevasAsignaciones);

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'Se han agregado nuevos participantes a la actividad.',
                'data' => $nuevasAsignaciones
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $response = [
                'state' => false,
                'message' => 'Error en storeParticipantes: ' . $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($response);
    }


    public function updateAsignaciones(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->idPlanificacion;
            $planificacion = Planificacion::findOrFail($id);
            $pagoTotal = $planificacion->pago_plan;
            $asignaciones = $planificacion->asignaciones;
            $idsAsignacionesViejas = $asignaciones->pluck('participante_id_asip')->toArray();
            $idsParticipantesToDelete = $request->idsEliminados;
            $idsParticipantes = array_diff($idsAsignacionesViejas, $idsParticipantesToDelete);

            foreach ($asignaciones as $asignacion) {
                $asignacion->delete();
            }

            $cuota = count($idsParticipantes) > 0 ? $pagoTotal / count($idsParticipantes) : 0;

            $asignacionesActualizadas = [];
            foreach ($idsParticipantes as $idParticipante) {
                $asignacionesActualizadas[] = [
                    'planificacion_id_asip' => $id,
                    'participante_id_asip' => $idParticipante,
                    'cuota_asip' => $cuota,
                    'pagado_asip' => 0
                ];
            }

            Asignacion_plan::insert($asignacionesActualizadas);

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'Se han guardado los cambios.',
                'data' => $asignacionesActualizadas
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $response = [
                'state' => false,
                'message' => 'Error en updateAsignaciones: ' . $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($response);
    }
}
