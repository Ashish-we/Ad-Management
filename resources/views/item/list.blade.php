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
        <div class="card-header" style="display: inline-flex;">
            <h3>Item List</h3>
            <div>
                <a class="btn btn-primary" href="{{route('item.add')}}" style=" margin-left:80%;display: inline-flex;">AddNew</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('search_item')}}" method="get">
                @csrf
                <div class="input-group">
                    <input type="text" name="search" placeholder="Search by item name" class="form-control">
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
                        <th>Unit</th>
                        <th>Selling Price</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->selling_price }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <a href="{{ url('/admin/dashboard/item/edit/'. $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ url('/admin/dashboard/item/delete/'. $item->id) }}" method="get" style="display:inline;">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item, this will cause the removal of the item from the invoices which contains this items and those invoices which contains only this item will also be deleted, so are you sure yo want to telete??')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $items->appends(request()->query())->links('pagination::bootstrap-5')}}
        </div>
    </div>
</div>
@endsection