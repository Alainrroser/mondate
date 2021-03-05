<?php


namespace App\Repository;

use App\Database\ConnectionHandler;
use Exception;

class AppointmentRepository extends Repository
{
    protected $tableName = 'appointment';

    public function get_appointments_for_user($user_id)
    {
        $query = "
        SELECT * FROM appointment AS a
        JOIN appointment_user AS au ON a.id = au.appointment_id
        WHERE au.user_id = ?;
        ";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $user_id);

        $statement->execute();
        $result = $statement->get_result();

        if (!$result) {
            throw new Exception($statement->error);
        }

        $rows = array();
        while($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }

}