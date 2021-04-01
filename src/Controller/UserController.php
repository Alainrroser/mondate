<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;
use App\Repository\UserRepository;
use App\View\View;

class UserController {
    
    public function doSignUp() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $emailValid = isset($email) && !empty($email) &&
                      filter_var($email, FILTER_VALIDATE_EMAIL);
        
        $passwordValid = isset($password) && !empty($password) &&
                         preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-.]).{8,20}$/", $password);
        
        if($passwordValid && $emailValid) {
            $userRepository = new UserRepository();
            
            if(!$userRepository->getByEmail($email)) {
                $userRepository->signUp($email, $password);
                header("Location: /calendar");
            } else {
                http_response_code(412);
                
                $view = new View('signUp/index');
                $view->title = 'Sign Up';
                $view->errors = ["User already exists."];
                $view->background = true;
                $view->display();
            }
        } else {
            header("Location: /signUp");
        }
    }
    
    public function doSignIn() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if(Authentication::login($email, $password)) {
            header("Location: /calendar");
        } else {
            http_response_code(412);
            
            $view = new View('signIn/index');
            $view->title = 'Sign In';
            $view->errors = ["Incorrect password or non-existing user."];
            $view->background = true;
            $view->display();
        }
    }
    
    public function doChangePassword() {
        Authentication::restrictAuthenticated();
        
        $userRepository = new UserRepository();
        $user = Authentication::getAuthenticatedUser();
        if(password_verify($_POST["oldPassword"], $user->getPassword())) {
            if($_POST["oldPassword"] !== $_POST["password"]) {
                $userRepository->changePassword($_SESSION["userId"], $_POST["password"]);
                Authentication::logout();
            }
        } else {
            http_response_code(412);
            
            $view = new View('user/changePassword');
            $view->title = 'Change Password';
            $view->errors = ["The old password is not correct."];
            $view->background = true;
            $view->display();
        }
    }
    
    public function changePassword() {
        Authentication::restrictAuthenticated();
        
        $view = new View('user/changePassword');
        $view->title = 'Change Password';
        $view->errors = [];
        $view->background = true;
        $view->display();
    }
    
    public function delete() {
        Authentication::restrictAuthenticated();
        
        $userId = $_SESSION['userId'];
        
        $appointmentRepository = new AppointmentRepository();
        $appointmentRepository->deleteAppointmentsFromUser($userId);
        
        $userRepository = new UserRepository();
        $userRepository->deleteById($userId);
        
        Authentication::logout();
    }
    
    public function index() {
        Authentication::restrictAuthenticated();
        header("Location: /calendar");
    }
    
}
