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
        </div>
        <div class="card-body">
            <form action="{{route('search_customer')}}" method="get">
                @csrf
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by customer name" class="form-control">
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
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->display_name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>
                            <a href="{{ url('/admin/dashboard/customer/edit/'. $customer->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ url('/admin/dashboard/customer/delete/'. $customer->id) }}" method="get" style="display:inline;">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer all the ads and invoices that are linked with this customer will be deleted?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $customers->links('pagination::bootstrap-5')}}
        </div>
    </div>
</div>
@endsection