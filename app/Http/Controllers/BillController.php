<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bill;
use Illuminate\Http\Request;
use App\Forms\BillForm as CreateForm;
use Illuminate\Support\Facades\Session;
use App\Forms\Edit\BillForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Spatie\QueryBuilder\QueryBuilder;

class BillController extends Controller
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
        $bills = Bill::with(
            'user.profiles',
            'user.teacher.paymethod',
            'order.student.profiles',
            'order.teacher.profiles',
            'order.agency.profiles'
        );
        $bills = $bills
            ->orderBy('status')
            ->orderBy('created_at', 'desc');
        $bills = QueryBuilder::for($bills)
            ->allowedFilters(['status', 'type', 'user.name', 'user.profiles.name'])
            ->paginate(100);
        return view('bills.index', compact('bills'));
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
            'url'    => action('BillController@store'),
        ]);

        return view('bills.create', compact('form'));
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
            'price'=> 'required|regex:/^\d*(\.\d{1,})?$/',
        ]);
        $form = $formBuilder->create(CreateForm::class);

        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $bill = new Bill;
        $postData = $request->all();
        $postData['status'] = $request->input('status') ?: 0;
        $postData['created_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('created_at'));
        $bill->fill($postData)->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('bills.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        return view('bills.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('BillController@update', ['id'=>$bill->id]),
            ],
            ['entity' => $bill],
        );

        return view('bills.edit', compact('form', 'bill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill, FormBuilder $formBuilder)
    {
        $this->validate($request, [
            'price'=> 'required|regex:/^\d*(\.\d{1,})?$/',
        ]);
        $form = $this->form(EditForm::class);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $postData = $request->all();
        $postData['status'] = $request->input('status') ?: 0;
        $postData['created_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $request->input('created_at'));
        $bill->fill($postData)->save();
        Session::flash('alert-success', __('Success'));

        return redirect()->route('bills.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();
        Session::flash('alert-success', '删除成功！');

        return redirect()->route('bills.index');
    }
}
