<?php

class UserController extends Controller
{
    public $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }
    public function home()
    {
        $this->renderView('user/home');
    }
}