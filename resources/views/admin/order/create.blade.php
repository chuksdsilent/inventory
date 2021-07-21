@extends('layouts.backend.app')

@section('title', 'New Sales')

@push('css')

@endpush
<style>
    .full-payment, .installment{
        display: none;
    }
</style>
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 offset-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add New Sales</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add New Sales</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ url('admin/sales/store') }}" method="get">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <!-- <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Customer</label>
                                                <select name="customer" id="" class="form-control">
                                                    <option value="">Select Customer</option>
                                                    @foreach($customers as $customer)
                                                        <option value="1">None</option>
                                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div> -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Payment Method</label>
                                                <select name="payment_method" id="payment-method" class="form-control">
                                                    <option value="">Select Payment Method</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                </select>
                                                    <!-- @if($errors->first())
                                                        <span style="font-size: 12px; color: red">{{ $errors->first('payment_method') }}</span>
                                                    @endif -->
                                            </div>
                                        </div>
                                        <div class="col-md-12 right my-3">
                                            <a href="#" class="btn btn-danger" id="add-product">Add New Product</a>
                                        </div>
                                        <div id="wrapper"  class="col-md-12">
                                            <div id="new-field" class="col-md-12">
                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Product Name</label>
                                                            <select name="product[]" id="product" class="form-control">
                                                                <option value="">Select Product Name</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}" name="product[]">{{$product->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Quantity</label>
                                                            <input type="text" name="quantity[]" class="form-control" value="{{ old('quantity') }}" placeholder="Enter Quantity">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Unit Price</label>
                                                            <input type="text" name="price[]" class="form-control" value="{{ old('price') }}" placeholder="Enter Quantity">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Add Sales</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

@push('js')
    <script src="{{asset("js/axios.js")}}"></script>
<script>

    $("#add-product").click(function (e) {
        e.preventDefault();
        $("#new-field").clone().appendTo("#wrapper");
    });
    $("#payment-method").change(function () {
        $(".full-payment").css({"display": "block"});
    })
    $("#product").change(function (e) {
        e.preventDefault();
        alert(this.val());
    })
</script>
@endpush
