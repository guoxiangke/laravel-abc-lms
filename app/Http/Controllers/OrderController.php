<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Rrule;
use App\Models\Student;
use App\Models\ClassRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Forms\OrderForm as CreateForm;
use Illuminate\Support\Facades\Session;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\Edit\OrderForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class OrderController extends Controller
{
    use FormBuilderTrait;

    public function __construct()
    {
        // $this->middleware(['admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);
        $orders = Order::with(
            'student',
            'student.profiles',
            'teacher.profiles',
            'agency.profiles',
            'classRecords',
        );
        $type = 'Default'; // 默认
        //0 订单作废 1 订单正常* 2 订单完成  3 订单暂停上课  4 订单过期
        // 试听订单页面
        if (request()->is('orders/trail')) {
            $type = 'Trial';
            $orders = $orders->where('period', 1);
        }
        // ！试听订单页面
        if ($request->is('orders/trash')) {
            $orders = $orders->where('period', '!=', 1)
                ->where('status', Order::STATU_TRASH);
            $type = 'Trash';
        }

        if ($request->is('orders/done')) {
            $orders = $orders->where('status', Order::STATU_COMPLETED)
                ->where('period', '!=', 1);
            $type = 'Done';
        }

        if ($request->is('orders/overdue')) {
            $orders = $orders->where('status', Order::STATU_OVERDUE)
                ->where('period', '!=', 1);
            $type = 'Overdue';
        }

        if ($request->is('orders/pause')) {
            $orders = $orders->where('status', Order::STATU_PAUSE);
            $type = 'Pause';
        }

        if ($request->is('orders/all')) {
            $type = 'All';
        }
        // 默认
        // 有效订单页面
        if ($type == 'Default') {
            $orDers = $orders->where('period', '!=', 1)
                ->where('status', Order::STATU_ACTIVE);
        }
        $orders = $orders->orderBy('id', 'desc'); //todo debug 第二页有N+1问题 /orders/done?page=1

        $orders = QueryBuilder::for($orders)
            // ->allowedIncludes(['student.profiles','student'])
            ->allowedFilters(['student.name', 'student.profiles.name'])
            ->paginate(100);

        return view('orders.index', compact('orders', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Order::class);

        $form = $this->form(CreateForm::class, [
            'method' => 'POST',
            'url'    => action('OrderController@store'),
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
        $this->authorize('create', Order::class);
        $this->validate($request, [
            'price'=> 'required|regex:/^\d*(\.\d{1,})?$/',
        ]);
        $form = $formBuilder->create(CreateForm::class);

        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $order = Order::firstOrNew([
            'student_uid'     => $request->input('student_uid'),
            'user_id'     => Auth::id(),
            'teacher_uid' => $request->input('teacher_uid') ?: 1,
            'agency_uid'  => $request->input('agency_uid') ?: 1,
            'book_id'     => $request->input('book_id') ?: 1,
            'product_id'  => $request->input('product_id'),
            'price'       => $request->input('price'),
            'period'      => $request->input('period'),
            'expired_at'  => $request->input('expired_at'),
            'remark'      => $request->input('remark'),
            'status'      => $request->input('status'),
        ]);
        if (isset($order->id)) {
            $order->price = $request->input('price'); //还原价格
        }
        $order->save();
        //rrule && rrule_repeated
        $rrules[] = $request->input('rrule');
        $rrules[] = $request->input('rrule_repeated');
        foreach ($rrules as $rrule) {
            // 如果没有填写第二个！
            if (is_null($rrule)) {
                continue;
            }
            $rruleReslovedArray = Rrule::buildRrule($rrule);
            Rrule::firstOrCreate([
                'string'   => $rruleReslovedArray['string'],
                'start_at' => $rruleReslovedArray['start_at'],
                'order_id' => $order->id,
                'type'     => Rrule::TYPE_SCHEDULE, //2个都是上课计划
            ]);
        }

        Session::flash('alert-success', __('Success'));

        if ($request->has('trail')) {
            return redirect()->route('orders.trail');
        }

        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     * @bug： 如果今日的课没有生成，日历表上会把今天的空出来，并显示多加1天的课
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $events = [];
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
            ->map(function ($startDateString) use (&$events) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $startDateString);
                //去除今日的 计划。 今日的0点生生了记录
                if ($start->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                    return;
                }
                $events[] = [
                    'start'   => $startDateString,
                    'end'     => $start->addMinutes(25)->format('Y-m-d H:i:s'),
                    'title'   => $start->subMinutes(25)->format('m/d H:i').'有课',
                    'class'   => 'schedule',
                ];
            });

        collect($order->reDiffAols())
            ->map(function ($startDateString) use (&$events) {
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $startDateString);
                $events[] = [
                    'start'   => $startDateString,
                    'end'     => Carbon::createFromFormat('Y-m-d H:i:s', $startDateString)->addMinutes(25)->format('Y-m-d H:i:s'),
                    'title'   => $start->format('m/d H:i').' 计划请假',
                    'class'   => 'aol',
                ];
            });

        $order->classRecords()//historyRecords
            ->each(function ($classRecord) use (&$events, $order) {
                $link = route('classRecords.index', $order->id);
                $now = Carbon::now()->format('Y-m-d');
                $isToday = $classRecord->generated_at->format('Y-m-d') == $now;
                $title = $isToday ? '⚠️今日有课' : '上课记录';
                if ($classRecord->exception) {
                    //学生老师/正常/异常请假
                    $title = ClassRecord::EXCEPTION_TYPES[$classRecord->exception];
                }
                $startDateString = $classRecord->generated_at->format('Y-m-d H:i:s');
                $events[] = [
                    'start'   => $startDateString,
                    'end'     => $classRecord->generated_at->addMinutes(25)->format('Y-m-d H:i:s'),
                    'title'   => $classRecord->generated_at->format('m/d H:i').$title,
                    'class'   => $isToday ? 'today' : 'history',
                ];
            });

        return view('orders.show', compact('order', 'events'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('OrderController@update', ['id' => $order->id]),
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
        $this->authorize('update', $order);
        $price = $order->price;
        if ($request->has('price')) {
            $this->validate($request, [
                'price'=> 'required|regex:/^\d*(\.\d{1,})?$/',
            ]);
            $price = $request->input('price');
        }
        $form = $this->form(EditForm::class);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $order->fill([
            'user_id'     => Auth::id(),
            'student_uid'     => $request->input('student_uid'),
            'teacher_uid' => $request->input('teacher_uid') ?: 1,
            'agency_uid'  => $request->input('agency_uid') ?: 1,
            'book_id'     => $request->input('book_id') ?: 1,
            'product_id'  => $request->input('product_id'),
            'price'       => $price,
            'period'      => $request->input('period'),
            'expired_at'  => $request->input('expired_at'),
            'remark'      => $request->input('remark'),
            'status'      => $request->input('status'),
        ])->save();

        $start_at = $request->input('start_at');
        $start_at = Carbon::createFromFormat('Y-m-d\TH:i', $start_at); //2019-04-09T06:00
        $string = $request->input('rrule');
        $order->rrules->first()->fill(compact('start_at', 'string'))->save();
        Session::flash('alert-success', __('Success'));

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
        $this->authorize('delete', $order);
    }

    public function flagStatus(Request $request, Order $order, $status)
    {
        $this->authorize('flag', $order);
        $order->status = $status;

        return ['success'=>$order->save()];
    }
}
