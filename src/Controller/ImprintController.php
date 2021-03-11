<?php
    
    namespace App\Controller;
    
    use App\View\View;
    
    class ImprintController {
        public function index() {
            $view = new View('imprint/index');
            $view->title = 'Impressum';
            $view->display();
        }
    }