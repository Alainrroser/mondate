<?php

namespace App\Controller;

use App\Authentication\Authentication;
use App\Repository\UserRepository;

class UserController
{
    const PASSWORD_PATTERN = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-\.]).{8,20}$";

    public function doSignUp()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userRepository = new UserRepository();
        $userRepository->signUp($email, $password);

        // Anfrage an die URI /calendar weiterleiten (HTTP 302)
        header('Location: /calendar/');
    }

    public function doSignIn()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $emailValid = isset($email) && !empty($email) &&
            filter_var($email, FILTER_VALIDATE_EMAIL);

        if ($emailValid && Authentication::login($email, $password)) {
            header("Location: /calendar/");
        } else {
            header("Location: /user/");
        }
    }
    
    public function doChangePassword() {
        $userRepository = new UserRepository();
        $userRepository->changePassword($_SESSION["userId"], $_POST["password"]);
        Authentication::logout();
    }
    
    public function changePassword() {
        $view = new View('user/changePassword');
        $view->title = 'Change Password';
        $view->display();
    }

    public function delete()
    {
        $userRepository = new UserRepository();
        $userRepository->deleteById($_GET['id']);

        // Anfrage an die URI /user weiterleiten (HTTP 302)
        header('Location: /user');
    }

    public function index()
    {
        Authentication::restrictAuthenticated();
        header("Location: /calendar");
    }
}
