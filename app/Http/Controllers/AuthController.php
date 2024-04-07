<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Метод для перехода к странице регистрации
    public function registration() {
        return view('auth.register');
    }
    
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
        $user->createToken('myAppToken')->plainTextToken;
        
        // Автоматический переход по адресу
        return redirect()->route('login');
    }

    // Метод для перехода к странице авторизации
    public function login() {
        return view('auth.login');
    }

    public function authenticate(Request $request) {
        // Проводим валидацию данных
        $credentials = $request->validate([
            'email'     =>  'required|email',
            'password'  =>  'required|min:6',
        ]);

        // Попытка аутентификации
        if (Auth::attempt($credentials)) {
            // Создание идентификатора сессии и удаление всех данных из нее
            $request->session()->regenerate();

            // Автоматический переход на главную страцницу
            return redirect()->intended('/');
        }
        
        // Если аутентификация не прошла, то возвращаем ошибку на страницу авторизации
        return back()->withErrors('Неправильный email или пароль');
    }

    // Метод выхода пользователя из системы
    public function logOut(Request $request) {
        Auth::logout();
        
        // Немедленное удаление всех данных из текущей сессии
        $request->session()->invalidate();

        // Генерация нового CSRF-токена
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}