<?php
    
    
    namespace App\Controller;
    
    
    use App\View\View;

    class AppointmentController {
        public function index() {
            $view = new View('');
            $view->title = 'Create Appointment';
            $view->display();
        }
        
        public function edit() {
            $view = new View('');
            $view->title = 'Edit Appointment';
            $view->display();
        }
    }