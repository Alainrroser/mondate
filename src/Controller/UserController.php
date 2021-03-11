<?php
    
    namespace App\Controller;
    
    use App\Authentication\Authentication;
    use App\Repository\UserRepository;
    use App\View\View;
    
    class UserController {
        public function doSignUp() {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $userRepository = new UserRepository();
            $userRepository->signUp($email, $password);
            
            // Anfrage an die URI /calendar weiterleiten (HTTP 302)
            header('Location: /calendar/');
        }
        
        public function doSignIn() {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $emailValid = isset($email) && !empty($email) &&
                          filter_var($email, FILTER_VALIDATE_EMAIL);
            
            if($emailValid && Authentication::login($email, $password)) {
                header("Location: /calendar/");
            } else {
                header("Location: /user/");
            }
        }
        
        public function doChangePassword() {
            Authentication::restrictAuthenticated();

            $userRepository = new UserRepository();
            $user = Authentication::getAuthenticatedUser();
            if(password_verify($_POST["oldPassword"], $user->password)) {
                $userRepository->changePassword($_SESSION["userId"], $_POST["password"]);
                Authentication::logout();
            } else {
                header("Location: /user/changePassword");
            }
        }
        
        public function changePassword() {
            Authentication::restrictAuthenticated();

            $view = new View('user/changePassword');
            $view->title = 'Change Password';
            $view->display();
        }
        
        public function delete() {
            Authentication::restrictAuthenticated();

            $userRepository = new UserRepository();
            $userRepository->deleteById($_SESSION['userId']);
            
            Authentication::logout();
        }
        
        public function index() {
            Authentication::restrictAuthenticated();
            header("Location: /calendar");
        }
    }
