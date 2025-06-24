<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FormController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Form/Index');
    }

    public function create()
    {
        return Inertia::render('Admin/Form/Create');
    }
}
