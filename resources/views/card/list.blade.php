<!-- resources/views/admin/customer/list.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header" style="display: inline-flex;">
                <h3>Card List</h3>
                <div>
                    <a class="btn btn-primary" href="{{route('card.add')}}" style=" margin-left:80%;display: inline-flex;">AddNew</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('search_card')}}" method="get">
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Card Number</th>
                            <th>USD</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cards as $card)
                        <tr>
                            <td>{{ $card->name }}</td>
                            <td>{{ $card->card_number }}</td>
                            <td>{{ $card->USD }}</td>
                            <td>
                                <a href="{{ url('/admin/dashboard/card/edit/'. $card->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <!-- <a href="{{ url('/admin/dashboard/card/delete/'. $card->id) }}" class="btn btn-danger btn-sm">Delete</a> -->
                                <form action="{{ url('/admin/dashboard/card/delete/'. $card->id) }}" method="get" style="display:inline;">
                                    @csrf
                                    @method('GET')
                                    <button class="btn btn-danger btn-sm" type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Card this will delete all the credit and debit performed on this card?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $cards->appends(request()->query())->links('pagination::bootstrap-5')}}
            </div>
        </div>
    </div>
</div>
@endsection