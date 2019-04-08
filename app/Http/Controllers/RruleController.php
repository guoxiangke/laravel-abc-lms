<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\RruleForm;

use App\Repositories\RruleRepository;
use App\Models\Rrule;
use App\Models\Order;

class RruleController extends Controller
{
    use FormBuilderTrait;

    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(RruleRepository $repository) {
        $this->repository = $repository;
        $this->middleware(['admin']); // isAdmin 中间件让具备指定权限的用户才能访问该资源
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
            ->orderBy('id','desc')
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
        $form = $this->form(RruleForm::class, [
            'method' => 'POST',
            'url' => action('RruleController@store')
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
        $form = $formBuilder->create(RruleForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        //rrule && rrule_repeated
        $rrule = $request->input('string');
        $rruleReslovedArray = Rrule::buildRrule($rrule);
        $rrule = Rrule::firstOrCreate(
            array_merge($rruleReslovedArray,[
                'order_id' => $request->input('order_id'),
                'type' => $request->input('type')?:0,//'AOL','SCHEDULE', Rrule::TYPE_SCHEDULE
            ])
        );
        if($rrule->wasRecentlyCreated){
            flashy()->success('创建成功');
        }else{
            flashy()->error('已存在相同的计划');
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
            RruleForm::class, 
            [
                'method' => 'PUT',
                'url' => action('RruleController@update', ['id'=>$rrule->id])
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
        $form = $this->form(RruleForm::class);
        // dd($rrule->toArray(),$form->isValid(),$form->getErrors());
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        //https://stackoverflow.com/questions/1809494/post-unchecked-html-checkboxes
        $data = $request->all();
        if (!$request->input('type')){
            $data['type'] = false;
        }
        $rrule = $rrule->fill($data);
        // dd($rrule->toArray());
        $rrule->save();
        flashy()->success('Update Success');
        return redirect()->back();
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
