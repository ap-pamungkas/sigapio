<?php

namespace App\Http\Controllers;

use App\Livewire\Auth\Login;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authLogout;

    public function __construct(){
        $this->authLogout = new Login;
    }
   public function logout(){
   $this->authLogout->logout(); 
   return redirect()->route("login");
   }
}
