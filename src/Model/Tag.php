<?php


namespace App\Model;


class Tag {
    
    private $id;
    private $name;
    private $color;
    
    public function __construct($id, $name, $color) {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getColor() {
        return $this->color;
    }
    
}