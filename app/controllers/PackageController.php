<?php
//control package va package_items
class PackageController extends Controller 
{
    private $packageModel;
    public $viewPath = 'packages/';

    public function __construct()
    {
        $this->packageModel = new Package();
    }
    public function index() {
        
        $packages = $this->packageModel->getAllPackages();
        $this->RenderView($this->viewPath . 'index', ['packages' => $packages]);
    }
}