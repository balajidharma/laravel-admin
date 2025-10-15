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

        return view('admin.demo.forms.index', compact('formBuilder', 'title'));
    }

    public function store()
    {
        return redirect()->route('admin.demo.forms.index');
    }
}
