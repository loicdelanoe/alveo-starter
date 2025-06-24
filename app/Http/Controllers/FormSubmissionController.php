<?php

namespace App\Http\Controllers;

use App\Concerns\hasDynamicValidation;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormSubmissionController extends Controller
{
    use hasDynamicValidation;

    public function index(Form $form)
    {
        $submissions = FormSubmission::where('form_id', $form->id)
            ->orderBy('submitted_at', 'desc')
            ->paginate(8);

        return Inertia::render('Admin/FormSubmission/Index', compact('form', 'submissions'));
    }

    public function show(Form $form, FormSubmission $submission)
    {
        dd($submission);

        // return Inertia::render('Admin/FormSubmission/Show', compact('form', 'submission'));
    }

    public function store(Request $request, Form $form)
    {
        $validated = $this->validateForm($request->all(), $form);

        FormSubmission::create([
            'form_id' => $form->id,
            'data' => $validated,
            'submitted_at' => now(),
        ]);
    }
}
