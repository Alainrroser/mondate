<?php


namespace App\Controller;


use App\Authentication\Authentication;
use App\View\View;

class ChangePasswordController {

    public function index() {
        Authentication::restrictAuthenticated();

        $view = new View('user/changePassword');
        $view->title = 'Change Password';
        $view->errors = [];
        $view->background = true;
        $view->display();
    }

}