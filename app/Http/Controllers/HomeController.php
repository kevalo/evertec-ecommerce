<?php

namespace App\Http\Controllers;


use App\ViewModels\HomeViewModel;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Welcome', new HomeViewModel());
    }
}
