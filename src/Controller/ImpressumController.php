<?php
    
    namespace App\Controller;
    
    use App\View\View;

    class ImpressumController
    {
        public function index()
        {
            $view = new View('impressum/index');
            $view -> title = 'Impressum';
            $view -> display();
        }
    }