<!-- resources/views/admin/customer/update.blade.php -->

@extends('admin.layout.layout') <!-- Assuming you have a layout file, adjust as needed -->

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<div class="container mt-4">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Update Customer</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ url('/admin/dashboard/customer/edit/'. $customer->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Display Name</label>
                        <input type="text" class="form-control" id="display_name" value="{{ $customer->display_name }}" name="display_name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $customer->address }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection