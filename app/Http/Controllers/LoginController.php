<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check())
        {
            return redirect()->intended('/');
        }

        return view('auth.login');
    }

    public function showHome()
    {
        return view('home');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $remember = ($request->has('remember') ? true : false);

        if (Auth::attempt($credentials, $remember))
        {
            $user = Auth::user();

            if ($user->estado == 1)
            {
                $request->session()->regenerate();
                $url = redirect()->intended('/')->getTargetUrl();
                $response = [
                    'state' => true,
                    'message' => 'Inicio de Sesión Exitoso',
                    'redirect' => $url,
                ];
                return response()->json($response);
            }
            else
            {
                Auth::logout();
                $url = redirect('login')->getTargetUrl();
                $response = [
                    'state' => false,
                    'message' => 'La cuenta se encuentra inactiva.',
                    'redirect' => $url,
                ];
                return response()->json($response);
            }
        }
        else
        {
            $url = redirect('login')->getTargetUrl();
            $response = [
                'state' => false,
                'message' => 'Credenciales incorrectas.',
                'redirect' => $url,
            ];
            return response()->json($response);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
