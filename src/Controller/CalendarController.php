<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\AppointmentRepository;
    use App\Repository\TagRepository;
    use App\View\View;
    use DateTime;
    
    class CalendarController {
        
        public function displayView($errors) {
            $userId = $_SESSION['userId'];
            $appointmentRepository = new AppointmentRepository();
            $tagRepository = new TagRepository();
            
            $appointments = $appointmentRepository->getAppointmentsForUser($userId);
            $tags = $tagRepository->readAll();
            
            $startDate = $_SESSION['startDate'];
            $endDate = clone $_SESSION['startDate'];
            $endDate->add(date_interval_create_from_date_string('6 days'));
            
            $view = new View('calendar/index');
            $view->title = 'Calendar';
            $view->appointments = $appointments;
            $view->tags = $tags;
            $view->startDate = $startDate;
            $view->endDate = $endDate;
            $view->errors = $errors;
            $view->display();
        }
        
        public function index() {
            Authentication::restrictAuthenticated();
            $this->setStartDateIfNotSet();
            $this->displayView([]);
        }
        
        private function setStartDateIfNotSet() {
            if(!isset($_SESSION['startDate'])) {
                $_SESSION['startDate'] = new DateTime('monday this week');
                echo $_SESSION['startDate']->getTimestamp();
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
        
        public function jumpToDate() {
            Authentication::restrictAuthenticated();
    
            $this->setStartDateIfNotSet();
            $_SESSION["startDate"] = new DateTime($_POST["weekStart"]);
            if($_SESSION["startDate"]->format("N") != 1) {
                $_SESSION["startDate"]->modify("last monday");
            }
        }
    }