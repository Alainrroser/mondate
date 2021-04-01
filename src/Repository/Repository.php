<?php

namespace App\Repository;

use App\Database\ConnectionHandler;
use Exception;

class Repository {
    
    protected $tableName = null;
    
    public function readById($id) {
        $query = "SELECT * FROM {$this->tableName} WHERE id=?";
        
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);

        $statement->execute();

        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $row = $result->fetch_object();

        $result->close();

        return $row;
    }
    
    public function readAll($max = 100) {
        $query = "SELECT * FROM {$this->tableName} LIMIT 0, $max";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->execute();

        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        $rows = [];
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }
    
    public function deleteById($id) {
        $query = "DELETE FROM {$this->tableName} WHERE id=?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('i', $id);

        if (!$statement->execute()) {
            throw new Exception($statement->error);
        }
    }

    protected function bindStatementParams($query, $types, ...$vars) {
        $typeArray = str_split($types);
        for ($i = 0; $i < sizeof($typeArray); $i++) {
            $type = $typeArray[$i];

            if ($type == 'i') {
                if (!is_numeric($vars[$i])) {
                    throw new Exception("Invalid statement parameter type");
                }
            }
        }

        $statement = ConnectionHandler::getConnection()->prepare($query);

        if (isset($types) && isset($vars)) {
            $statement->bind_param($types, ...$vars);
        }

        return $statement;
    }
    
    protected function execute($query, $types = null, ...$vars) {
        $statement = self::bindStatementParams($query, $types, ...$vars);
        $executionResult = $statement->execute();

        if(!$executionResult) {
            throw new Exception($statement->error);
        }
    }
    
    protected function executeAndGetRows($query, $types = null, ...$vars) {
        $statement = self::bindStatementParams($query, $types, ...$vars);

        $statement->execute();
        $result = $statement->get_result();

        if (!$result) {
            throw new Exception($statement->error);
        }

        $rows = [];
        while ($row = $result->fetch_object()) {
            $rows[] = $row;
        }

        return $rows;
    }
    
    protected function executeAndGetInsertId($query, $types = null, ...$vars) {
        $statement = self::bindStatementParams($query, $types, ...$vars);
        $executionResult = $statement->execute();

        if(!$executionResult) {
            throw new Exception($statement->error);
        }

        return $statement->insert_id;
    }

}
