<?php
    
    namespace App\Controller;
    
    use App\View\View;
    
    class SignInController {
        public function index() {
            $view = new View('signIn/index');
            $view->title = 'Sign In';
            $view->display();
        }
    }