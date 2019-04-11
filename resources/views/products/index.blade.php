@extends('layouts.app')

@section('title', __('Products'))

@section('content')
<div class="container">
	<h1>{{__('Products')}}</h1>

  <div class="show-links">
      <a href="{{ route('home') }}" class="btn btn-outline-dark"><i class="fas fa-angle-left fa-large"></i> {{__('Go Back')}}</a>
      <a href="{{ route('products.create') }}" class="btn btn-outline-primary">{{__('Create')}}</a>
  </div>

    <div class="col-md-12 col-sm-12"> 
        <div class="table-responsive">
          <table class="table">
              <thead>
                <tr>
                	<th scope="col">#</th>
                	<th scope="col">Name</th>
                	<th scope="col">Description</th>
                	<th scope="col">Price</th>
                	<th scope="col">Image</th>
                	<th scope="col">Remark</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                    <tr id={{$product->id}}>
                      <th scope="row"><a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-dark text-uppercase">Edit</a></th>
                      <td data-label="Name">{{$product->name}}</td>
                      <td data-label="Description">{{$product->description}}</td>
                      <td data-label="Price">{{$product->price}}</td>
                      <td data-label="Image">{{$product->image}}</td>
                      <td data-label="Remark">{{$product->remark}}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
        {{ $products->onEachSide(1)->links() }}
    </div>
</div>
@endsection
