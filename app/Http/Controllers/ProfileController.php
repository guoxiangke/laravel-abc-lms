<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Forms\ProfileForm as CreateForm;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\Edit\ProfileForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ProfileController extends Controller
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
        $profiles = Profile::with(
            'recommend',
            'user.student',
            'user.teacher',
            'user.agency',
         )->orderBy('id', 'desc')->paginate(50);

        return view('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if (! $user->isAdmin()) {
            $profile = $user->profiles->first();
            if ($profile) {
                // 跳转到编辑页面
                return $this->edit($profile);
            }
        }
        $form = $this->form(CreateForm::class, [
            'method' => 'POST',
            'url'    => action('ProfileController@store'),
        ]);

        return view('profiles.create', compact('form'));
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
        $profile = Profile::firstOrCreate(
            [
                'user_id' => $request->input('user_id'),
            ]
        );
        $profile = $profile->fill($request->except('user_id'))->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('profiles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(profile $profile)
    {
        $this->authorize('view', $profile);

        return view('profiles.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(profile $profile)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('ProfileController@update', ['id'=>$profile->id]),
            ],
            ['entity' => $profile],
        );

        return view('profiles.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, profile $profile, FormBuilder $formBuilder)
    {
        $form = $this->form(EditForm::class);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $profile = $profile->fill($request->all())->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('profiles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(profile $profile)
    {
        //
    }
}
