<?php


namespace App\Repository;

use App\Model\Tag;

class TagRepository extends Repository {
    
    protected $tableName = 'tag';
    
    private function convertRowsToTags($rows) {
        $tags = [];
        foreach($rows as $row) {
            $tags[] = new Tag($row->id, $row->name, $row->color);
        }
        
        return $tags;
    }
    
    public function getAllTags() {
        $query = "SELECT * FROM $this->tableName";
        $rows = $this->executeAndGetRows($query);
        
        return $this->convertRowsToTags($rows);
    }
    
    public function getTagsFromAppointment($appointmentId) {
        $query = "SELECT * FROM $this->tableName
                  JOIN appointment_tag ON $this->tableName.id = appointment_tag.tag_id
                  WHERE appointment_tag.appointment_id = ?";
        $rows = $this->executeAndGetRows($query, 'i', $appointmentId);
        
        return $this->convertRowsToTags($rows);
    }
    
    public function getTagsFromUser($userId) {
        $query = "SELECT * FROM appointment_user
                  JOIN appointment_tag ON appointment_user.appointment_id = appointment_tag.appointment_id
                  JOIN tag ON appointment_tag.tag_id = tag.id
                  WHERE appointment_user.user_id = ?
                  GROUP BY tag.id";
        $rows = $this->executeAndGetRows($query, 'i', $userId);
        
        return $this->convertRowsToTags($rows);
    }
    
    public function addTag($name, $color) {
        $query = "INSERT INTO $this->tableName (name, color) VALUES (?, ?)";
        return $this->executeAndGetInsertId($query, 'ss', $name, $color);
    }
    
    public function editTag($id, $name, $color) {
        $query = "UPDATE $this->tableName SET name=?, color=? WHERE id=?";
        $this->execute($query, "ssi", $name, $color, $id);
    }
    
}