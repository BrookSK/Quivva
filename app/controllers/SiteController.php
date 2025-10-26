<?php
namespace App\controllers;

use App\core\Controller;

class SiteController extends Controller
{
    public function home(): void
    {
        $this->view('site/home', []);
    }

    public function plans(): void
    {
        $this->view('site/plans', []);
    }

    public function contact(): void
    {
        $this->view('site/contact', []);
    }
}
