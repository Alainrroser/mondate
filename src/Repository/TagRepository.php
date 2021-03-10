<?php


namespace App\Repository;

use Exception;

class TagRepository extends Repository
{
    protected $tableName = 'tag';

    /**
     * @return array
     * @throws Exception
     */
    public function getTags() {
        $query = "SELECT * FROM $this->tableName";
        return parent::executeAndGetRows($query);
    }

    /**
     * @param $appointment_id
     * @return array
     * @throws Exception
     */
    public function getTagsForAppointment($appointment_id)
    {
        $query = "
        SELECT * FROM $this->tableName
        JOIN appointment_tag ON $this->tableName.id = appointment_tag.tag_id
        WHERE appointment_tag.appointment_id = ?
        ";

        return parent::executeAndGetRows($query, 'i', $appointment_id);
    }

}