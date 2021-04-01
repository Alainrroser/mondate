<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\View\View;

class SignInController {
    
    public function index() {
        if(Authentication::isAuthenticated()) {
            header('Location: /calendar/');
        }
        
        $view = new View('signIn/index');
        $view->title = 'Sign In';
        $view->errors = [];
        $view->background = true;
        $view->display();
    }
    
}