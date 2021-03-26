<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\AppointmentRepository;
    use App\Repository\TagRepository;
    use App\View\JsonView;
    
    class TagController {
        function index() {
            header("Location: /");
        }
        
        public function create() {
            Authentication::restrictAuthenticated();
            
            $exists = false;
            $tagRepository = new TagRepository();
            $rows = $tagRepository->readAll();
            foreach($rows as $row) {
                if($row->name === $_POST["name"] || $row->color === substr($_POST["color"], 1)) {
                    $exists = true;
                }
            }
            if(!$exists) {
                $tagId = $tagRepository->addTag($_POST["name"], substr($_POST["color"], 1));
    
                $response = [];
                $response['id'] = $tagId;
                $response['name'] = $_POST["name"];
                $response['color'] = $_POST["color"];
    
                $view = new JsonView();
                $view->setJsonObject($response);
                $view->display();
            }
        }
        
        public function edit() {
            Authentication::restrictAuthenticated();
            
            $tagRepository = new TagRepository();
            $tagRepository->editTag($_POST["id"], $_POST["name"], substr($_POST["color"], 1));
        }
    
        public function delete() {
            Authentication::restrictAuthenticated();
            
            $tagRepository = new TagRepository();
            $tagRepository->deleteById($_POST["id"]);
        }
    
        public function get() {
            $tagRepository = new TagRepository();
            $tag = $tagRepository->readById($_GET['id']);
            
            $response = [];
            $response['name'] = $tag->name;
            $response['color'] = $tag->color;
            
            $view = new JsonView();
            $view->setJsonObject($response);
            $view->display();
        }
    }