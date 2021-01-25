<?php

namespace App\Http\Controllers;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(AuthManager $manager)
    {
        return $manager->user();
    }
}
