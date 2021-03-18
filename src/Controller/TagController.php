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

            $tagRepository = new TagRepository();
            $tagId = $tagRepository->addTag($_POST["name"], substr($_POST["color"], 1));

            $response = array();
            $response['id'] = $tagId;
            $response['name'] = $_POST["name"];
            $response['color'] = $_POST["color"];

            $view = new JsonView();
            $view->setJsonObject($response);
            $view->display();
        }

        function edit() {
            Authentication::restrictAuthenticated();
    
            $tagRepository = new TagRepository();
            $tagRepository->editTag($_POST["id"], $_POST["name"], substr($_POST["color"], 1));
        }
        
        function delete() {
            Authentication::restrictAuthenticated();
            
            $tagRepository = new TagRepository();
            $tagRepository->deleteById($_POST["id"]);
        }
    
        function get() {
            $tagRepository = new TagRepository();
            $tag = $tagRepository->readById($_GET['id']);
        
            $response = array();
            $response['name'] = $tag->name;
            $response['color'] = $tag->color;
        
            $view = new JsonView();
            $view->setJsonObject($response);
            $view->display();
        }
    
    }