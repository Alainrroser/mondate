<?php


namespace App\Repository;

use App\Model\User;

class UserRepository extends Repository {

    protected $tableName = 'user';

    private function convertRowToUser($row) {
        if(!$row) {
            return false;
        }

        return new User($row->id, $row->email, $row->password);;
    }

    public function signUp($email, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO $this->tableName (email, password) VALUES (?, ?)";

        return parent::executeAndGetInsertId($query, 'ss', $email, $passwordHash);
    }

    public function getById($id) {
        $query = "SELECT * FROM $this->tableName WHERE id = ?";
        $rows = $this->executeAndGetRows($query, 'i', $id);
        return sizeof($rows) > 0 ? $this->convertRowToUser($rows[0]) : null;
    }

    public function getByEmail($email) {
        $query = "SELECT * FROM $this->tableName WHERE email = ?";
        $rows = $this->executeAndGetRows($query, 's', $email);
        return sizeof($rows) > 0 ? $this->convertRowToUser($rows[0]) : null;
    }

    public function changePassword($id, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE $this->tableName SET password = ? WHERE id = ?";
        $this->execute($query, 'si', $passwordHash, $id);
    }

    public function getNonCreatorUsersForAppointment($appointment_id) {
        $query = "SELECT * FROM $this->tableName
                      JOIN appointment_user ON $this->tableName.id = appointment_user.user_id
                      JOIN appointment ON appointment_user.appointment_id = appointment.id
                      WHERE appointment_user.appointment_id = ? AND appointment.creator_id != $this->tableName.id";
        $rows = parent::executeAndGetRows($query, 'i', $appointment_id);

        $users = [];
        foreach($rows as $row) {
            $users[] = $this->convertRowToUser($row);
        }

        return $users;
    }

}