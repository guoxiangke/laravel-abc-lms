<?php

namespace App\Http\Controllers;

use App\Models\Zoom;
use App\Models\Teacher;
use App\Forms\ZoomForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    use FormBuilderTrait;

    public function __construct() {
        $this->middleware(['admin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zooms = Zoom::with('teacher','teacher.user.profile')
                    ->orderBy('id','desc')
                    ->paginate(10);
        return view('zooms.index', compact('zooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = $this->form(ZoomForm::class, [
            'method' => 'POST',
            'url' => action('ZoomController@store')
        ]); 
        return view('zooms.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zoom = Zoom::firstOrCreate(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'pmi' => str_replace(' ', '', $request->input('pmi')),
            ]
        );
        flashy()->success('创建成功');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zoom $zoom)
    {
        //
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
