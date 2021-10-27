@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-user"></i> Edit a User
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

            <form action="/management/user/{{$user->id}}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="userName" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="{{$user->name}}" id="userName" placeholder="Name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{$user->email}}" id="email" placeholder="Email">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" value="{{$user->password}}" id="password" placeholder="Password">
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Choose Role</label>
                    <select class="form-control" id="role" name="role">
                            <option disabled selected>---Choose Category---</option>
                            <option value="admin" {{$user->role == 'admin' ? 'selected' : ''}}>Admin</option>
                            <option value="cashier" {{$user->role == 'cashier' ? 'selected' : ''}}>Cashier</option>
                    </select>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary mb-3">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
