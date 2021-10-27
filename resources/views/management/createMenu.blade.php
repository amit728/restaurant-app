@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-hamburger"></i> Create a Menu
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

            <form action="/management/menu" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="menuName" class="form-label">Menu Name</label>
                    <input type="text" class="form-control" name="name" id="menuName" placeholder="Menu Name">
                </div>
                <div class="mb-3">
                    <label for="menuPrice" class="form-label">Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                        </div>
                        <input type="text" class="form-control" name="price" id="menuPrice" placeholder="Price">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="menuImage" class="form-label">Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="menuImage">
                        <label class="custom-file-label" for="menuImage">Choose file</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="menuDesc" class="form-label">Description</label>
                    <textarea class="form-control" name="description" id="menuDesc" rows="3" placeholder="Description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="menuCat" class="form-label">Choose Category</label>
                    <select class="form-control" name="category_id">
                            <option disabled selected>---Choose Category---</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary mb-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
