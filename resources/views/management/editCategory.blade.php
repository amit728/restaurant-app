@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-align-justify"></i> Update a Category
            <hr>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/management/category/{{$category->id}}" method="POST">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label for="categoryInput1" class="form-label">Category Name</label>
                    <input type="text" class="form-control" value="{{ $category->name }}" name="name" id="categoryInput1" placeholder="Category Name">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary mb-3">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
