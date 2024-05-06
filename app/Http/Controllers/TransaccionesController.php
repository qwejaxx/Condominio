<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaccionesController extends Controller
{
    public function showIndex()
    {
        return view('RegistroTransacciones.home');
    }

    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        try {

            $transacciones = Transaccion::with(['planificacion', 'residente'])
                ->where(function ($query) use ($search) {
                    $query->whereHas('planificacion', function ($query) use ($search) {
                        $query->where("motivo_plan", 'LIKE', $search)
                            ->orWhere('inicio_plan', 'LIKE', $search);
                    })
                        ->orWhereHas('residente', function ($query) use ($search) {
                            $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt) LIKE ?", [$search])
                                ->orWhere('ci_rsdt', 'LIKE', $search);
                        })
                        ->orWhere('id_tr', 'LIKE', $search)
                        ->orWhere('tipo_tr', 'LIKE', $search)
                        ->orWhere('fecha_tr', 'LIKE', $search);
                })
                ->paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $transacciones
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
            $transaccion = Transaccion::create([
                'plan_id_tr' => $request->plan_id_tr,
                'residente_id_tr' => $request->residente_id_tr,
                'tipo_tr' => $request->tipo_tr,
                'monto_tr' => $request->monto_tr,
                'fecha_tr' => Carbon::now(),
            ]);

            $response = [
                'state' => true,
                'message' => 'Se ha registrado una nueva transacción.',
                'data' => $transaccion
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
            $transaccion = Transaccion::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $transaccion
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
            $transaccion = Transaccion::findOrFail($id);

            $transaccion->plan_id_tr = $request->plan_id_tr;
            $transaccion->residente_id_tr = $request->residente_id_tr;
            $transaccion->tipo_tr = $request->tipo_tr;
            $transaccion->monto_tr = $request->monto_tr;
            $transaccion->fecha_tr = Carbon::now();

            $transaccion->save();

            $response = [
                'state' => true,
                'message' => 'La transacción ha sido actualizada con éxito.',
                'data' => $transaccion
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
            Transaccion::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'La transacción ha sido eliminada correctamente.'
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
