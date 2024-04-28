<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Residente;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class ResidenteController extends Controller
{
    public function showIndex()
    {
        return view('Residente.home');
    }

    public function getRepresentantes()
    {
        try {
            $representantes = Residente::whereNull('rep_fam_id_rsdt')->get();

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

    public function getRoles()
    {
        try {
            $roles = Role::all();

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
            $residentes = DB::table('residente as r')
                ->select(
                    'r.id_rsdt',
                    'r.ci_rsdt',
                    'r.nombre_rsdt',
                    'r.apellidop_rsdt',
                    'r.apellidom_rsdt',
                    'r.fechanac_rsdt',
                    'r.telefono_rsdt',
                    'rep.nombre_rsdt as nombre_representante',
                    'rep.apellidop_rsdt as apellido_representante',
                    'estado'
                )
                ->leftJoin('residente as rep', 'r.rep_fam_id_rsdt', '=', 'rep.id_rsdt')
                ->leftJoin('users as u', 'r.usuario_id_rsdt', '=', 'id')
                ->whereRaw("CONCAT(r.nombre_rsdt, ' ', r.apellidop_rsdt, ' ', r.apellidom_rsdt) LIKE ?", [$search])
                ->orWhere('r.ci_rsdt', 'LIKE', '%' . $search . '%')
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
        try {
            DB::beginTransaction();

            if ($request->has('es_representante'))
            {
                $user = User::create([
                    'name' => $request->nombre_rsdt,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'estado' => 1
                ])->assignRole($request->rol);
                $rep_fam = null;
            }
            else
            {
                $user = null;
                $rep_fam = $request->rep_fam_id_rsdt;
            }

            $residente = Residente::create([
                'ci_rsdt' => $request->ci_rsdt,
                'nombre_rsdt' => $request->nombre_rsdt,
                'apellidop_rsdt' => $request->apellidop_rsdt,
                'apellidom_rsdt' => $request->apellidom_rsdt,
                'fechanac_rsdt' => $request->fechanac_rsdt,
                'telefono_rsdt' => $request->telefono_rsdt,
                'usuario_id_rsdt' => $user ? $user->id : null,
                'rep_fam_id_rsdt' => $rep_fam
            ]);

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'Se ha agregado un nuevo residente.',
                'data' => [
                    'residente' => $residente,
                    'user' => $user,
                    'rol' => $user ? $user->getRoleNames() : null
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            $response = [
                'state' => false,
                'message' => 'Error en store: ' . $e->getMessage(),
                'data' => [
                    'residente' => null,
                    'user' => null,
                    'rol' => null
                ]
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function show($id)
    {
        try {
            $residente = Residente::findOrFail($id);
            $user = $residente->usuario_id_rsdt ? User::findOrFail($residente->usuario_id_rsdt) : null;

            $response = [
                'state' => true,
                'message' => 'Consulta exitosa.',
                'data' => [
                    'residente' => $residente,
                    'user' => $user,
                    'rol' => $user ? $user->getRoleNames() : null
                ],
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en show: ' . $e->getMessage(),
                'data' => [
                    'residente' => null,
                    'user' => null,
                    'rol' => null
                ],
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

            if ($request->has('es_representante')) {
                if ($residente->usuario_id_rsdt) {
                    $user = User::findOrFail($residente->usuario_id_rsdt);
                    $user->name = $request->nombre_rsdt;
                    $user->email = $request->email;
                    $user->password = $request->password !== $user->password ? Hash::make($request->password) : $user->password;
                    $user->roles()->detach();
                    $user->assignRole($request->rol);
                    $user->save();
                } else {
                    $user = User::create([
                        'name' => $request->nombre_rsdt,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'estado' => 1
                    ]);
                    $user->assignRole($request->rol);
                    $residente->usuario_id_rsdt = $user->id;
                    $residente->rep_fam_id_rsdt = null;
                }
            } else {
                $residente->rep_fam_id_rsdt = $request->rep_fam_id_rsdt;
            }


            $residente->save();

            DB::commit();

            $response = [
                'state' => true,
                'message' => 'La operación se realizó con éxito.',
                'data' => [
                    'residente' => $residente,
                    'user' => $user,
                    'rol' => $user ? $user->getRoleNames() : null
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            $response = [
                'state' => false,
                'message' => 'Error en update: ' . $e->getMessage(),
                'data' => [
                    'residente' => null,
                    'user' => null,
                    'rol' => null
                ]
            ];
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Encuentra el residente por su ID
            $residente = Residente::findOrFail($id);

            // Si es representante, quita cualquier rol asociado antes de eliminar el usuario
            if ($residente->usuario_id_rsdt) {
                $user = User::findOrFail($residente->usuario_id_rsdt);
                $user->roles()->detach();
                $user->delete();
            }

            // Elimina el residente
            $residente->delete();

            DB::commit();

            // Prepara la respuesta exitosa
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
