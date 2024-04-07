<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Метод для создания нового пользователя в БД
    public function create_user(Request $request) {
        $request->validate([
            'name'              =>  'required',
            'email'             =>  'required|email',
            'password'          =>  'required|min:6',
            'password_repeat'   =>  'required|same:password'
        ]);

        $user = User::create([
            'name'      =>  $request->name,
            'email'     =>  $request->email,
            'password'  =>  Hash::make($request->password),
            'role'      =>  'reader', 
        ]);

        // Создание нового токена
        $token = $user->createToken('myAppToken')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function authenticate(Request $request) {
        // Проводим валидацию данных
        $credentials = $request->validate([
            'email'     =>  'required|email',
            'password'  =>  'required|min:6',
        ]);

        // Попытка аутентификации
        if (Auth::attempt($credentials)) {
            $token = auth()->user()->createToken('myAppToken');
            return response($token);
        }
        
        // Если аутентификация не прошла, то возвращаем ошибку на страницу авторизации
        return response(['email' => 'Вы указали неверные данные'], 401);
    }

    // Метод выхода пользователя из системы
    public function logOut(Request $request) {
        auth()->user()->tokens()->delete();
        return response('logout');
    }
}