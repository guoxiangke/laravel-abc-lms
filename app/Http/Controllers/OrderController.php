<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\User;
use App\Models\Agency;
use App\Models\Student;
use App\Models\Contact;
use App\Models\Profile;
use App\Models\PayMethod;
use App\Models\Rrule;
use Carbon\Carbon;
use App\Models\ClassRecord;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\OrderForm as CreateForm;
use App\Forms\Edit\OrderForm as EditForm;

class OrderController extends Controller
{
    use FormBuilderTrait;

    public function __construct() {
        $this->middleware(['admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with(
            'user', 'user.profiles',
            'teacher', 'teacher.profiles',
            'agency', 'agency.profiles',
            'schedules',
            'schedules.classRecords',
            'book',
            'product',
        )
        ->orderBy('id','desc')
        ->paginate(100);//todo debug 第二页有N+1问题
        return view('orders.index', compact('orders'));
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
            'url' => action('OrderController@store')
        ]); 
        return view('orders.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $this->validate($request, [
            'price'=>'required|regex:/^\d*(\.\d{1,})?$/',
        ]);
        $form = $formBuilder->create(CreateForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $order = Order::firstOrNew([
            'user_id' => $request->input('user_id'),//student_uid
            'teacher_uid' => $request->input('teacher_uid')?:1,
            'agency_uid' =>  $request->input('agency_uid')?:1,
            'book_id' =>  $request->input('book_id')?:1 ,
            'product_id' => $request->input('product_id'),
            'price' => $request->input('price'),
            'period' => $request->input('period'),
            'expired_at' => $request->input('expired_at'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);
        if(isset($order->id)){
            $order->price = $request->input('price'); //还原价格
        }
        $order->save();
        //rrule && rrule_repeated
        $rrules[] = $request->input('rrule');
        $rrules[] = $request->input('rrule_repeated');
        foreach ($rrules as $rrule) {
            // 如果没有填写第二个！
            if(is_null($rrule)) {
                continue;
            }
            $rruleReslovedArray = Rrule::buildRrule($rrule);
            Rrule::firstOrCreate([
                'string' => $rruleReslovedArray['string'],
                'start_at' => $rruleReslovedArray['start_at'],
                'order_id' => $order->id,
                'type' => Rrule::TYPE_SCHEDULE,//2个都是上课计划
            ]);
        }

        alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */

    public function show(Order $order)
    {
        $events = [];
        // dd($order->hasClassToday());
        if(!$order->isActive()){
            //todo
            // return redirect()->back()->with('message', 'order is not Active.');
        }
        // $order->load('user');
        // $order->load('user.profiles');

        // dd(
        //     // Rrule::find(1)->classRecords()->exceptions()->get()->toArray(),//->exceptions()->get()->toArray(),
        //     $order->regenAolsSchedule(),
        // //     // $order->reDiffAols(),
        // //     // $order->historyRecords(),
        // //     // $order->getAllAols(), 
        // //     // $order->getDiffAols(),
        //     $order->regenRruleSchedule(),
        // );

        collect($order->regenRruleSchedule())
            ->map(function($startDateString) use (&$events){
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $startDateString);
                //去除今日的 计划。 今日的0点生生了记录
                if($start->format('Y-m-d') ==  Carbon::now()->format('Y-m-d') ){
                    return;
                }
                $events[] = [
                    'start' =>  $startDateString,
                    'end' =>  $start->addMinutes(25)->format('Y-m-d H:i:s'),
                    'title' =>  $start->subMinutes(25)->format('m/d H:i'). '有课',
                    'icon' =>  'lunch',
                    'class' =>  'schedule',
                    'content' =>  '<i class="v-icon material-icons">directions_run</i>',
                    // 'background' =>  true,
                    'contentFull' =>  'My shopping list is rather long:<br><ul><li>Avocadoes</li><li>Tomatoes</li><li>Potatoes</li><li>Mangoes</li></ul>',
                ];
            });

        collect($order->reDiffAols())
            ->map(function($startDateString) use (&$events){
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $startDateString);
                $events[] = [
                    'start' =>  $startDateString,
                    'end' =>  Carbon::createFromFormat('Y-m-d H:i:s', $startDateString)->addMinutes(25)->format('Y-m-d H:i:s'),
                    'title' =>  $start->format('m/d H:i') . ' 计划请假',
                    'icon' =>  'lunch',
                    'class' =>  'aol',
                    'content' =>  '<i class="v-icon material-icons">directions_run</i>',
                    // 'background' =>  true,
                    'contentFull' =>  'My shopping list is rather long:<br><ul><li>Avocadoes</li><li>Tomatoes</li><li>Potatoes</li><li>Mangoes</li></ul>',
                ];
            });

        $order->classRecords()//historyRecords
            ->each(function($classRecord) use (&$events, $order){
                $link = route('classRecords.index', $order->id);
                $now = Carbon::now()->format('Y-m-d ');
                $isToday = $classRecord->generated_at->format('Y-m-d ') == $now;
                $title = $isToday?'⚠️今日有课':'上课记录';
                if($classRecord->exception){
                    //学生老师/正常/异常请假
                    $title = ClassRecord::EXCEPTION_TYPES[$classRecord->exception];
                }
                $events[] = [
                    'start' =>  $classRecord->generated_at->format('Y-m-d H:i:s'),
                    'end' =>  $classRecord->generated_at->addMinutes(25)->format('Y-m-d H:i:s'),
                    'title' => $classRecord->generated_at->format('m/d H:i') . $title,
                    'icon' =>  'lunch',
                    'class' => $isToday?'today':'history',
                    'content' =>  '<i class="v-icon material-icons">directions_run</i>',
                    // 'background' =>  true,
                    'contentFull' =>  'My shopping list is rather long:<br><ul><li>Avocadoes</li><li>Tomatoes</li><li>Potatoes</li><li>Mangoes</li></ul>',
                ];
            });
        
        // dd($events);
        // $default_events = json_encode($events);
        return view('orders.show', compact('order','events'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $form = $this->form(
            EditForm::class, 
            [
                'method' => 'PUT',
                'url' => action('OrderController@update', ['id' => $order->id])
            ],
            ['entity' => $order],
        ); 
        return view('orders.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order, FormBuilder $formBuilder)
    {
        $this->validate($request, [
            'price'=>'required|regex:/^\d*(\.\d{1,})?$/',
        ]);
        $form = $this->form(EditForm::class);
        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $order->fill([
            'user_id' => $request->input('user_id'),//student_uid
            'teacher_uid' => $request->input('teacher_uid')?:1,
            'agency_uid' =>  $request->input('agency_uid')?:1,
            'book_id' =>  $request->input('book_id')?:1 ,
            'product_id' => $request->input('product_id'),
            'price' => $request->input('price'),
            'period' => $request->input('period'),
            'expired_at' => $request->input('expired_at'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ])->save();
        
        $start_at = $request->input('start_at');
        $start_at = Carbon::createFromFormat('Y-m-d\TH:i', $start_at);//2019-04-09T06:00
        $string = $request->input('rrule');
        $order->rrules->first()->fill(compact('start_at','string'))->save();
        alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);
        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
