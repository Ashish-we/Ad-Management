@extends('admin.layout.layout')

@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<form action="{{route('search_user')}}" method="get">
    @csrf
    <div style="width: 80%;" class="input-group ml-5">
        <input type="text" name="search" placeholder="Search by customer name" class="form-control">
        <div style="background-color: grey;" class="input-group-append">
            <button type="submit" class="btn">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</form>

<table class="ml-5 mr-5 table">
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td>
                <a href="{{URL('/admin/dashboard/user/edit/' . $user->id)}}" class="btn btn-primary">Edit</a>
                <a href="{{URL('/admin/dashboard/user/delete/' . $user->id )}}" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger">Delete</a>
                <a href="{{URL('/admin/dashboard/user/privilege/'. $user->id)}}" class="btn btn-warning">Edit Privilege</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $users->appends(request()->query())->links('pagination::bootstrap-5')}}


@endsection