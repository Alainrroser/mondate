<?php


namespace App\Repository;

use Exception;

class TagRepository extends Repository {
    protected $tableName = 'tag';

    /**
     * @param $appointment_id
     *
     * @return array
     * @throws Exception
     */
    public function getTagsForAppointment($appointment_id) {
        $query = "
                    SELECT * FROM $this->tableName
                    JOIN appointment_tag ON $this->tableName.id = appointment_tag.tag_id
                    WHERE appointment_tag.appointment_id = ?
                    ";

        return parent::executeAndGetRows($query, 'i', $appointment_id);
    }
    
    public function addTag($name, $color) {
        $query = "INSERT INTO $this->tableName (name, color) VALUES (?, ?)";
        return parent::insertAndGetId($query, 'ss', $name, $color);
    }
    
    public function editTag($id, $name, $color) {
        $query = "UPDATE $this->tableName SET name=?, color=? WHERE id=?";
        $this->execute($query, "ssi", $name, $color, $id);
    }

}