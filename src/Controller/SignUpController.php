<?php
    
    namespace App\Controller;
    
    use App\View\View;
    
    class SignUpController {
        public function index() {
            $view = new View('signUp/index');
            $view->title = 'Sign Up';
            $view->display();
        }
    }

