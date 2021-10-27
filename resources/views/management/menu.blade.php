@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @include('management\inc\sidebar')
        <div class="col-md-8">
            <i class="fas fa-hamburger"></i> Menus
            <a class="btn btn-sm btn-success float-right" href="menu/create"><i class="fas fa-plus"></i> Create Menu</a>
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
                    <th scope="col">Menu</th>
                    <th scope="col">Price</th>
                    <th scope="col">Image</th>
                    <th scope="col">Category</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->price }}</td>
                            <td><img src="{{asset('menu_images')}}/{{$menu->image}}" alt="{{ $menu->name }}" height="24"></td>
                            <td>{{ $menu->category->name }}</td>
                            <td><a href="/management/menu/{{$menu->id}}/edit" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a></td>
                            <td>
                                <form action="/management/menu/{{$menu->id}}" method="post">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" value="Delete" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                                </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
