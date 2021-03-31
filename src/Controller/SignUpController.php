<?php
    
    namespace App\Controller;
    
    use App\Authentication\Authentication;
    use App\View\View;
    
    class SignUpController {
        
        public function index() {
            if(Authentication::isAuthenticated()) {
                header('Location: /calendar/');
            }
            
            $view = new View('signUp/index');
            $view->background = true;
            $view->title = 'Sign Up';
            $view->errors = [];
            $view->display();
        }
    }

