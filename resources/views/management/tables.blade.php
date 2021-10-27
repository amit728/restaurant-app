@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-chair"></i> Tables
            <a class="btn btn-sm btn-success float-right" href="table/create"><i class="fas fa-plus"></i> Create a Table</a>
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
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody> @foreach($tables as $table)
                        <tr>
                            <td>{{ $table->id }}</td>
                            <td>{{ $table->name }}</td>
                            <td>{{ $table->status }}</td>
                            <td><a href="/management/table/{{$table->id}}/edit" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td>
                                <form action="/management/table/{{$table->id}}" method="post">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" value="Delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $tables->links() }}
        </div>
    </div>
</div>
@endsection
