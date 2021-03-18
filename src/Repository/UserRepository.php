<?php
    
    
    namespace App\Repository;
    
    use App\Database\ConnectionHandler;
    use Exception;
    use stdClass;
    
    class UserRepository extends Repository {
        
        protected $tableName = 'user';
        
        /**
         * @param $email
         * @param $password
         *
         * @return int
         * @throws Exception
         */
        public function signUp($email, $password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO $this->tableName (email, password) VALUES (?, ?)";
            return parent::insertAndGetId($query, 'ss', $email, $passwordHash);
        }
        
        /**
         * @param $email
         *
         * @return object|stdClass
         * @throws Exception
         */
        public function readByEmail($email) {
            $query = "SELECT * FROM $this->tableName WHERE email = ?";
            
            $statement = ConnectionHandler::getConnection()->prepare($query);
            $statement->bind_param('s', $email);
            
            $statement->execute();
            $result = $statement->get_result();
            
            if(!$result) {
                throw new Exception($statement->error);
            }
            
            return $result->fetch_object();
        }

        public function changePassword($id, $password) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE $this->tableName SET password=? WHERE id=?";

            $statement = ConnectionHandler::getConnection()->prepare($query);
            $statement->bind_param("si", $password_hash, $id);

            $execution_result = $statement->execute();
            if(!$execution_result) {
                throw new Exception($statement->error);
            }
        }

        public function getNonCreatorUsersForAppointment($appointment_id) {
            $query = "SELECT * FROM $this->tableName
                      JOIN appointment_user ON $this->tableName.id = appointment_user.user_id
                      JOIN appointment ON appointment_user.appointment_id = appointment.id
                      WHERE appointment_user.appointment_id = ? AND appointment.creator_id != $this->tableName.id";

            return parent::executeAndGetRows($query, 'i', $appointment_id);
        }
    }