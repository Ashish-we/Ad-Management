<!-- resources/views/admin/customer/list.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Customer List</h3>
            <a href="{{route('exp.add')}}" class="btn btn-primary">Add New Exp</a>
        </div>
        <div class="card-body">
            <form action="{{route('search_exp')}}" method="get">
                @csrf
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by title" class="form-control">
                    <div style="background-color: grey;" class="input-group-append">
                        <button type="submit" class="btn">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </form>
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($exps as $exp)
                    <tr>
                        <td>{{ $exp->date }}</td>
                        <td>{{ $exp->title }}</td>
                        <td>{{ $exp->amount }}</td>
                        <td>{{ $exp->note }}</td>
                        <td>
                            <a href="{{ url('/admin/dashboard/exp/edit/'. $exp->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ url('/admin/dashboard/exp/delete/'. $exp->id) }}" method="get" style="display:inline;">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expence?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $exps->links('pagination::bootstrap-5')}}
        </div>
    </div>
</div>
@endsection