<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\View\View;
    use App\Repository\AppointmentRepository;
    
    class CalendarController {
        public function index() {
            Authentication::restrictAuthenticated();

            $userId = $_SESSION['userId'];
            $appointmentRepository = new AppointmentRepository();
            $appointments = $appointmentRepository->get_appointments_for_user($userId);

            $view = new View('calendar/index');
            $view -> title = 'Calendar';
            $view->appointments = $appointments;
            $view->start_date = 1614553200;
            $view->end_date = 1614553200 + 60 * 60 * 24 * 6;
            $view -> display();
        }
    }