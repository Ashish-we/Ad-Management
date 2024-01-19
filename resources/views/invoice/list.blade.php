<!-- resources/views/list.blade.php -->

@extends('admin.layout.layout')

@section('content')
<div class="container">
    <h2>Invoice List</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice Number</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->customer }}</td>

                <?php



                $items = \DB::table('invoice__items')->where('invoice_id', $invoice->id)->get();
                $t_amount = 0;
                foreach ($items as $item) {
                    $t_amount = $t_amount + $item->amount;
                }

                ?>

                <td>{{ $t_amount }}</td>
                <td>
                    <a href="{{ URL('admin/dashboard/invoice/update/'. $invoice->id) }}" class="btn btn-primary" onclick="return confirm('Are you sure you want to update this invoice?')">Edit</a>
                    <!-- Add delete button if needed -->
                    <!-- Example: -->
                    <form action="{{ URL('admin/dashboard/invoice/delete/'. $invoice->id) }}" method="post" style="display:inline;">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="hello" value="hello">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</button>
                    </form>
                </td>
                <td><a href="{{ URL('/invoice/show_invoice/'. $invoice->id) }}" class="btn btn-primary">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection