<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;
use App\Repository\TagRepository;
use App\View\View;
use DateTime;
use DateTimeZone;

class CalendarController {
    
    public function index() {
        if(!isset($_SESSION["timezone"])) {
            header("Location: /calendar/requestTimezone");
        }
        
        Authentication::restrictAuthenticated();
        $this->setStartDateIfNotSet();
        $this->displayView([]);
    }
    
    private function setStartDateIfNotSet() {
        if(!isset($_SESSION['startDate'])) {
            $timezone = new DateTimeZone($_SESSION["timezone"]);
            $_SESSION['startDate'] = new DateTime('monday this week', $timezone);
        }
    }
    
    public function displayView($errors) {
        $userId = $_SESSION['userId'];
        $appointmentRepository = new AppointmentRepository();
        $tagRepository = new TagRepository();
        
        $appointments = $appointmentRepository->getAppointmentsFromUser($userId);
        $tags = $tagRepository->getAllTags();
        $usedTags = $tagRepository->getTagsFromUser($userId);
        
        $startDate = $_SESSION['startDate'];
        $endDate = clone $_SESSION['startDate'];
        $endDate->add(date_interval_create_from_date_string('6 days'));
        
        $view = new View('calendar/index');
        $view->transition = isset($_POST["transition"]) && $_POST["transition"] == "true";
        $view->title = 'Calendar';
        $view->appointments = $appointments;
        $view->tags = $tags;
        $view->usedTags = $usedTags;
        $view->startDate = $startDate;
        $view->endDate = $endDate;
        $view->errors = $errors;
        $view->display();
    }
    
    public function requestTimezone() {
        Authentication::restrictAuthenticated();
        
        $view = new View('calendar/uploadTimezone');
        $view->title = 'Uploading Timezone...';
        $view->display();
    }
    
    public function uploadTimezone() {
        if(isset($_POST['timezone'])) {
            date_default_timezone_set($_POST['timezone']);
            $_SESSION['timezone'] = $_POST['timezone'];
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
        $_SESSION["startDate"] = new DateTime($_POST["weekStart"], new DateTimeZone($_SESSION["timezone"]));
        if($_SESSION["startDate"]->format("N") != 1) {
            $_SESSION["startDate"]->modify("last monday");
        }
    }
    
}