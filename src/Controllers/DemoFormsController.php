<?php

namespace BalajiDharma\LaravelAdmin\Controllers;

use BalajiDharma\LaravelAdmin\Controllers\Controller;
use BalajiDharma\LaravelFormBuilder\FormBuilder;

class DemoFormsController extends Controller
{
    protected $title = 'Laravel Form Builder Demo';

    public function index(FormBuilder $formBuilder)
    {

        $title = $this->title;

        return view('laravel-admin::demo.forms.index', compact('formBuilder', 'title'));
    }

    public function store()
    {
        return redirect()->route('laravel-admin::demo.forms.index');
    }
}
