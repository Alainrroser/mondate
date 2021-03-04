<?php
    
    
    namespace App\Controller;
    
    
    use App\View\View;
    
    class CalendarController {
        public function index() {
            $view = new View('calendar/index');
            $view -> title = 'Calendar';
            $view -> heading = 'Calendar';
            $view -> display();
        }
    }