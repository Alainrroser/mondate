<?php
    
    
    namespace App\Repository;
    
    use App\Database\ConnectionHandler;
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
            $query = "
        SELECT * FROM $this->tableName AS a
        JOIN appointment_user AS au ON a.id = au.appointment_id
        WHERE au.user_id = ?
        ";
            
            return parent::executeAndGetRows($query, 'i', $user_id);
        }
        
    }