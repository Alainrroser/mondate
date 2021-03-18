<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\AppointmentRepository;
    use App\Repository\TagRepository;
    use App\View\View;
    use DateTime;

    class CalendarController {

        public function index() {
            Authentication::restrictAuthenticated();
            
            $userId = $_SESSION['userId'];
            $appointmentRepository = new AppointmentRepository();
            $tagRepository = new TagRepository();

            $appointments = $appointmentRepository->getAppointmentsForUser($userId);
            $tags = $tagRepository->readAll();
            $this->setStartDateIfNotSet();

            $startDate = $_SESSION['startDate'];
            $endDate = clone $_SESSION['startDate'];
            $endDate->add(date_interval_create_from_date_string('6 days'));

            $view = new View('calendar/index');
            $view->title = 'Calendar';
            $view->appointments = $appointments;
            $view->tags = $tags;
            $view->startDate = $startDate;
            $view->endDate = $endDate;
            $view->display();
        }
        
        private function setStartDateIfNotSet() {
            if(!isset($_SESSION['startDate'])) {
                $_SESSION['startDate'] = new DateTime('monday this week');
            }
        }
        
        public function next() {
            Authentication::restrictAuthenticated();

            $this->setStartDateIfNotSet();
            $_SESSION['startDate']->add(date_interval_create_from_date_string('1 week'));
            header('Location: /calendar');
        }
        
        public function last() {
            Authentication::restrictAuthenticated();

            $this->setStartDateIfNotSet();
            $_SESSION['startDate']->sub(date_interval_create_from_date_string('1 week'));
            header('Location: /calendar');
        }
    }