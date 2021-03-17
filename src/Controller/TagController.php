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

        function edit($id, $name, $color) {
            Authentication::restrictAuthenticated();
    
            $tagRepository = new TagRepository();
            $tagRepository->editTag($_POST["tagId"], $_POST["name"], $_POST["color"]);
        }
        
        function delete($id) {
            Authentication::restrictAuthenticated();
    
            $tagRepository = new TagRepository();
            $tagRepository->deleteById($id);
        }
    }