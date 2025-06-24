<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormRequest;
use App\Models\Form;
use Inertia\Inertia;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::paginate(8);

        return Inertia::render('Admin/Form/Index', compact('forms'));
    }

    public function create()
    {
        return Inertia::render('Admin/Form/Create');
    }

    public function store(StoreFormRequest $request)
    {
        $validated = $request->validated();

        Form::create($validated);

        return to_route('admin.form.index');
    }

    public function show(Form $form)
    {
        dd($form);
        // return Inertia::render('Admin/Form/Show', compact('form'));
    }

    public function destroy(Form $form)
    {
        $form->delete();

        return to_route('admin.form.index')->with('success', 'Form deleted successfully.');
    }
}
