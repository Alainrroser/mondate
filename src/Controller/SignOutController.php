<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\View\View;
    
    class SignOutController {
        public function index() {
            Authentication::logout();
        }
    }