<?php
    
    namespace App\Controller;
    
    use App\Repository\UserRepository;
    use App\View\View;
    
    /**
     * Siehe Dokumentation im DefaultController.
     */
    class UserController {
        public function doCreate() {
            if(isset($_POST['send'])) {
                $firstName = $_POST['fname'];
                $lastName = $_POST['lname'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                
                $userRepository = new UserRepository();
                $userRepository -> create($firstName, $lastName, $email, $password);
            }
            
            // Anfrage an die URI /user weiterleiten (HTTP 302)
            header('Location: /user');
        }
        
        public function delete() {
            $userRepository = new UserRepository();
            $userRepository -> deleteById($_GET['id']);
            
            // Anfrage an die URI /user weiterleiten (HTTP 302)
            header('Location: /user');
        }
        
        public function index() {
            $view = new View('user/index');
            $view -> title = 'Sign In';
            $view -> display();
        }
        
        public function signUp() {
            $view = new View('user/signUp');
            $view -> title = 'Sign Up';
            $view -> display();
        }
    }
