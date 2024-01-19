<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Invoice_Items;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{

    public function show($id)
    {
        try {
            $ad = Ad::findorFail($id);

            return view('downloadable.receipt', compact('ad'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function create_pdf($id)
    {
        try {
            $ad = Ad::findOrFail($id);

            // Pass data to the view
            $data = ['ad' => $ad];

            // Create a new instance of the PDF class
            $pdf = app('dompdf.wrapper');

            // Load the view and pass data to it
            $pdf->loadView('downloadable.receipt', $data);

            // Download the PDF file
            return $pdf->download('pdf_file.pdf');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function show_invoice($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoiceItems = Invoice_Items::where('invoice_id', $id)->get();
            $customer = Customer::where('phone', $invoice->customer)->first();
            return view('downloadable.invoice', compact('invoice'));
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }

    public function create_pdf_invoice($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice_items = Invoice_Items::where('invoice_id', $id)->get();
            $customer = Customer::where('phone', $invoice->customer)->first();
            // Pass data to the view

            $data = ['invoice' => $invoice];
            // dd("hello");
            // Create a new instance of the PDF class
            $pdf = app('dompdf.wrapper');
            // dd("hello");
            // Load the view and pass data to it
            $pdf->loadView('downloadable.invoice', $data);
            // dd("hello");
            // Download the PDF file
            return $pdf->download('pdfFile.pdf');
        } catch (\Throwable $th) {
            $th->getMessage();
        }
    }
}
