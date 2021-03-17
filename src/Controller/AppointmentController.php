<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;
use App\Repository\TagRepository;
use App\View\JsonView;

class AppointmentController {
    public function create() {
        Authentication::restrictAuthenticated();

        if($this->validateAppointmentData()) {
            $date = $_POST['date'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $tags = $_POST['tags'];
            $creatorId = $_SESSION['userId'];

            $tagIds = is_null($tags) ? array() : array_keys($tags);

            $appointmentRepository = new AppointmentRepository();
            $appointmentRepository->createAppointment($date, $start, $end, $name, $description, $creatorId, $tagIds);

            header('Location: /calendar');
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

            $tagIds = is_null($tags) ? array() : array_keys($tags);

            $appointmentRepository = new AppointmentRepository();
            $appointmentRepository->editAppointment($id, $date, $start, $end, $name, $description, $tagIds);

            header('Location: /calendar');
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

    public function share() {
        Authentication::restrictAuthenticated();

        $id = $_POST['id'];
        $userEmails = isset($_POST['users']) ? array() : array_keys($_POST['users']);

        $appointmentRepository = new AppointmentRepository();
        $appointmentRepository->shareAppointment($id, $userEmails);

        header('Location: /calendar');
    }

    public function delete() {
        Authentication::restrictAuthenticated();

        if(isset($_POST["id"])) {
            $appointmentRepository = new AppointmentRepository();
            $appointmentRepository->deleteById($_POST["id"]);
            header('Location: /calendar');
        } else {
            echo "Invalid input, appointment ID missing";
        }
    }

    public function get() {
        Authentication::restrictAuthenticated();

        if(isset($_GET["id"])) {
            $appointmentRepository = new AppointmentRepository();
            $tagRepository = new TagRepository();

            $id = $_GET['id'];
            $appointment = $appointmentRepository->readById($id);

            if($appointment) {
                $response = array();
                $response['date'] = $appointment->date;
                $response['start'] = $appointment->start;
                $response['end'] = $appointment->end;
                $response['name'] = $appointment->name;
                $response['description'] = $appointment->description;
                $response['tags'] = array();

                foreach($tagRepository->getTagsForAppointment($id) as $tag) {
                    $response['tags'][] = $tag->id;
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