@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-chair"></i> Edit a Table
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

            <form action="/management/table/{{$table->id}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="tableName" class="form-label">Table Name</label>
                    <input type="text" class="form-control" name="name" value="{{$table->name}}" id="tableName" placeholder="Table Name">
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary mb-3">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
