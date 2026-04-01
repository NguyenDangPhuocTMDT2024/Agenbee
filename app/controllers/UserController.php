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
        if(isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id)
            ];
            $this->renderView('user/home', $data);
        } else {
            $this->renderView('user/home');
        }
    }
}