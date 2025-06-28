<?php

namespace App\Http\Controllers;

use App\Livewire\Auth\Login;


class AuthController extends Controller
{
    protected $authLogout;

    public function __construct(){
        $this->authLogout = new Login;
    }
   public function logout(){
   return $this->authLogout->logout(); 
 
   }
}
