<?php


namespace App\Repository;

use App\Database\ConnectionHandler;
use App\Model\Appointment;
use App\Model\Tag;
use Exception;

class AppointmentRepository extends Repository {
    protected $tableName = 'appointment';

    /**
     * @param $user_id
     *
     * @return array
     * @throws Exception
     */
    public function getAppointmentsForUser($user_id) {
        $query = "SELECT
                  a.id, a.date, a.start, a.end, a.name, a.description, a.creator_id,
                  tag.name AS \"tag\", tag.color AS \"tag_color\"
                  FROM appointment AS a
                  JOIN appointment_user ON a.id = appointment_user.appointment_id
                  LEFT JOIN appointment_tag ON a.id = appointment_tag.appointment_id
                  LEFT JOIN tag ON appointment_tag.tag_id = tag.id
                  WHERE appointment_user.user_id = ?";

        $rows = parent::executeAndGetRows($query, 'i', $user_id);

        $appointments = array();

        foreach($rows as $row) {
            $id = $row->id;

            $currentAppointment = null;
            foreach($appointments as $appointment) {
                if($appointment->getId() === $id) {
                    $currentAppointment = $appointment;
                    break;
                }
            }

            if(is_null($currentAppointment)) {
                $currentAppointment = new Appointment(
                    $id,
                    $row->date,
                    $row->start,
                    $row->end,
                    $row->name,
                    $row->description,
                    $row->creator_id
                );
                $appointments[] = $currentAppointment;
            }

            if($row->tag !== null) {
                $tag = new Tag($row->tag, $row->tag_color);
                $currentAppointment->addTag($tag);
            }
        }

        return $appointments;
    }

    public function createAppointment($date, $start, $end, $name, $description, $creatorId) {
        $queryAppointment = "INSERT INTO $this->tableName (date, start, end, name, description, creator_id)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $appointmentId = self::insertAndGetId($queryAppointment, 'sssssi', $date, $start, $end, $name, $description, $creatorId);

        $queryAppointmentUser = "INSERT INTO appointment_user (appointment_id, user_id) VALUES (?, ?)";
        self::insertAndGetId($queryAppointmentUser, 'ii', $appointmentId, $creatorId);
    }

    public function deleteAppointsFromUser($userId) {
        $queryDeleteAppointment = "DELETE appointment FROM appointment
                                   JOIN appointment_user ON appointment.id = appointment_user.appointment_id
                                   WHERE appointment_user.user_id = ?";
        $this->execute($queryDeleteAppointment, 'i', $userId);
    }

}