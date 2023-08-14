<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function funIngresar(Request $request){
        $credenciales=$request->validate([
            "email"=>"required|email",
            "password"=>"required"
        ]);
        if(!Auth::attempt($credenciales))
        {
            return response()->json(["mensaje"=>'No Autenticado'],401);
        }
        $usuario=Auth::user();
        $token=$usuario->createToken("token personal")->plainTextToken;

        return response()->json(["access_token"=>$token,"user"=>$usuario]);
    }
    public function funRegistro(Request $request){
        $request->validate([
            "name"=>"required",
            "email"=>"required|email|unique:users",
            "password"=>"required"
        ]);
        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->save();
        return response()->json(["message"=>"usuario registrado"]);
    }
    public function funPerfil(){
        $user = Auth::user();
        return response()->json($user);
    }
    public function funSalir(){
        Auth::user()->tokens()->delete();
        return response()->json(["message"=>"Logouth"]);
    }
}
