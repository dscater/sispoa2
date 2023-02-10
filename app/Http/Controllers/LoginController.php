<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            // "captcha" => "required",
            "usuario" => "required",
            "password" => "required"
        ]);

        $usuario = $request->usuario;
        $password = $request->password;
        $res = Auth::attempt(['usuario' => $usuario, 'password' => $password, 'acceso' => 1]);
        if ($res) {
            return response()->JSON([
                'user' => Auth::user(),
            ], 200);
        }

        return response()->JSON([], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->JSON(['code' => 204], 204);
    }

    public function verifica_captcha(Request $request)
    {
        $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            // "secret" => '6LfLgEAkAAAAAEUx6mam_35HPbm5DUl0u4bfHKay',
            "secret" => config("app.clave_captcha_servidor"),
            "response" => $request->input("g-recaptcha-response")
        ])->object();
        return response()->JSON($response);
    }
}
