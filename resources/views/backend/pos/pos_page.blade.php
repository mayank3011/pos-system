@extends('admin_dashboard')
@section('admin')
@php
    use Gloudemans\Shoppingcart\Facades\Cart;
    // Retrieve the current cart contents
    $contents = Cart::content();
    $allcart = Cart::content();
    // Group cart items by book group (using the 'book_group' option if available)
    $groupedItems = $contents->groupBy(function($item) {
        return $item->options->book_group ?? 'Ungrouped';
    });
@endphp
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<div class="content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">POS</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">POS</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Cart & Invoice Section -->
            <div class="row">
    <div class="col-lg-6 col-xl-6">
        <div class="card text-center">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered border-primary mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>QTY</th>
                                <th>Price</th>
                                <th>SubTotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allcart as $cart)
                                <tr>
                                    <td>{{ $cart->name }}</td>
                                    <td>
                                        <form method="post" action="{{ url('/cart-update/'.$cart->rowId) }}">
                                            @csrf
                                            <input type="number" name="qty" value="{{ $cart->qty }}" style="width:40px;" min="1">
                                            <button type="submit" class="btn btn-sm btn-success" style="margin-top:-2px;">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $cart->price }}</td>
                                    <td>{{ $cart->price * $cart->qty }}</td>
                                    <td>
                                        <a href="{{ url('/cart-remove/'.$cart->rowId) }}">
                                            <i class="fas fa-trash-alt" style="color:#ffffff"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-primary mt-3 p-3">
                    <br>
                    <p style="font-size:18px; color:#fff">Quantity : {{ Cart::count() }}</p>
                    <p style="font-size:18px; color:#fff">SubTotal : {{ Cart::subtotal() }}</p>
                    <p style="font-size:18px; color:#fff">Vat : {{ Cart::tax() }}</p>
                    <p>
                        <h2 class="text-white">Total</h2>
                        <h1 class="text-white">{{ Cart::total() }}</h1>
                    </p>
                    <br>
                </div>

                <br>
                <form id="myForm" method="post" action="{{ url('/create-invoice') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="customer_id" class="form-label">All Customer</label>
                        <a href="{{ route('add.customer') }}" class="btn btn-primary rounded-pill waves-effect waves-light mb-2">Add Customer</a>
                        <select name="customer_id" class="form-select" id="example-select">
                            <option selected disabled>Select Customer</option>
                            @foreach($customer as $cus)
                                <option value="{{ $cus->id }}">{{ $cus->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-blue waves-effect waves-light">Create Invoice</button>
                </form>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col-->

            <!-- Products Section with Category and Group Filter -->
            <div class="col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Filter Dropdowns -->
                        <div class="row mb-3">
                            <!-- Category Filter -->
                            <div class="col-md-4">
                                <select id="categoryFilter" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (request()->get('category_id') == $cat->id) ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Group Filter -->
                            <div class="col-md-4">
                                <select id="groupFilter" class="form-select">
                                    <option value="">All Groups</option>
                                    @foreach($groups as $grp)
                                        <option value="{{ $grp->id }}" {{ (request()->get('group_id') == $grp->id) ? 'selected' : '' }}>
                                            {{ $grp->group_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Products Table -->
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Group</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product as $key => $item)
                                    <tr>
                                        <form method="post" action="{{ url('/add-cart') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <input type="hidden" name="name" value="{{ $item->product_name }}">
                                            <input type="hidden" name="qty" value="1">
                                            <input type="hidden" name="price" value="{{ $item->selling_price }}">
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <img src="{{ asset($item->product_image) }}" style="width:50px; height:40px;">
                                            </td>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $item->category?->category_name ?? 'Uncategorized' }}</td>
                                            <td>{{ $item->group?->group_name ?? 'No Group' }}</td>
                                            <td>
                                                <button type="submit" style="font-size:20px; color:#000;">
                                                    <i class="fas fa-plus-square"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card -->
            </div>
        </div> <!-- end row -->
    </div> <!-- container -->
</div> <!-- content -->

<script type="text/javascript">
    $(document).ready(function () {
        // When filters change, reload page with query parameters
        $('#categoryFilter, #groupFilter').on('change', function(){
            var categoryId = $('#categoryFilter').val();
            var groupId = $('#groupFilter').val();
            var url = "{{ url()->current() }}";
            var params = [];
            if(categoryId) {
                params.push("category_id=" + categoryId);
            }
            if(groupId) {
                params.push("group_id=" + groupId);
            }
            if(params.length) {
                window.location.href = url + "?" + params.join("&");
            } else {
                window.location.href = url;
            }
        });
    });
</script>
@endsection
