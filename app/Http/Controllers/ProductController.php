<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Forms\ProductForm as CreateForm;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\Edit\ProductForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    use FormBuilderTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::
            orderBy('id', 'desc')
            ->paginate(100);

        return view('products.index', compact('products'));
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
            'url'    => action('ProductController@store'),
        ]);

        return view('products.create', compact('form'));
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

        $product = Product::firstOrCreate([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'remark'      => $request->input('remark'),
            // 'image', //todo
            'remark' => $request->input('remark'),
        ]);
        alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $form = $this->form(
            EditForm::class,
            [
                'method' => 'PUT',
                'url'    => action('ProductController@update', ['id'=>$product->id]),
            ],
            ['entity' => $product],
        );

        return view('products.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, FormBuilder $formBuilder)
    {
        $this->validate($request, [
            'price'=> 'required|regex:/^\d*(\.\d{1,})?$/',
        ]);
        $form = $this->form(EditForm::class);
        if (! $form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }
        $data = $request->all();
        $product->fill($data)->save();
        alert()->toast(__('Success'), 'success', 'top-center')->autoClose(3000);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
