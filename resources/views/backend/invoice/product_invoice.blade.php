@extends('admin_dashboard')
@section('admin')

<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Customer Invoice</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Customer Invoice</h4>
                </div>
            </div>
        </div>     
        <!-- End Page Title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo & Title -->
                        <div class="clearfix">
                            <div class="float-start">
                                <div class="auth-logo">
                                    <span class="logo-lg">
                                        <img src="{{ asset('backend/assets/images/logo-dark.png') }}" alt="Logo" height="22">
                                    </span>
                                </div>
                            </div>
                            <div class="float-end">
                                <h4 class="m-0 d-print-none">Invoice</h4>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p><b>Hello, {{ $customer->name }}</b></p>
                            </div>
                            <div class="col-md-4 offset-md-2">
                                <div class="mt-3 float-end">
                                    <p><strong>Order Date: </strong> <span class="float-end">{{ date('d-F-Y') }}</span></p>
                                    <p><strong>Order Status: </strong> <span class="float-end"><span class="badge bg-danger">Unpaid</span></span></p>
                                    <p><strong>Invoice No.: </strong> <span class="float-end">000028</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="row">
                            <div class="col-sm-6">
                                <h6>Billing Address</h6>
                                <address>
                                    {{ $customer->address }} - {{ $customer->city }}<br>
                                    <abbr title="Shop Name">Shop Name:</abbr> {{ $customer->shopname }}<br>
                                    <abbr title="Phone">Phone:</abbr> {{ $customer->phone }}<br>
                                    <abbr title="Email">Email:</abbr> {{ $customer->email }}<br>
                                </address>
                            </div>
                        </div>

                        <!-- Order Details Table -->
                        <div class="table-responsive">
                            <table class="table mt-4 table-centered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th style="width: 10%">Qty</th>
                                        <th style="width: 10%">Unit Cost</th>
                                        <th style="width: 10%" class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $sl = 1; @endphp
                                    @foreach($contents as $item)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td><b>{{ $item->name }}</b></td>
                                        <td>{{ $item->qty }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">${{ number_format($item->price * $item->qty, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Invoice Summary -->
                        <div class="row">
                            <div class="col-sm-6">
                                <h6 class="text-muted">Notes:</h6>
                            </div>
                            <div class="col-sm-6 text-end">
                                <p><b>Sub-total:</b> <span class="float-end">${{ Cart::subtotal() }}</span></p>
                                <p><b>Vat (21%):</b> <span class="float-end">${{ Cart::tax() }}</span></p>
                                <h3><b>${{ Cart::total() }} USD</b></h3>
                            </div>
                        </div>

                        <!-- Print & Create Invoice Buttons -->
                        <div class="mt-4 mb-1 text-end">
                            <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer me-1"></i> Print</a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#invoice-modal">Create Invoice</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- container -->
</div> <!-- content -->

<!-- Invoice Modal -->
<div id="invoice-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3>Invoice Of {{ $customer->name }}</h3>
                <h3>Total Amount: ${{ Cart::total() }}</h3>

                <form method="post" action="{{ url('/final-invoice') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_status" class="form-select">
                            <option selected disabled>Select Payment</option>
                            <option value="HandCash">HandCash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Due">Due</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pay Now</label>
                        <input class="form-control" type="text" name="pay" placeholder="Enter amount">
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <input type="hidden" name="order_date" value="{{ date('d-F-Y') }}">
                    <input type="hidden" name="order_status" value="pending">
                    <input type="hidden" name="total_products" value="{{ Cart::count() }}">
                    <input type="hidden" name="sub_total" value="{{ Cart::subtotal() }}">
                    <input type="hidden" name="vat" value="{{ Cart::tax() }}">
                    <input type="hidden" name="total" value="{{ Cart::total() }}">

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" type="submit">Complete Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
