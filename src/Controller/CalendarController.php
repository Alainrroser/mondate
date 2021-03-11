<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\AppointmentRepository;
    use App\Repository\TagRepository;
    use App\View\View;
    
    class CalendarController {
        const SECONDS_PER_WEEK = 60 * 60 * 24 * 7;
        const SECONDS_PER_SCOPE = 60 * 60 * 24 * 6;
        
        public function index() {
            Authentication::restrictAuthenticated();
            
            $userId = $_SESSION['userId'];
            $appointmentRepository = new AppointmentRepository();
            $tagRepository = new TagRepository();
            
            $appointments = $appointmentRepository->getAppointmentsForUser($userId);
            $tags = $tagRepository->getTags();
            
            $appointmentColors = [];
            
            foreach($appointments as $appointment) {
                $tagsForAppointment = $tagRepository->getTagsForAppointment($appointment->id);
                $tag = $tagsForAppointment[0]; // Get first tag
                
                $appointmentColors[$appointment->id] = $tag->color;
            }
            
            $this->setStartDateIfNotSet();
            
            $view = new View('calendar/index');
            $view->title = 'Calendar';
            $view->appointments = $appointments;
            $view->appointmentColors = $appointmentColors;
            $view->tags = $tags;
            $view->startDate = $_SESSION['startDate'];
            $view->endDate = $_SESSION['startDate'] + CalendarController::SECONDS_PER_SCOPE;
            $view->display();
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