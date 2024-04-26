<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class ResidenteController extends Controller
{
    public function showIndex()
    {
        return view('Residente.home');
    }

    public function index(Request $request)
    {
        $search = '%'.$request->search.'%';
        try
        {
            $residentes = DB::table('residente as r')
            ->select('r.id_rsdt', 'r.ci_rsdt', 'r.nombre_rsdt', 'r.apellidop_rsdt', 'r.apellidom_rsdt', 'r.fechanac_rsdt', 'r.telefono_rsdt',
                'rep.nombre_rsdt as nombre_representante', 'rep.apellidop_rsdt as apellido_representante', 'estado')
            ->leftJoin('residente as rep', 'r.rep_fam_id_rsdt', '=', 'rep.id_rsdt')
            ->leftJoin('users as u', 'r.usuario_id_rsdt', '=', 'id')
            ->whereRaw("CONCAT(r.nombre_rsdt, ' ', r.apellidop_rsdt, ' ', r.apellidom_rsdt) LIKE ?", [$search])
            ->paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residentes
            ];
        }
        catch (\Exception $e)
        {
            $response = [
                'state' => false,
                'message' => 'Error en index: ' . $e->getMessage(),
                'data' => $residentes
            ];
        }
        finally
        {
            return response()->json($response);
        }
    }

    public function store(Request $request)
    {
        try
        {
            if ($request->has('tiene_usuario'))
            {
                $usuario = User::create([
                    'name' => $request->nombre_rsdt,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'estado' => 1
                ])->assignRole($request->rol);

                $usuario_id = $usuario->id;
            }
            else
            {
                $usuario_id = null;
            }

            $rep_fam_id_rsdt = $request->has('es_representante') ? null : $request->rep_fam_id_rsdt;

            Residente::create([
                'ci_rsdt' => $request->ci_rsdt,
                'nombre_rsdt' => $request->nombre_rsdt,
                'apellidop_rsdt' => $request->apellidop_rsdt,
                'apellidom_rsdt' => $request->apellidom_rsdt,
                'fechanac_rsdt' => $request->fechanac_rsdt,
                'telefono_rsdt' => $request->telefono_rsdt,
                'usuario_id_rsdt' => $usuario_id,
                'rep_fam_id_rsdt' => $rep_fam_id_rsdt,
            ]);

            $response = [
                'state' => true,
                'message' => 'La operación se realizó con éxito.'
            ];
        }
        catch (\Exception $e)
        {
            $response = [
                'state' => false,
                'message' => 'Error en store: ' . $e->getMessage()
            ];
        }
        finally
        {
            return response()->json($response);
        }
    }

    public function show($id)
    {
        $residente = Residente::findOrFail($id);
        return view('residentes.show', compact('residente'));
    }

    public function update(Request $request, $id)
    {
        $residente = Residente::findOrFail($id);
        $residente->update($request->all());

        return redirect()->route('residentes.index')->with('success', 'Residente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $residente = Residente::findOrFail($id);
        $residente->delete();

        return redirect()->route('residentes.index')->with('success', 'Residente eliminado exitosamente.');
    }
}
