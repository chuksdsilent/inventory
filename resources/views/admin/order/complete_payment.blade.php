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
                            <li class="breadcrumb-item active">Complete Payment</li>
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
                            <div class="alert-section">
                                @if(Session::has("msg"))
                                    <div class="alert alert-primary">{{Session::get('msg')}}</div>
                                @endif
                            </div>
                            <div class="card-header">
                                <h3 class="card-title">Complete Payment</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ url('admin/order/complete-payment') }}" method="post">
                                @csrf
                                <input type="hidden" value="{{$id}}" name="id">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div id="wrapper"  class="col-md-12">
                                                <div id="new-field" class="col-md-12">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Amount</label>
                                                                <input type="number" name="amount" id="amount" value="{{old("amount")}}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary float-md-right">Complete Payment</button>
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
        $("#payment-method").change(function () {
            $(".full-payment").css({"display": "block"});
        })
    </script>
@endpush
