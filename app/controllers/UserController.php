<?php

class UserController extends Controller
{
    private $userModel;
    private $packageModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->packageModel = new Package();
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
    public function showPackage()
    {
        if(isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id),
                'combos' => $this->packageModel->getComboPackages(),
                'addons' => $this->packageModel->getAddonPackages(),
                'addonTypes' => $this->packageModel->getAllAddonType()
            ];
            $this->renderView('user/packages/index', $data);
        } else {
            $this->renderView('user/packages/index');
        }    
    }
    //show thông tin liên hệ
    public function showContact()
    {
        if(isLoginStrict($this->userModel)) {
            $id = getSession('user_id');
            $data = [
                'user' => $this->userModel->getUserById($id)
            ];
            $this->renderView('user/contact', $data);
        } else {
            $this->renderView('user/contact');
        }    
    }
}