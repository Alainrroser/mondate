<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\View\View;
    
    class LogoutController {
        public function index() {
            Authentication::logout();
        }
    }