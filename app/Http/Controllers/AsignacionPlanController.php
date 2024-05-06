<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\Planificacion;
use App\Models\Asignacion_plan;
use Illuminate\Support\Facades\DB;
use App\Models\Transaccion;

class AsignacionPlanController extends Controller
{
    public function showIndex()
    {
        return view('RegistroAsignacion.home');
    }

    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        try {

            $asignaciones = Asignacion_plan::with(['planificacion', 'participante'])
                ->where(function ($query) use ($search) {
                    $query->whereHas('planificacion', function ($query) use ($search) {
                        $query->where("motivo_plan", 'LIKE', $search)
                            ->orWhere('inicio_plan', 'LIKE', $search);
                    })
                        ->orWhereHas('participante', function ($query) use ($search) {
                            $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt) LIKE ?", [$search])
                                ->orWhere('ci_rsdt', 'LIKE', $search);
                        })
                        ->orWhere('id_asip', 'LIKE', $search);
                })
                ->paginate($request->totalResultados);

            foreach ($asignaciones as $asignacion) {
                $totalPagado = DB::table('transacciones')
                    ->where('plan_id_tr', $asignacion->planificacion->id_plan)
                    ->where('residente_id_tr', $asignacion->participante->id_rsdt)
                    ->where('tipo_tr', 'Embolso')
                    ->sum('monto_tr');

                $asignacion->totalPagado = number_format((float) $totalPagado, 2);
                $asignacion->restante = number_format((float) $asignacion->planificacion->cuota_plan - $totalPagado, 2);
            }

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $asignaciones
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

    public function getParticipantes($id)
    {
        try {
            $planificacion = Planificacion::findOrFail($id);
            $asignaciones = $planificacion->asignaciones;

            foreach ($asignaciones as $asignacion) {
                $residente = $asignacion->participante;

                $transacciones = Transaccion::where('plan_id_tr', $asignacion->planificacion->id_plan)
                    ->where('residente_id_tr', $residente->id_rsdt)
                    ->where('tipo_tr', 'Embolso')
                    ->get();

                if ($transacciones->count() > 0) {
                    $totalPagado = $transacciones->sum('monto_tr');
                } else {
                    $totalPagado = 0;
                }

                $residente->usuario->getRoleNames();

                $residente->totalPagado = number_format((float) $totalPagado, 2);
                $cuotaPlan = $asignacion->planificacion->cuota_plan;
                $residente->restante = number_format($cuotaPlan - $totalPagado, 2);
            }

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $asignaciones,
                'motivo' => $planificacion ? $planificacion->motivo_plan : null
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getParticipantes: ' . $e->getMessage(),
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

    public function store(Request $request)
    {
        try {
            $asignacion = Asignacion_plan::create($request->all());

            $response = [
                'state' => true,
                'message' => 'Se ha registrado una nueva asignación.',
                'data' => $asignacion
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
            $asignacion = Asignacion_plan::with('participante')->findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $asignacion
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
            $adquisicion = Asignacion_plan::findOrFail($id);

            $adquisicion->participante_id_asip = $request->participante_id_asip;
            $adquisicion->planificacion_id_asip = $request->planificacion_id_asip;

            $adquisicion->save();

            $response = [
                'state' => true,
                'message' => 'La asignación ha sido actualizada con éxito.',
                'data' => $adquisicion
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
            Asignacion_plan::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'La asignación ha sido eliminada correctamente.'
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en destroy: ' . $e->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function storeParticipantes(Request $request)
    {
        DB::beginTransaction();

        try {
            $id = $request->idPlanificacion;
            $planificacion = Planificacion::findOrFail($id);
            $asignaciones = $planificacion->asignaciones;
            $idsAsignacionesViejas = $asignaciones->pluck('participante_id_asip')->toArray();
            $idsNuevosParticipantes = $request->idsParticipantes;
            $idsParticipantes = array_merge($idsAsignacionesViejas, $idsNuevosParticipantes);

            foreach ($asignaciones as $asignacion) {
                $asignacion->delete();
            }

            $nuevasAsignaciones = [];
            foreach ($idsParticipantes as $idParticipante) {
                $nuevasAsignaciones[] = [
                    'planificacion_id_asip' => $id,
                    'participante_id_asip' => $idParticipante
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
                    'participante_id_asip' => $idParticipante
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
