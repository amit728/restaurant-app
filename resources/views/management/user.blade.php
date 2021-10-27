@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-users-cog"></i> Users
            <a class="btn btn-sm btn-success float-right" href="user/create"><i class="fas fa-plus"></i> Create a users</a>
            <hr>
            @if(Session()->has('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{Session()->get('status')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
            @endif

            <table class="table border">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td><a href="/management/user/{{$user->id}}/edit" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td>
                                <form action="/management/user/{{$user->id}}" method="post">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" value="Delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </users>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
