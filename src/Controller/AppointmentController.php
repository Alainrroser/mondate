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

        if ($this->checkIfAppointmentDataPresent()) {
            $date = $_POST['date'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
            $description = htmlspecialchars($_POST['description'], ENT_QUOTES, "UTF-8");
            $creatorId = $_SESSION['userId'];

            if ($this->validateAppointmentTimes(null, $date, $start, $end)) {
                $tagIds = !isset($_POST['tags']) ? [] : array_keys($_POST['tags']);
                $emails = !isset($_POST['emails']) ? [] : $_POST['emails'];

                $appointmentRepository = new AppointmentRepository();
                $id = $appointmentRepository->createAppointment($date, $start, $end, $name, $description, $creatorId, $tagIds);
                $appointmentRepository->shareAppointment($id, $emails);

                if($this->validateAndShareAppointment($id, $emails)) {
                    header('Location: /calendar');
                }
            }
        }
    }

    public function edit() {
        Authentication::restrictAuthenticated();

        if ($this->checkIfAppointmentDataPresent()) {
            if(!$this->validateEditingUserIsCreator($_POST["id"])) {
                return;
            }

            $id = $_POST['id'];
            $date = $_POST['date'];
            $start = $_POST['start'];
            $end = $_POST['end'];
            $name = htmlspecialchars($_POST['name'], ENT_QUOTES, "UTF-8");
            $description = htmlspecialchars($_POST['description'], ENT_QUOTES, "UTF-8");

            if ($this->validateAppointmentTimes($id, $date, $start, $end)) {
                $tagIds = !isset($_POST['tags']) ? [] : array_keys($_POST['tags']);
                $emails = !isset($_POST['emails']) ? [] : $_POST['emails'];

                $appointmentRepository = new AppointmentRepository();
                $appointmentRepository->editAppointment($id, $date, $start, $end, $name, $description, $tagIds);

                if($this->validateAndShareAppointment($id, $emails)) {
                    header('Location: /calendar');
                }
            }
        }
    }

    private function checkIfAppointmentDataPresent() {
        if (!self::postKeysExist('date', 'start', 'end', 'name', 'description')) {
            echo "Invalid input, missing data";
            return false;
        }

        if (!self::postKeysNotEmpty('id', 'date', 'start', 'end', 'name', 'description')) {
            echo "Invalid input, all fields must be filled out";
            return false;
        }

        return true;
    }

    private function postKeysExist(...$keys) {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $_POST)) {
                return false;
            }
        }

        return true;
    }

    private function postKeysNotEmpty(...$keys) {
        foreach ($keys as $key) {
            if (empty($key)) {
                return false;
            }
        }

        return true;
    }

    private function validateAppointmentTimes($id, $date, $start, $end) {
        $appointmentRepository = new AppointmentRepository();
        $rows = $appointmentRepository->getAppointmentsForUser($_SESSION["userId"]);

        foreach ($rows as $row) {
            if (!$id || $id != $row->getId()) {
                if($row->getDate() === $date) {
                    $overlapTop = $start >= $row->getStart() && $start <= $row->getEnd();
                    $overlapBottom = $end >= $row->getStart() && $end <= $row->getEnd();

                    if ($overlapTop || $overlapBottom) {
                        $calendarController = new CalendarController();
                        $calendarController->displayView(["There already exists an appointment in this time frame."]);
                        return false;
                    }
                }
            }
        }

        if (strtotime($end) - strtotime($start) < 0) {
            $calendarController = new CalendarController();
            $calendarController->displayView(["An appointment can't end before it starts."]);
            return false;
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

        if ($appointmentRepository->shareAppointment($id, $emails)) {
            return true;
        } else {
            $calendarController = new CalendarController();
            $calendarController->displayView(["Can't share appointment with non-existing user."]);
            return false;
        }
    }

    public function delete() {
        Authentication::restrictAuthenticated();

        if (isset($_POST["id"])) {
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

    public function get() {
        Authentication::restrictAuthenticated();

        if (isset($_GET["id"])) {
            $appointmentRepository = new AppointmentRepository();
            $tagRepository = new TagRepository();
            $userRepository = new UserRepository();

            $id = $_GET['id'];
            $appointment = $appointmentRepository->readById($id);

            if ($appointment) {
                $response = [];
                $response['date'] = $appointment->date;
                $response['start'] = $appointment->start;
                $response['end'] = $appointment->end;
                $response['name'] = $appointment->name;
                $response['description'] = $appointment->description;
                $response['tags'] = [];

                foreach ($tagRepository->getTagsForAppointment($id) as $tag) {
                    $response['tags'][] = $tag->id;
                }

                foreach ($userRepository->getNonCreatorUsersForAppointment($id) as $user) {
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