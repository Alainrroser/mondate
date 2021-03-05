<?php
    
    namespace App\Controller;
    
    use App\Authentication\Authentication;
    use App\Repository\UserRepository;
    use App\View\View;
    
    /**
     * Siehe Dokumentation im DefaultController.
     */
    class UserController {
        public function doSignUp() {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $userRepository = new UserRepository();
            $userRepository -> signUp($email, $password);
            
            // Anfrage an die URI /calendar weiterleiten (HTTP 302)
            header('Location: /calendar/');
        }
        
        public function doSignIn() {
            $email = $_POST['email'];
            $password = $_POST['password'];
            if(Authentication ::login($email, $password)) {
                header("Location: /calendar/");
            } else {
                header("Location: /user/");
            }
        }
        
        public function delete() {
            $userRepository = new UserRepository();
            $userRepository -> deleteById($_GET['id']);
            
            // Anfrage an die URI /user weiterleiten (HTTP 302)
            header('Location: /user');
        }
        
        public function index() {
            Authentication::restrictAuthenticated();
            header("Location: /calendar");
        }
    }
