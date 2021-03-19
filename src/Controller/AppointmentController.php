<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\AppointmentRepository;
    use App\Repository\TagRepository;
    use App\Repository\UserRepository;
    use App\View\JsonView;
    
    class AppointmentController {
        public function create() {
            Authentication::restrictAuthenticated();
            
            $appointmentRepository = new AppointmentRepository();
            $rows = $appointmentRepository->getStartAndEndFromId();
            foreach($rows as $row) {
                if($_POST["start"] <= $row->start && $_POST["start"] >= $row->end && $_POST["end"] <= $row->start && $_POST["end"] >= $row->end) {
                    if($this->validateAppointmentData()) {
                        $date = $_POST['date'];
                        $start = $_POST['start'];
                        $end = $_POST['end'];
                        $name = $_POST['name'];
                        $description = $_POST['description'];
                        $tags = $_POST['tags'];
                        $creatorId = $_SESSION['userId'];
                        
                        $tagIds = !isset($tags) ? [] : array_keys($tags);
                        $emails = !isset($_POST['emails']) ? [] : $_POST['emails'];
                        
                        $appointmentRepository = new AppointmentRepository();
                        $id = $appointmentRepository->createAppointment($date, $start, $end, $name, $description, $creatorId, $tagIds);
                        $appointmentRepository->shareAppointment($id, $emails);
                        
                        if($appointmentRepository->shareAppointment($id, $emails)) {
                            header('Location: /calendar');
                        } else {
                            $calendarController = new CalendarController();
                            $calendarController->displayView(["Can't share appointment with non-existing user."]);
                        }
                    }
                } else {
                    $calendarController = new CalendarController();
                    $calendarController->displayView(["There already exists an appointment in this time frame."]);
                }
            }
        }
        
        public function edit() {
            Authentication::restrictAuthenticated();
            
            if($this->validateAppointmentData()) {
                $id = $_POST['id'];
                $date = $_POST['date'];
                $start = $_POST['start'];
                $end = $_POST['end'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $tags = $_POST['tags'];
                
                $tagIds = !isset($tags) ? [] : array_keys($tags);
                $emails = !isset($_POST['emails']) ? [] : $_POST['emails'];
                
                $appointmentRepository = new AppointmentRepository();
                $appointmentRepository->editAppointment($id, $date, $start, $end, $name, $description, $tagIds);
                
                if($appointmentRepository->shareAppointment($id, $emails)) {
                    header('Location: /calendar');
                } else {
                    $calendarController = new CalendarController();
                    $calendarController->displayView(["Can't share appointment with non-existing user."]);
                }
            }
        }
        
        private function validateAppointmentData() {
            if(!self::postKeysExist('date', 'start', 'end', 'name', 'description')) {
                echo "Invalid input, missing data";
                return false;
            }
            
            if(!self::postKeysNotEmpty('id', 'date', 'start', 'end', 'name', 'description')) {
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
        
        public function delete() {
            Authentication::restrictAuthenticated();
            
            if(isset($_POST["id"])) {
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
                    $response = [];
                    $response['date'] = $appointment->date;
                    $response['start'] = $appointment->start;
                    $response['end'] = $appointment->end;
                    $response['name'] = $appointment->name;
                    $response['description'] = $appointment->description;
                    $response['tags'] = [];
                    
                    foreach($tagRepository->getTagsForAppointment($id) as $tag) {
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
    }