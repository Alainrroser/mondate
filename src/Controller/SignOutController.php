<?php


namespace App\Controller;


use App\Authentication\Authentication;

class SignOutController {
    
    public function index() {
        Authentication::logout();
    }
    
}