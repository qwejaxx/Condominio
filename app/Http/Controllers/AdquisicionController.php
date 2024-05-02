<?php

namespace App\Http\Controllers;

use App\Models\Adquisicion;
use Illuminate\Http\Request;

class AdquisicionController extends Controller
{
    public function showIndex()
    {
        return view('Adquisiciones.home');
    }

    public function index(Request $request)
    {
        try {
            $search = '%' . $request->search . '%';

            $adquisiciones = Adquisicion::with(['departamento', 'residente'])
                ->whereHas('departamento', function ($query) use ($search) {
                    $query->where('codigo_dpto', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('residente', function ($query) use ($search) {
                    $query->where('nombre_rsdt', 'LIKE', '%' . $search . '%')
                        ->orWhere('apellidop_rsdt', 'LIKE', '%' . $search . '%')
                        ->orWhere('apellidom_rsdt', 'LIKE', '%' . $search . '%');
                })
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
}
