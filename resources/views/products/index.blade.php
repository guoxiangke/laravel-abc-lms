@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container">
	<h1>Products</h1>

  <div class="show-links">
    <button type="button" class="btn btn-outline-primary"><a href="{{ route('products.create') }}">Create</a></button>
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
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $product)
                    <tr>
                      <th scope="row"><a href="#{{$product->id}}">{{$product->id}}</a></th>
                      <td data-label="Name">{{$product->name}}</td>
                      <td data-label="Description">{{$product->description}}</td>
                      <td data-label="Price">{{$product->price}}</td>
                      <td data-label="Image">{{$product->image}}</td>
                      <td data-label="Remark">{{$product->remark}}</td>
                      <td data-label="Action"><a href="{{ route('products.edit', $product->id) }}">Edit</a></td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
        {{ $products->onEachSide(1)->links() }}
    </div>
</div>
@endsection
