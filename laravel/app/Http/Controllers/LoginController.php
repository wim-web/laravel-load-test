<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request, AuthManager $manager)
    {
        $input = $request->only(['name', 'password']);
        
        $user = User::query()->where($input)->firstOrFail();
        
        $manager->login($user);
        
        return $user;
    }
    
    public function logout(AuthManager $manager)
    {
        $manager->logout();
    }
}
