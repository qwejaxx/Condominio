<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PersonalController extends Controller
{
    public function showIndex()
    {
        return view('Personal.home');
    }

    public function getRoles()
    {
        try {
            $roles = Role::whereNotIn('name', ['Administrador', 'Residente', 'Visitante'])->get();

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $roles,
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en getRoles: ' . $e->getMessage(),
                'data' => [],
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function index(Request $request)
    {
        $search = '%' . $request->search . '%';
        try {
            $residentes = Residente::with('usuario.roles')
                ->where(function ($query) use ($search) {
                    $query->whereRaw("CONCAT(nombre_rsdt, ' ', apellidop_rsdt, ' ', apellidom_rsdt) LIKE ?", [$search])
                        ->orWhere('ci_rsdt', 'LIKE', '%' . $search . '%');
                })
                ->whereHas('usuario.roles', function ($query) {
                    $query->whereNotIn('name', ['Administrador', 'Residente']);
                })
                ->paginate($request->totalResultados);

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => $residentes
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
        DB::beginTransaction();

        try {
            $usuario = User::create([
                'name' => $request->nombre_rsdt . ' ' . $request->apellidop_rsdt,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'estado' => 1
            ])->assignRole($request->rol);

            $residente = Residente::create([
                'ci_rsdt' => $request->ci_rsdt,
                'nombre_rsdt' => $request->nombre_rsdt,
                'apellidop_rsdt' => $request->apellidop_rsdt,
                'apellidom_rsdt' => $request->apellidom_rsdt,
                'fechanac_rsdt' => $request->fechanac_rsdt,
                'telefono_rsdt' => $request->telefono_rsdt,
                'usuario_id_rsdt' => $usuario->id,
                'rep_fam_id_rsdt' => null
            ])->load('usuario');

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'Personal creado exitosamente.',
                'data' => $residente
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            $response = [
                'state' => false,
                'message' => 'Error al crear el personal: ' . $e->getMessage(),
                'data' => null
            ];
        }

        return response()->json($response);
    }

    public function show($id)
    {
        try {
            $residente = Residente::with('usuario.roles')->findOrFail($id);

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
            DB::beginTransaction();

            $residente = Residente::findOrFail($id);

            $residente->ci_rsdt = $request->ci_rsdt;
            $residente->nombre_rsdt = $request->nombre_rsdt;
            $residente->apellidop_rsdt = $request->apellidop_rsdt;
            $residente->apellidom_rsdt = $request->apellidom_rsdt;
            $residente->fechanac_rsdt = $request->fechanac_rsdt;
            $residente->telefono_rsdt = $request->telefono_rsdt;

            $residente->save();

            $user = $residente->usuario;

            $user->name = $request->nombre_rsdt;
            $user->email = $request->email;
            $user->password = $request->password !== $user->password ? Hash::make($request->password) : $user->password;
            $user->roles()->detach();
            $user->assignRole($request->rol);

            $user->save();

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'Personal actualizado exitosamente.',
                'data' => $residente
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            $response = [
                'state' => false,
                'message' => 'Error al actualizar el personal: ' . $e->getMessage(),
                'data' => null
            ];
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $residente = Residente::findOrFail($id);
            $user = $residente->usuario;
            $user->roles()->detach();
            $user->delete();
            $residente->delete();

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'El residente se eliminó correctamente.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            // Prepara la respuesta en caso de error
            $response = [
                'state' => false,
                'message' => 'Error al eliminar el residente: ' . $e->getMessage(),
            ];
        }

        // Devuelve la respuesta como JSON
        return response()->json($response);
    }
}
