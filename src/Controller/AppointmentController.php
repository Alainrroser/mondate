<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;

class AppointmentController {
    public function create() {
        Authentication::restrictAuthenticated();

        $date = $_POST['date'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $creatorId = $_SESSION['userId'];

        $appointmentRepository = new AppointmentRepository();
        $appointmentRepository->createAppointment($date, $start, $end, $name, $description, $creatorId);

        header('Location: /calendar');
    }

    public function edit() {

    }

    public function delete() {

    }
}