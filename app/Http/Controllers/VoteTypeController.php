<?php

namespace App\Http\Controllers;

use App\Models\VoteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\VoteTypeForm as CreateForm;
use App\Forms\Edit\VoteTypeForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class VoteTypeController extends Controller
{
    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $voteTypes = VoteType::orderBy('id', 'desc')
            ->paginate(100);

        return view('voteTypes.index', compact('voteTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(CreateForm::class, [
            'method' => 'POST',
            'url'    => action('VoteTypeController@store'),
        ]);

        return view('voteTypes.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(CreateForm::class);

        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $zoom = VoteType::firstOrCreate(
            [
                'name'    => $request->input('name'),
                'description' => $request->input('description'),
                'type' => $request->input('type'),
                'votable_type' => $request->input('votable_type'),
            ]
        );
        Session::flash('alert-success', __('Success'));

        return redirect()->route('voteTypes.index'); //todo last page! or order
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoteType  $voteType
     * @return \Illuminate\Http\Response
     */
    public function edit(VoteType $voteType)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('VoteTypeController@update', ['id'=>$voteType->id]),
            ],
            ['entity' => $voteType],
        );

        return view('voteTypes.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoteType  $voteType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoteType $voteType, FormBuilder $formBuilder)
    {
        $form = $this->form(EditForm::class);
        // dd($rrule->toArray(),$form->isValid(),$form->getErrors());
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        //https://stackoverflow.com/questions/1809494/post-unchecked-html-checkboxes
        $data = $request->all();
        $voteType = $voteType->fill($data);
        // dd($rrule->toArray());
        $voteType->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('voteTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoteType  $voteType
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoteType $voteType)
    {
        //
    }
}
