@extends('layouts.default')
@section('title','Home')
@section('content')



@foreach ($product as $item)




<div class="card mb-3" style="max-width: 540px;">
    <div class="row g-0">
      <div class="col-md-4">
        <img src="{{asset('productimages/'.$item->image)}}" class="img-fluid rounded-start" height="100" width="100" alt="...">
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title">{{$item->productname}}</h5>
          <p class="card-text">{{$item->description}}</p>
          <p class="card-text">{{$item->category->name}}</p>
          <p class="card-text"><small class="text-muted">{{$item->created_at}}</small></p>
        </div>
      </div>
    </div>
  </div>
  @endforeach


@stop
