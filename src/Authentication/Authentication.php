<?php

namespace App\Authentication;

use App\Repository\UserRepository;

class Authentication {
    public static function login($email, $password) {
        $userRepository = new UserRepository();
        $user = $userRepository->getByEmail($email);
        
        if($user != null) {
            if(password_verify($password, $user->getPassword())) {
                session_unset();
                session_destroy();
                session_start();
                $_SESSION["userId"] = $user->getId();
                return true;
            }
        }
        
        return false;
    }
    
    public static function logout() {
        session_unset();
        session_destroy();
        header("Location: /");
    }
    
    public static function getAuthenticatedUser() {
        $userRepository = new UserRepository();
        $userId = $_SESSION["userId"];
        return $userRepository->getById($userId);
    }
    
    public static function restrictAuthenticated() {
        if(!self::isAuthenticated()) {
            header('Location: /signIn/');
        }
    }
    
    public static function isAuthenticated() {
        return isset($_SESSION['userId']);
    }
}