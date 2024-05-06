<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Residente;
use App\Models\Parqueo;
use DateTime;

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
            $parqueosRegistrados = Departamento::whereNotNull('parqueo_id_dpto')->pluck('parqueo_id_dpto')->toArray();

            $parqueos = Parqueo::whereNotIn('id_park', $parqueosRegistrados)->get();

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

            $departamentos = Departamento::with(['parqueo', 'adquisiciones' => function ($query) {
                $query->select('id_reg', 'departamento_id_reg', 'residente_id_reg', 'tipoadq_reg', 'inicio_reg', 'fin_reg', 'pago_reg')
                    ->with('residente')
                    ->orderByDesc('id_reg')
                    ->limit(1);
            }])
                ->where('id_dpto', 'LIKE', $search)
                ->orWhere('codigo_dpto', 'LIKE', $search)
                ->paginate($request->totalResultados);

            $departamentos->getCollection()->transform(function ($departamento) {
                $estado = 'DISPONIBLE';

                if ($departamento->adquisiciones->isNotEmpty()) {
                    $adquisicion = $departamento->adquisiciones->first();
                    if ($adquisicion->tipoadq_reg == 'Compra') {
                        $estado = 'COMPRADO';
                    } else {
                        $fechaFin = new DateTime($adquisicion->fin_reg);
                        $fechaHoy = new DateTime();

                        if ($fechaHoy > $fechaFin) {
                            $estado = 'DISPONIBLE';
                        } else {
                            $estado = 'ALQUILADO';
                        }
                    }
                }

                $departamento->estado_dpto = $estado;

                return $departamento;
            });

            $paginatedResults = $departamentos;

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $paginatedResults
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
            if ($request->parqueo_id_dpto == 0) {
                $request->merge(['parqueo_id_dpto' => null]);
            }

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
            $departamento = Departamento::with(['parqueo', 'adquisiciones' => function ($query) {
                $query->select('id_reg', 'departamento_id_reg', 'residente_id_reg', 'tipoadq_reg', 'inicio_reg', 'fin_reg', 'pago_reg')
                    ->with('residente')
                    ->orderByDesc('id_reg')
                    ->limit(1);
            }])->findOrFail($id);

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
            if ($request->parqueo_id_dpto == 0) {
                $request->merge(['parqueo_id_dpto' => null]);
            }

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
