<?php


namespace App\Model;


use App\Util\DateTimeUtils;
use DateTime;

class Appointment {
    
    private $id;
    private $start;
    private $end;
    private $name;
    private $description;
    private $creatorId;
    private $tags;
    
    public function __construct($id, $start, $end, $name, $description, $creatorId) {
        $this->id = $id;
        $this->start = $start;
        $this->end = $end;
        $this->name = $name;
        $this->description = $description;
        $this->creatorId = $creatorId;
        $this->tags = [];
    }
    
    public function getId() {
        return $this->id;
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

    /**
     * Return the start date time in the browser's local timezone
     */
    public function getStartAsDateTime() {
        $utcDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->start);
        return DateTimeUtils::convertUTCToLocal($utcDateTime);
    }

    /**
     * Return the end date time in the browser's local timezone
     */
    public function getEndAsDateTime() {
        $utcDateTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->end);
        return DateTimeUtils::convertUTCToLocal($utcDateTime);
    }
    
}