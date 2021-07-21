@extends('layouts.backend.app')

@section('title', 'Add Expense')

@push('css')

@endpush
<style>

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
                            <form role="form" action="{{ url('admin/sales/store-sales') }}" method="post">
                                <input type="hidden" name="customer" value="{{$customer}}">
                                <input type="hidden" name="payment_method" value="{{$payment_method}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <!-- <div class="form-group">
                                                <label>Customer</label> -->
{{--                                                        <option value="{{$customer->id}}">{{$customer}}</option>--}}
                                            <!-- </div> -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Payment Method</label>
                                                    <div class="form-control">
                                                        {{$payment_method}}

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="full-payment" class="full-payment">
                                                <div class="form-group">
                                                    <label>Amount Paid</label>
                                                    <input type="number" name="pay" class="form-control" value="{{ old('pay') }}" placeholder="Enter Amount">
                                                    @if($errors->first())
                                                        <span style="font-size: 12px; color: red">{{ $errors->first('pay') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div id="wrapper"  class="col-md-12">
                                                <div id="new-field" class="col-md-12">
                                                    <?php $total = 0; ?>
                                                    @foreach($quantity as $qty => $value)
                                                    <div class="row">

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Product Name</label>
                                                                <select name="product[]" id="product" class="form-control">
                                                                    <option value="">Select Product Name</option>
                                                                    @foreach($products as $product)
                                                                        <option value="{{$product->id}}"  {{ ( $product->id == $cust_products[$qty] ) ? "selected" : "" }}  name="product[]">{{$product->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Quantity</label>
                                                                <input type="text" name="quantity[]" class="form-control" value="{{ $value }}" placeholder="Enter Quantity">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Unit Price</label>
                                                                <input type="text" name="price[]" value="{{$price[$qty]}}" id="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Total</label>
                                                                <div class="form-control">
                                                                {{$price[$qty] * $value}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                        <?php $total += $price[$qty] * $value ?>

                                                    @endforeach
                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            Total
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-control">
                                                                {{ $total }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary float-md-right">Confirm Sales</button>
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
        });
    </script>
@endpush
