@extends('layouts.backend.app')

@section('title', 'Add Expense')

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
                            <li class="breadcrumb-item active">Edit Sales</li>
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
                                <h3 class="card-title">Edit Sales</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ url('admin/sales/update') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Customer</label>
                                                <div class="form-control">

                                                    {{ \App\Customer::where("id", $customer->customer_id)->value("name") }}
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Payment Method</label>
                                                    <div class="form-control">
                                                        {{ ($order->payment_status == "cash") ? "Cash" : "Bank Transfer"}}
                                                    </div>

                                                </div>
                                            </div>
                                            <div id="wrapper"  class="col-md-12">
                                                <div id="new-field" class="col-md-12">
                                                    @foreach($sales as $sale)
                                                        <input type="hidden" name="id[]" value="{{$sale->id}}">

                                                        <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Product Name</label>
                                                                <div class="form-control">
                                                                    {{ \App\Product::where('id', $sale->product_id)->value("name") }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Quantity</label>
                                                                <input type="text" name="quantity[]" class="form-control" value="{{ $sale->quantity }}" placeholder="Enter Quantity">
                                                            </div>
                                                        </div>
                                                    </div>
                                                        @endforeach
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary float-md-right">Update Sales</button>
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
    <script>
        $("#add-product").click(function (e) {
            e.preventDefault();
            $("#new-field").clone().appendTo("#wrapper");
        });
        $("#payment-method").change(function () {
            $(".full-payment").css({"display": "block"});
        })
    </script>
@endpush
