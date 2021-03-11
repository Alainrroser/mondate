<?php


namespace App\Model;


class Appointment {

    private $id;
    private $date;
    private $start;
    private $end;
    private $name;
    private $description;
    private $creatorId;
    private $tags;

    public function __construct($id, $date, $start, $end, $name, $description, $creatorId) {
        $this->id = $id;
        $this->date = $date;
        $this->start = $start;
        $this->end = $end;
        $this->name = $name;
        $this->description = $description;
        $this->creatorId = $creatorId;
        $this->tags = array();
    }

    public function getId() {
        return $this->id;
    }

    public function getDate() {
        return $this->date;
    }

    public function getStart() {
        return $this->start;
    }

    public function getEnd() {
        return $this->end;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCreatorId() {
        return $this->creatorId;
    }

    public function addTag($tag) {
        $this->tags[] = $tag;
    }

    public function getTags() {
        return $this->tags;
    }

}