<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.login');
    }

    public function showHome()
    {
        return view('Residente.home');
    }

    public function login(Request $request)
    {
        try {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            $remember = ($request->has('remember') ? true : false);

            if (Auth::attempt($credentials, $remember)) {
                $user = Auth::user();

                if ($user->estado == 1) {
                    $rol = $user->roles->first();

                    switch ($rol->name) {
                        case 'Administrador':
                            $redirectUrl = '/Residentes';
                            break;
                        case 'Personal de Seguridad':
                            $redirectUrl = '/Visitas';
                            break;
                        default:
                            $redirectUrl = '/ResidentesHome';
                            break;
                    }

                    $request->session()->regenerate();
                    $url = redirect()->intended($redirectUrl)->getTargetUrl();
                    $response = [
                        'state' => true,
                        'message' => 'Inicio de Sesión Exitoso',
                        'redirect' => $url,
                    ];
                } else {
                    Auth::logout();
                    $url = redirect('login')->getTargetUrl();
                    $response = [
                        'state' => false,
                        'message' => 'La cuenta se encuentra inactiva.',
                        'redirect' => $url,
                    ];
                }
            } else {
                $url = redirect('login')->getTargetUrl();
                $response = [
                    'state' => false,
                    'message' => 'Credenciales incorrectas.',
                    'redirect' => $url,
                ];
            }
        } catch (\Exception $e) {
            $url = redirect('login')->getTargetUrl();
            $response = [
                'state' => false,
                'message' => 'Error en login: ' . $e->getMessage(),
                'redirect' => $url,
            ];
        } finally {
            return response()->json($response);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $url = redirect('login')->getTargetUrl();

            $response = [
                'state' => true,
                'message' => 'Se ha cerrado la sesión.',
                'redirect' => $url
            ];
        } catch (\Exception $e) {
            $response = [
                'state' => false,
                'message' => 'Error en logout: ' . $e->getMessage(),
                'redirect' => null,
            ];
        } finally {
            return response()->json($response);
        }
    }
}
