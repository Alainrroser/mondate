<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\AppointmentRepository;
use App\Repository\UserRepository;
use App\Util\RequestUtils;
use App\View\View;

const PASSWORD_PATTERN = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-.]).{8,20}$/";

class UserController {
    
    public function doSignUp() {
        $email = RequestUtils::getPOSTValue('email');
        $password = RequestUtils::getPOSTValue('password');
        
        $emailValid = !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
        $passwordValid = !empty($password) && preg_match(PASSWORD_PATTERN, $password);
        
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
            echo "Invalid input";
        }
    }
    
    public function doSignIn() {
        $email = RequestUtils::getPOSTValue('email');
        $password = RequestUtils::getPOSTValue('password');
        
        if(!empty($email) && !empty($password)) {
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
    }
    
    public function doChangePassword() {
        Authentication::restrictAuthenticated();
        
        $oldPassword = RequestUtils::getPOSTValue('oldPassword');
        $password = RequestUtils::getPOSTValue('password');
        
        if(!empty($oldPassword) && !empty($password)) {
            $userRepository = new UserRepository();
            $user = Authentication::getAuthenticatedUser();
            
            if(password_verify($oldPassword, $user->getPassword())) {
                if($oldPassword !== $password) {
                    $userRepository->changePassword($_SESSION["userId"], $password);
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
        } else {
            echo "Invalid input";
        }
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
