<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;

class AppointmentController {
    public function create() {
        Authentication::restrictAuthenticated();

        if(!self::array_keys_exist($_POST, 'date', 'start', 'end', 'name', 'description')) {
            echo "Invalid input, missing data";
            return;
        }

        $date = $_POST['date'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $creatorId = $_SESSION['userId'];

        if(empty($date) || empty($start) || empty($end) || empty($name) || empty($description)) {
            echo "Invalid input, all fields must be filled out";
            return;
        }

        $appointmentRepository = new AppointmentRepository();
        $appointmentRepository->createAppointment($date, $start, $end, $name, $description, $creatorId);

        header('Location: /calendar');
    }

    private function array_keys_exist($array, ...$keys) {
        foreach($keys as $key) {
            if(!array_key_exists($key, $array)) {
                return false;
            }
        }

        return true;
    }

    public function edit() {

    }

    public function delete() {
        Authentication::restrictAuthenticated();

        if(isset($_POST["id"])) {
            $appointmentRepository = new AppointmentRepository();
            $appointmentRepository->deleteById($_POST["id"]);
        }

        header('Location: /calendar');
    }
}