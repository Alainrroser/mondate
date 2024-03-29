<?php


namespace App\Repository;

use App\Model\Appointment;
use App\Model\Tag;
use App\Util\DateTimeUtils;

class AppointmentRepository extends Repository {
    
    protected $tableName = 'appointment';
    
    private function convertRowToAppointment($row) {
        return new Appointment(
            $row->id,
            $row->start,
            $row->end,
            $row->name,
            $row->description,
            $row->creator_id
        );
    }
    
    public function getAppointmentsFromUser($userId) {
        $query = "SELECT
                  a.id, a.start, a.end, a.name, a.description, a.creator_id,
                  tag.name AS \"tag\", tag.color AS \"tag_color\"
                  FROM appointment AS a
                  JOIN appointment_user ON a.id = appointment_user.appointment_id
                  LEFT JOIN appointment_tag ON a.id = appointment_tag.appointment_id
                  LEFT JOIN tag ON appointment_tag.tag_id = tag.id
                  WHERE appointment_user.user_id = ?";
        
        $rows = parent::executeAndGetRows($query, 'i', $userId);
        
        $appointments = [];
        
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
                $currentAppointment = $this->convertRowToAppointment($row);
                $appointments[] = $currentAppointment;
            }
            
            if($row->tag !== null) {
                $tag = new Tag($row->id, $row->tag, $row->tag_color);
                $currentAppointment->addTag($tag);
            }
        }
        
        return $appointments;
    }
    
    public function createAppointment($start, $end, $name, $description, $creatorId, $tagIds) {
        // Convert from local time to UTC string
        $start = DateTimeUtils::convertLocalToUTC($start)->format("Y-m-d H:i");
        $end = DateTimeUtils::convertLocalToUTC($end)->format("Y-m-d H:i");
        
        $queryAppointment = "INSERT INTO $this->tableName (start, end, name, description, creator_id)
                             VALUES (?, ?, ?, ?, ?)";
        $appointmentId = self::executeAndGetInsertId($queryAppointment, 'ssssi', $start, $end, $name, $description, $creatorId);
        
        $queryAppointmentUser = "INSERT INTO appointment_user (appointment_id, user_id) VALUES (?, ?)";
        self::executeAndGetInsertId($queryAppointmentUser, 'ii', $appointmentId, $creatorId);
        
        foreach($tagIds as $tagId) {
            $queryAppointmentTag = "INSERT INTO appointment_tag (appointment_id, tag_id) VALUES (?, ?)";
            self::executeAndGetInsertId($queryAppointmentTag, 'ii', $appointmentId, $tagId);
        }
        
        return $appointmentId;
    }
    
    public function editAppointment($id, $start, $end, $name, $description, $tagIds) {
        // Convert from local time to UTC string
        $start = DateTimeUtils::convertLocalToUTC($start)->format("Y-m-d H:i");
        $end = DateTimeUtils::convertLocalToUTC($end)->format("Y-m-d H:i");
        
        $queryDeleteAppointmentTag = "DELETE FROM appointment_tag WHERE appointment_id = ?";
        self::execute($queryDeleteAppointmentTag, "i", $id);
        
        $query = "UPDATE $this->tableName SET start = ?, end = ?, name = ?, description = ? WHERE id = ?";
        $this->execute($query, 'ssssi', $start, $end, $name, $description, $id);
        
        foreach($tagIds as $tagId) {
            $queryAppointmentTag = "INSERT INTO appointment_tag (appointment_id, tag_id) VALUES (?, ?)";
            self::executeAndGetInsertId($queryAppointmentTag, 'ii', $id, $tagId);
        }
    }
    
    public function shareAppointment($id, $userEmails) {
        // Delete all previous appointment_user entries for this appointment
        $deleteAppointmentUsersQuery = "DELETE appointment_user FROM appointment
                                        JOIN appointment_user ON appointment.id = appointment_user.appointment_id
                                        WHERE appointment.id = ? AND appointment_user.user_id != appointment.creator_id";
        $this->execute($deleteAppointmentUsersQuery, 'i', $id);
        
        // Verify that all users actually exist
        foreach($userEmails as $userEmail) {
            $userExistsQuery = "SELECT * FROM user WHERE email = ? LIMIT 1";
            if(!$this->executeAndGetRows($userExistsQuery, 's', $userEmail)) {
                return false;
            }
        }
        
        // Insert the appointment_user entries
        foreach($userEmails as $userEmail) {
            $query = "INSERT INTO appointment_user (appointment_id, user_id)
                      VALUES (?, (SELECT id FROM user WHERE email = ?))";
            self::executeAndGetInsertId($query, 'is', $id, $userEmail);
        }
        
        return true;
    }
    
    public function deleteAppointmentsFromUser($userId) {
        $queryDeleteAppointment = "DELETE appointment FROM appointment
                                   JOIN appointment_user ON appointment.id = appointment_user.appointment_id
                                   WHERE appointment_user.user_id = ?";
        $this->execute($queryDeleteAppointment, 'i', $userId);
    }
    
}