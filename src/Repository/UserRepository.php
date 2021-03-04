<?php


namespace App\Repository;

use App\Database\ConnectionHandler;
use Exception;

class UserRepository
{
    protected $tableName = 'user';

    public function create($email, $password)
    {
        $query = "INSERT INTO $this->tableName (email, password) VALUES (?, ?)";
        $statement = ConnectionHandler::getConnection()->prepare($query);

        $statement->bind_param('ss', $email, $password);
        $execution_result = $statement->execute();

        if (!$execution_result) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    public function get($email, $password)
    {
        $query = "SELECT * FROM $this->tableName WHERE email == ? AND password == $password";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('ss', $email, $password);

        $statement->execute();
        $result = $statement->get_result();

        if (!$result) {
            throw new Exception($statement->error);
        }

        return $result->fetch_object();
    }

}