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
            <h3>Client List</h3>
        </div>
        <div class="card-body">
            <form action="{{route('search_client')}}" method="get">
                @csrf
                <div class="input-group">
                    <div class="col-md-3">
                        <label for="start_date">Search BY Customer Name</label>
                        <input type="text" name="search" placeholder="Search by customer name" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" value="0" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">End Date</label>
                        <input type="date" value="0" name="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <div><br></div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search fa-fw"></i> Search
                        </button>
                    </div>
                </div>
            </form>
            <br>
            <a href="{{ route('client.yesterday') }}" class="btn btn-secondary">Yesterday</a>
            <a href="{{ route('client.this_day') }}" class="btn btn-secondary">Today</a>
            <a href="{{ route('client.this_week') }}" class="btn btn-secondary">This Week</a>
            <a href="{{ route('client.this_month') }}" class="btn btn-secondary">This Month</a>
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Account</th>
                        <th>USD</th>
                        <th>Rate</th>
                        <th>NRP</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->account }}</td>
                        <td>{{ $client->USD }}</td>
                        <td>{{ $client->Rate }}</td>
                        <td>{{ $client->NRP }}</td>
                        <td>{{ $client->created_at->format('Y-m-d')}}</td>
                        <td>
                            <a href="{{ url('/admin/dashboard/client/edit/'. $client->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ url('/admin/dashboard/client/delete/'. $client->id) }}" method="get" style="display:inline;">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this client?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $clients->links('pagination::bootstrap-5')}}
        </div>
    </div>
</div>
@endsection