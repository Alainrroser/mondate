<?php


namespace App\Repository;

use App\Database\ConnectionHandler;
use Exception;

class UserRepository
{
    protected $tableName = 'user';

    public function create($email, $password)
    {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->tableName (email, password) VALUES (?, ?)";
        $statement = ConnectionHandler::getConnection()->prepare($query);

        $statement->bind_param('ss', $email, $password_hash);
        $execution_result = $statement->execute();

        if (!$execution_result) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function get($email)
    {
        $query = "SELECT * FROM $this->tableName WHERE email == ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ss', $email);

        $statement->execute();
        $result = $statement->get_result();

        if (!$result) {
            throw new Exception($statement->error);
        }

        return $result->fetch_object();
    }

}