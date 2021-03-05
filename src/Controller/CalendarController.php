<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\View\View;
    use App\Repository\AppointmentRepository;
    
    class CalendarController {

        const SECONDS_PER_WEEK = 60 * 60 * 24 * 7;

        public function index() {
            Authentication::restrictAuthenticated();

            $userId = $_SESSION['userId'];
            $appointmentRepository = new AppointmentRepository();
            $appointments = $appointmentRepository->get_appointments_for_user($userId);

            $this->setStartDateIfNotSet();

            $view = new View('calendar/index');
            $view -> title = 'Calendar';
            $view->appointments = $appointments;
            $view->start_date = $_SESSION['startDate'];
            $view->end_date = $_SESSION['startDate'] + 60 * 60 * 24 * 6;
            $view -> display();
        }

        private function setStartDateIfNotSet() {
            if(!isset($_SESSION['startDate'])) {
                $_SESSION['startDate'] = strtotime('monday this week');
            }
        }

        public function next() {
            $this->setStartDateIfNotSet();
            $_SESSION['startDate'] += CalendarController::SECONDS_PER_WEEK;
            header('Location: /calendar');
        }

        public function last() {
            $this->setStartDateIfNotSet();
            $_SESSION['startDate'] -= CalendarController::SECONDS_PER_WEEK;
            header('Location: /calendar');
        }
    }