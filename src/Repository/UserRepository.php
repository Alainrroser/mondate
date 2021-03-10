<?php


namespace App\Repository;

use App\Database\ConnectionHandler;
use Exception;
use stdClass;

class UserRepository extends Repository
{

    protected $tableName = 'user';

    /**
     * @param $email
     * @param $password
     * @return int
     * @throws Exception
     */
    public function signUp($email, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO $this->tableName (email, password) VALUES (?, ?)";
        $statement = ConnectionHandler::getConnection()->prepare($query);

        $statement->bind_param('ss', $email, $passwordHash);
        $executionResult = $statement->execute();

        if (!$executionResult) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

    /**
     * @param $email
     * @return object|stdClass
     * @throws Exception
     */
    public function get($email)
    {
        $query = "SELECT * FROM $this->tableName WHERE email = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $email);

        $statement->execute();
        $result = $statement->get_result();

        if (!$result) {
            throw new Exception($statement->error);
        }

        return $result->fetch_object();
    }

}