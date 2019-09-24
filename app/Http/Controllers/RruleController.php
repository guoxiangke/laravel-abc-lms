<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Rrule;
use Illuminate\Http\Request;
use App\Forms\RruleForm as CreateForm;
// use App\Repositories\RruleRepository;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\Edit\RruleForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class RruleController extends Controller
{
    use FormBuilderTrait;

    /**
     * @var UserRepository
     */
    // protected $repository;

    public function __construct()
    {
        // $this->repository = $repository;
        $this->middleware(['admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $limit = 10;
        // $columns = ['*'];
        // $rrules = $this->repository->paginate($limit, $columns);
        $rrules = Rrule::with(
            'order',
            'order.user',
            'order.user.profiles',
            'order.teacher',
            'order.teacher.profiles',
            'order.agency',
            'order.agency.profiles',
            )
            ->orderBy('id', 'desc')
            ->paginate(100);

        return view('rrules.index', compact('rrules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Order $order)
    {
        $form = $this->form(CreateForm::class, [
            'method' => 'POST',
            'url'    => action('RruleController@store'),
        ], ['entity' => $order]);

        return view('rrules.create', compact('form'));
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

        //rrule && rrule_repeated
        $rrule = $request->input('string');
        $rruleReslovedArray = Rrule::buildRrule($rrule);
        $rrule = Rrule::firstOrCreate(
            array_merge($rruleReslovedArray, [
                'order_id' => $request->input('order_id'),
                'type'     => $request->input('type') ?: 0, //'AOL','SCHEDULE', Rrule::TYPE_SCHEDULE
            ])
        );
        if ($rrule->wasRecentlyCreated) {
            Session::flash('alert-success', __('Success'));
        } else {
            Session::flash('alert-danger', '已存在相同的计划');
        }

        return redirect()->route('rrules.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rrule  $rrule
     * @return \Illuminate\Http\Response
     */
    public function show(Rrule $rrule)
    {
        dd($rrule->toArray());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rrule  $rrule
     * @return \Illuminate\Http\Response
     */
    public function edit(Rrule $rrule)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('RruleController@update', ['id'=>$rrule->id]),
            ],
            ['entity' => $rrule],
        );

        return view('rrules.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rrule  $rrule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rrule $rrule, FormBuilder $formBuilder)
    {
        $form = $this->form(EditForm::class);
        // dd($rrule->toArray(),$form->isValid(),$form->getErrors());
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        //https://stackoverflow.com/questions/1809494/post-unchecked-html-checkboxes
        $start_at = $request->input('start_at');
        $start_at = Carbon::createFromFormat('Y-m-d\TH:i', $start_at); //2019-04-09T06:00
        $string = $request->input('string');
        $rrule = $rrule->fill(compact('start_at', 'string'));
        $rrule->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('rrules.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rrule  $rrule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rrule $rrule)
    {
        //
    }
}
