<?php
    
    
    namespace App\Controller;
    
    
    use App\Authentication\Authentication;
    use App\Repository\TagRepository;

    class TagController {
        function index() {
        
        }
        
        function createTag($name, $color) {
            Authentication::restrictAuthenticated();
    
            $tagRepository = new TagRepository();
            $tagRepository->addTag($_POST["name"], $_POST["color"]);
        }
        
        function editTag($id, $name, $color) {
            Authentication::restrictAuthenticated();
    
            $tagRepository = new TagRepository();
            $tagRepository->editTag($_POST["tagId"], $_POST["name"], $_POST["color"]);
        }
        
        function deleteTag($id) {
            Authentication::restrictAuthenticated();
    
            $tagRepository = new TagRepository();
            $tagRepository->deleteById($id);
        }
    }