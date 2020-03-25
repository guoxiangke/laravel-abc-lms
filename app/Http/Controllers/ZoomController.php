<?php

namespace App\Http\Controllers;

use App\Forms\Edit\ZoomForm as EditForm;
use App\Forms\ZoomForm as CreateForm;
use App\Models\Zoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ZoomController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
        $this->middleware(['admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zooms = Zoom::with('teacher', 'teacher.user.profiles')
                    ->orderBy('id', 'desc')
                    ->paginate(100);

        return view('zooms.index', compact('zooms'));
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
            'url'    => action('ZoomController@store'),
        ]);

        return view('zooms.create', compact('form'));
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
        $zoom = Zoom::firstOrCreate(
            [
                'email'    => $request->input('email'),
                'password' => $request->input('password'),
                'pmi'      => str_replace(' ', '', $request->input('pmi')),
            ]
        );
        Session::flash('alert-success', __('Success'));

        return redirect()->route('zooms.index'); //todo last page! or order
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function show(Zoom $zoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function edit(Zoom $zoom)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('ZoomController@update', ['zoom'=>$zoom]),
            ],
            ['entity' => $zoom],
        );

        return view('zooms.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zoom $zoom, FormBuilder $formBuilder)
    {
        $form = $this->form(EditForm::class);
        // dd($rrule->toArray(),$form->isValid(),$form->getErrors());
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        //https://stackoverflow.com/questions/1809494/post-unchecked-html-checkboxes
        $data = $request->all();
        $zoom = $zoom->fill($data);
        // dd($rrule->toArray());
        $zoom->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('zooms.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zoom $zoom)
    {
        //
    }
}
