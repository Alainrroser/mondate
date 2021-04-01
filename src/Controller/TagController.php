<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\Repository\TagRepository;
use App\Util\RequestUtils;
use App\View\JsonView;

class TagController {
    
    function index() {
        header("Location: /");
    }
    
    public function create() {
        Authentication::restrictAuthenticated();
        
        $name = RequestUtils::getPOSTValue("name");
        $color = RequestUtils::getPOSTValue("color");
        
        if(!empty($name) && !empty($color)) {
            // Find out whether this tag already exists
            $exists = false;
            $tagRepository = new TagRepository();
            $tags = $tagRepository->getAllTags();
            
            foreach($tags as $tag) {
                // The substring is required because the color from the browser starts with an "#"
                if($tag->getName() === $name || $tag->getColor() === substr($color, 1)) {
                    $exists = true;
                }
            }
            
            if(!$exists) {
                $tagId = $tagRepository->addTag($name, substr($color, 1));
                
                $response = [];
                $response['id'] = $tagId;
                $response['name'] = $name;
                $response['color'] = $color;
                
                $view = new JsonView();
                $view->setJsonObject($response);
                $view->display();
            }
        } else {
            echo "Invalid input";
        }
    }
    
    public function delete() {
        Authentication::restrictAuthenticated();
        
        $id = RequestUtils::getPOSTValue("id");
        
        if(!empty($id)) {
            $tagRepository = new TagRepository();
            $tagRepository->deleteById($id);
        } else {
            echo "Invalid input";
        }
    }
    
    public function get() {
        Authentication::restrictAuthenticated();
        
        $id = RequestUtils::getGETValue("id");
        
        $response = [];
        
        if(!empty($id)) {
            $tagRepository = new TagRepository();
            $tag = $tagRepository->readById($id);
            
            $response['name'] = $tag->name;
            $response['color'] = $tag->color;
        } else {
            $response['name'] = "Unknown";
            $response['color'] = "000000";
        }
        
        $view = new JsonView();
        $view->setJsonObject($response);
        $view->display();
    }
    
}