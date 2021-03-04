<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\View\View;
    
    class CalendarController {
        public function index() {
            Authentication::restrictAuthenticated();
            $view = new View('calendar/index');
            $view -> title = 'Calendar';
            $view -> display();
        }
    }