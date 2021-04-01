<?php

namespace App\Controller;

use App\Authentication\Authentication;

class DefaultController {
    
    public function index() {
        Authentication::restrictAuthenticated();
        header("Location: /calendar");
    }
    
}
