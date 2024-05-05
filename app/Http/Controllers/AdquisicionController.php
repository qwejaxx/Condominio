<?php

namespace App\Http\Controllers;

use App\Models\Adquisicion;
use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Residente;
use DateTime;

class AdquisicionController extends Controller
{
    public function showIndex()
    {
        return view('Adquisiciones.home');
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

    public function getDepartamentos()
    {
        $departamentos = Departamento::with(['adquisiciones' => function ($query) {
            $query->select('id_reg', 'departamento_id_reg', 'residente_id_reg', 'tipoadq_reg', 'inicio_reg', 'fin_reg', 'pago_reg')
                ->with('residente')
                ->orderByDesc('id_reg')
                ->limit(1);
        }])->get();

        $departamentos->transform(function ($departamento) {
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

            $departamento->makeHidden('adquisiciones');

            return $departamento;
        });

        /* $departamentos = $departamentos->filter(function ($departamento) {
            return $departamento->estado_dpto === 'DISPONIBLE';
        })->values(); */

        $response = [
            'state' => true,
            'message' => 'Consulta exitosa.',
            'data' => $departamentos
        ];

        return response()->json($response);
    }

    public function index(Request $request)
    {
        try {
            $search = '%' . $request->search . '%';

            $adquisiciones = Adquisicion::with(['departamento', 'residente'])
                ->WhereHas('departamento', function ($query) use ($search) {
                    $query->where('codigo_dpto', 'LIKE', $search);
                })
                ->orWhereHas('residente', function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt, ' ', apellidom_rsdt) LIKE ?", ['%' . $search . '%']);
                    });
                })
                ->orWhere('id_reg', 'LIKE', $search)
                ->paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $adquisiciones
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
            $data = $request->all();

            if ($request->has('tipoadq_reg') && $request->tipoadq_reg === 'Compra') {
                $data['fin_reg'] = null;
            }

            $adquisición = Adquisicion::create($data);

            $response = [
                'state' => true,
                'message' => 'Se ha registrado una nueva adquisición.',
                'data' => $adquisición
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
            $adquisicion = Adquisicion::findOrFail($id);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $adquisicion
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
            $adquisicion = Adquisicion::findOrFail($id);

            $adquisicion->residente_id_reg = $request->residente_id_reg;
            $adquisicion->tipoadq_reg = $request->tipoadq_reg;
            $adquisicion->inicio_reg = $request->inicio_reg;
            $adquisicion->fin_reg = $request->tipoadq_reg == 'Compra' ? null : $request->fin_reg;
            $adquisicion->pago_reg = $request->pago_reg;

            $adquisicion->save();

            $response = [
                'state' => true,
                'message' => 'La adquisición ha sido actualizada con éxito.',
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
            Adquisicion::findOrFail($id)->delete();

            $response = [
                'state' => true,
                'message' => 'La adquisición ha sido eliminada correctamente.'
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
