<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\AppointmentRepository;
    use App\Repository\TagRepository;
    use App\Repository\UserRepository;
    use App\Util\DateTimeUtils;
    use App\View\JsonView;
    use DateTime;
    
    class AppointmentController {
        
        private $MIN_APPOINTMENT_DURATION;
        private $MIN_DATE;
        private $MAX_DATE;
        
        public function __construct() {
            $this->MIN_APPOINTMENT_DURATION = 60 * 15;
            $this->MIN_DATE = DateTime::createFromFormat("Y-m-d", "1975-01-01");
            $this->MAX_DATE = DateTime::createFromFormat("Y-m-d", "2035-01-01");
        }
        
        public function create() {
            Authentication::restrictAuthenticated();
            
            if($this->checkIfAppointmentDataPresent()) {
                $start = $_POST['start'];
                $end = $_POST['end'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $creatorId = $_SESSION['userId'];
                
                $startUTC = DateTime::createFromFormat("Y-m-d\TH:i", $start);
                $endUTC = DateTime::createFromFormat("Y-m-d\TH:i", $end);
                
                if($this->validateAppointmentTimes(null, $startUTC, $endUTC)) {
                    $tagIds = !isset($_POST['tags']) ? [] : array_keys($_POST['tags']);
                    $emails = !isset($_POST['emails']) ? [] : $_POST['emails'];
                    
                    $appointmentRepository = new AppointmentRepository();
                    $id = $appointmentRepository->createAppointment($startUTC, $endUTC, $name, $description, $creatorId, $tagIds);
                    $appointmentRepository->shareAppointment($id, $emails);
                    
                    if($this->validateAndShareAppointment($id, $emails)) {
                        header('Location: /calendar');
                    }
                }
            }
        }
        
        private function checkIfAppointmentDataPresent() {
            if(!self::postKeysExist('start', 'end', 'name', 'description')) {
                echo "Invalid input, missing data";
                return false;
            }
            
            if(!self::postKeysNotEmpty('id', 'start', 'end', 'name', 'description')) {
                echo "Invalid input, all fields must be filled out";
                return false;
            }
            
            return true;
        }
        
        private function postKeysExist(...$keys) {
            foreach($keys as $key) {
                if(!array_key_exists($key, $_POST)) {
                    return false;
                }
            }
            
            return true;
        }
        
        private function postKeysNotEmpty(...$keys) {
            foreach($keys as $key) {
                if(empty($key)) {
                    return false;
                }
            }
            
            return true;
        }
        
        private function validateAppointmentTimes($id, $start, $end) {
            if($end < $start) {
                $calendarController = new CalendarController();
                $calendarController->displayView(["An appointment can't end before it starts."]);
                return false;
            }
            
            if($end->getTimestamp() - $start->getTimestamp() < $this->MIN_APPOINTMENT_DURATION) {
                $minMinutes = $this->MIN_APPOINTMENT_DURATION / 60;
                
                $calendarController = new CalendarController();
                $calendarController->displayView(["An appointment can't be shorter than $minMinutes minutes."]);
                return false;
            }
            
            if($end < $this->MIN_DATE || $end > $this->MAX_DATE || $start < $this->MIN_DATE || $start > $this->MAX_DATE) {
                $minDate = $this->MIN_DATE->format("Y-m-d");
                $maxDate = $this->MAX_DATE->format("Y-m-d");
                
                $calendarController = new CalendarController();
                $calendarController->displayView(["Appointments must lay between $minDate and $maxDate."]);
                return false;
            }
            
            $appointmentRepository = new AppointmentRepository();
            $rows = $appointmentRepository->getAppointmentsFromUser($_SESSION["userId"]);
            
            foreach($rows as $row) {
                if(!$id || $id != $row->getId()) {
                    $overlapStartA = $start >= $row->getStartAsDateTime() && $start < $row->getEndAsDateTime();
                    $overlapEndA = $end > $row->getStartAsDateTime() && $end < $row->getEndAsDateTime();
                    $overlapStartB = $row->getStartAsDateTime() >= $start && $row->getStartAsDateTime() < $end;
                    $overlapEndB = $row->getEndAsDateTime() > $start && $row->getEndAsDateTime() < $end;
                    
                    if($overlapStartA || $overlapEndA || $overlapStartB || $overlapEndB) {
                        $calendarController = new CalendarController();
                        $calendarController->displayView(["There already exists an appointment in this time frame."]);
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        private function validateAndShareAppointment($id, $emails) {
            $appointmentRepository = new AppointmentRepository();
            $userRepository = new UserRepository();
            
            $myEmail = $userRepository->readById($_SESSION['userId'])->email;
            
            foreach($emails as $email) {
                if($email == $myEmail) {
                    $calendarController = new CalendarController();
                    $calendarController->displayView(["Can't share appointment with yourself."]);
                    return false;
                }
            }
            
            if($appointmentRepository->shareAppointment($id, $emails)) {
                return true;
            } else {
                $calendarController = new CalendarController();
                $calendarController->displayView(["Can't share appointment with non-existing user."]);
                return false;
            }
        }
        
        public function edit() {
            Authentication::restrictAuthenticated();
            
            if($this->checkIfAppointmentDataPresent()) {
                if(!$this->validateEditingUserIsCreator($_POST["id"])) {
                    return;
                }
                
                $id = $_POST['id'];
                $start = $_POST['start'];
                $end = $_POST['end'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                
                $startUTC = DateTime::createFromFormat("Y-m-d\TH:i", $start);
                $endUTC = DateTime::createFromFormat("Y-m-d\TH:i", $end);

                if($this->validateAppointmentTimes($id, $startUTC, $endUTC)) {
                    $tagIds = !isset($_POST['tags']) ? [] : array_keys($_POST['tags']);
                    $emails = !isset($_POST['emails']) ? [] : $_POST['emails'];

                    $appointmentRepository = new AppointmentRepository();
                    $appointmentRepository->editAppointment($id, $startUTC, $endUTC, $name, $description, $tagIds);

                    if($this->validateAndShareAppointment($id, $emails)) {
                        header('Location: /calendar');
                    }
                }
            }
        }
        
        private function validateEditingUserIsCreator($appointmentId) {
            $appointmentRepository = new AppointmentRepository();
            
            $creatorId = $appointmentRepository->readById($appointmentId)->creator_id;
            if($_SESSION["userId"] !== $creatorId) {
                $calendarController = new CalendarController();
                $calendarController->displayView(["You can't edit or delete appointments that you haven't created."]);
                return false;
            }
            
            return true;
        }
        
        public function delete() {
            Authentication::restrictAuthenticated();
            
            if(isset($_POST["id"])) {
                if(!$this->validateEditingUserIsCreator($_POST["id"])) {
                    return;
                }
                
                $appointmentRepository = new AppointmentRepository();
                $appointmentRepository->deleteById($_POST["id"]);
                header('Location: /calendar');
            } else {
                echo "Invalid input, missing appointment ID";
            }
        }
        
        public function get() {
            Authentication::restrictAuthenticated();
            
            if(isset($_GET["id"])) {
                $appointmentRepository = new AppointmentRepository();
                $tagRepository = new TagRepository();
                $userRepository = new UserRepository();
                
                $id = $_GET['id'];
                $appointment = $appointmentRepository->readById($id);
                
                if($appointment) {
                    $startTimeDateUTC = DateTimeUtils::parseDatabaseDateTime($appointment->start);
                    $startTimeDateUTC = DateTimeUtils::convertUTCToLocal($startTimeDateUTC);
                    $endTimeDateUTC = DateTimeUtils::parseDatabaseDateTime($appointment->end);
                    $endTimeDateUTC = DateTimeUtils::convertUTCToLocal($endTimeDateUTC);
                    
                    $startTimeDateSplit = explode(" ", $startTimeDateUTC->format("Y-m-d H:i"));
                    $endTimeDateSplit = explode(" ", $endTimeDateUTC->format("Y-m-d H:i"));
                    
                    $response = [];
                    $response['start'] = $startTimeDateSplit[0]."T".$startTimeDateSplit[1];
                    $response['end'] = $endTimeDateSplit[0]."T".$endTimeDateSplit[1];
                    $response['name'] = $appointment->name;
                    $response['description'] = $appointment->description;
                    $response['tags'] = [];
                    
                    foreach($tagRepository->getTagsFromAppointment($id) as $tag) {
                        $response['tags'][] = $tag->id;
                    }
                    
                    foreach($userRepository->getNonCreatorUsersForAppointment($id) as $user) {
                        $response['emails'][] = $user->email;
                    }
                    
                    $view = new JsonView();
                    $view->setJsonObject($response);
                    $view->display();
                } else {
                    echo "Invalid input, appointment ID not found in database";
                }
            } else {
                echo "Invalid input, appointment ID missing";
            }
        }
        
        public function getAppointmentsFromUser() {
            Authentication::restrictAuthenticated();
            
            $appointmentRepository = new AppointmentRepository();
            $appointments = $appointmentRepository->getAppointmentsFromUser($_SESSION["userId"]);
            
            $response = [];
            foreach($appointments as $appointment) {
                $appointmentArray = [];
                $tagArray = [];
                foreach($appointment->getTags() as $tag) {
                    $tagArray[] = $tag;
                }
                $appointmentArray["name"] = $appointment->getName();
                $appointmentArray["description"] = $appointment->getDescription();
                $appointmentArray["start"] = $appointment->getStart();
                $appointmentArray["end"] = $appointment->getEnd();
                $appointmentArray["tags"] = $tagArray;
                
                $response[] = $appointmentArray;
            }
            
            $view = new JsonView();
            $view->setJsonObject($response);
            $view->display();
        }
    }