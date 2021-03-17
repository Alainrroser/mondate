<?php


namespace App\View;


class JsonView {
    private $jsonObject;

    public function getJsonObject() {
        return $this->jsonObject;
    }

    public function setJsonObject($jsonObject) {
        $this->jsonObject = $jsonObject;
    }

    public function display() {
        $response = json_encode($this->jsonObject);
        echo $response;
    }
}