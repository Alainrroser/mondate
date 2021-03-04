<?php
    
    namespace App\Authentication;
    
    use App\Repository\UserRepository;
    use RuntimeException;
    
    class Authentication {
        public static function login($email, $password) {
            // Den Benutzer anhand der E-Mail oder des Benutzernamen auslesen
            $userRepository = new UserRepository();
            $user = $userRepository -> get($email);
            
            if($user != null) {
                // Prüfen ob der Password-Hash dem aus der Datenbank entspricht
                if(password_verify($password, $user->password)) {
                    session_unset();
                    session_destroy();
                    session_start();
                    $_SESSION["userId"] = $user -> id;
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
        
        public static function isAuthenticated() {
            return isset($_SESSION['userId']);
        }
        
        public static function getAuthenticatedUser() {
            $userRepository = new UserRepository();
            $userId = $_SESSION["userId"];
            return $userRepository -> readById($userId);
        }
        
        public static function restrictAuthenticated() {
            if(!self ::isAuthenticated()) {
                header('Location: /user/');
            }
        }
    }