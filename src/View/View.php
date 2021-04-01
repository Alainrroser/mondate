<?php
    
    namespace App\View;
    
    class View {
        
        private $viewfile;
        
        private $properties = [];
        
        public function __construct($viewfile) {
            $this->viewfile = "./../templates/$viewfile.php";
        }
        
        public function __set($key, $value) {
            if(!isset($this->$key)) {
                $this->properties[$key] = $value;
            }
        }
        
        public function __get($key) {
            if(isset($this->properties[$key])) {
                return $this->properties[$key];
            }
            return null;
        }
        
        public function display() {
            extract($this->properties);
            
            require './../templates/header.php';
            require $this->viewfile;
            require './../templates/footer.php';
        }
        
    }
