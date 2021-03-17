<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;
use App\View\JsonView;

class AppointmentController {
    public function create() {
        Authentication::restrictAuthenticated();

        if(!$this->validateAppointmentKeysExist()) {
            return;
        }

        $date = $_POST['date'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $tags = $_POST['tags'];
        $creatorId = $_SESSION['userId'];

        if(empty($date) || empty($start) || empty($end) || empty($name) || empty($description)) {
            echo "Invalid input, all fields must be filled out";
            return;
        }

        $tagIds = is_null($tags) ? array() : array_keys($tags);

        $appointmentRepository = new AppointmentRepository();
        $appointmentRepository->createAppointment($date, $start, $end, $name, $description, $creatorId, $tagIds);

        header('Location: /calendar');
    }

    public function edit() {
        Authentication::restrictAuthenticated();

        $id = $_POST['id'];
        $date = $_POST['date'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $tags = $_POST['tags'];

        if(empty($id) || empty($date) || empty($start) || empty($end) || empty($name) || empty($description)) {
            echo "Invalid input, all fields must be filled out";
            return;
        }

        $tagIds = is_null($tags) ? array() : array_keys($tags);

        $appointmentRepository = new AppointmentRepository();
        $appointmentRepository->editAppointment($id, $date, $start, $end, $name, $description, $tagIds);

        header('Location: /calendar');
    }

    private function validateAppointmentKeysExist() {
        if(!self::array_keys_exist($_POST, 'date', 'start', 'end', 'name', 'description')) {
            echo "Invalid input, missing data";
            return false;
        }

        return true;
    }

    private function array_keys_exist($array, ...$keys) {
        foreach($keys as $key) {
            if(!array_key_exists($key, $array)) {
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
        }

        header('Location: /calendar');
    }

    public function get() {
        $appointmentRepository = new AppointmentRepository();
        $appointment = $appointmentRepository->readById($_GET['id']);

        $response = array();
        $response['date'] = $appointment->date;
        $response['start'] = $appointment->start;
        $response['end'] = $appointment->end;
        $response['name'] = $appointment->name;
        $response['description'] = $appointment->description;

        $view = new JsonView();
        $view->setJsonObject($response);
        $view->display();
    }
}