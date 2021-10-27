@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-align-justify"></i> Category
            <a class="btn btn-sm btn-success float-right" href="category/create"><i class="fas fa-plus"></i> Create Category</a>
            <hr>

            @if(Session()->has('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{Session()->get('status')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            @endif

            <table class="table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td><a href="/management/category/{{$category->id}}/edit" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td>
                                <form action="/management/category/{{$category->id}}" method="post">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" value="Delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
