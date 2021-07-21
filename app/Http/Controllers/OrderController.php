<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Expense;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Setting;
use Barryvdh\DomPDF\Facade as PDF;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class OrderController extends Controller
{
    public function update_sales(Request $request){

        $subtotal = 0;
        $total = 0;
        $unique_id = uniqid(20);
        $quantity = $request->get("quantity");
        $id = $request->get("id");

        foreach ($quantity as $key => $value){
            Order::where("id", $id[$key])->update(['quantity' => $value]);
        }

        Toastr::success('Order Successfully Created', 'Success!!!');
        return redirect()->route('admin.sales.pending');
    }
    public function edit_sales($id){
        $order = Order::where('order_details_id', $id)->first();
         $customer = Order::join("order_details", "order_details.unique_id", "=", "orders.unique_id")->where('orders.order_details_id', $id)->first();;
         $sales = Order::where('order_details_id', $id)->get();;
        $products = Product::all();

        return view("admin.order.edit", compact('order', "sales", "products", "customer"));
    }
    public function installment(){
        $installment = Order::latest()->with('customer')->where('payment_status', 'installment')->get();
        $payment = "installment";
        return view('admin.order.payment_method', compact('installment', 'payment'));
    }
    public function full_payment(){
        $payment = "full_payment";
        $installment = Order::latest()->with('customer')->where('payment_status', 'full_payment')->get();
        return view('admin.order.payment_method', compact('installment','payment'));
    }
    public function completePayment(Request $request){

        $pay = Order::where('id' , $request->get('id'))->value('pay');
        $total = Order::where('id' , $request->get('id'))->value('total');
        $pay = $pay + $request->get('amount');
        if ($pay < $total){
            return redirect()->back()->with("msg", "Payment is Not Complete yet. Complete payment to continue.");
        }
        Order::where('id' , $request->get('id'))->update(['bal' => $request->get('amount'), 'due' => $pay, 'payment_status' => 'full_payment']);
        return redirect()->back()->with("msg", "Payment Completed Successfully. ");

    }
    public function complete_payment($id){
        $customers = Customer::all();

        return view('admin.order.complete_payment', compact(['customers', 'id']));
    }
    public function storeSales(Request $request){

        $request->validate([
            'pay' => 'required'
        ],[
            'pay.required' => 'Amount paid is required'
        ]);
        
        $pay = $request->get("pay");
        $quantity = $request->get('quantity');
        $products = $request->get("product");
        $payment_method = $request->get("payment_method");
        $customer = $request->get('customer');
        $price = $request->get('price');
        $this->make_order($products, $quantity, $payment_method, $pay, $pay, $request, $customer, $price);
        Toastr::success('Order Successfully Created', 'Success!!!');
        return redirect()->route('admin.sales.pending');
    }
    public function store(Request $request){

        // $request->validate([
        //     'payment_method' => 'required'
        // ],[
        //     'payment_method.required' => 'Payment Method is required'
        // ]);
        $payment_method = $request->get("payment_method");

        $pay = $request->get("pay");
        $quantity = $request->get('quantity');
        $cust_products = $request->get("product");
        $payment_method = $request->get("payment_method");
        $customer = $request->get('customer');
        $price = $request->get('price');
        $products = Product::all();

        return view("admin.order.confirm_order", compact("pay", "quantity", "products", "cust_products","payment_method", "customer", "price"));

    }
    public function make_order($products, $quantity, $payment_method, $pay, $due, $request, $customer, $price){

        $total = 0;
        $unique_id = uniqid(20);

        $order_id =  OrderDetail::create([
            'unique_id' => $unique_id,
            "amount_paid" => $pay,
            'customer_id' => $customer
        ])->id;
        foreach ($products as $key => $value){
            $selling_price = Product::where('id', $products[$key])->value("selling_price");
            $total = $quantity[$key] * $price[$key];

             Order::create([
                 'product_id' => $products[$key],
                 'order_details_id' => $order_id,
                'unique_id' => $unique_id,
                'order_status' => 'pending',
                'unit_price' => $price[$key],
                 'quantity' => $quantity[$key],
                 'total' => $total,
                'payment_status' => $payment_method
            ]);
        }
    }
    public function create_order(){
        $customers = Customer::all();
        $products = Product::all();
        return view('admin.order.create', compact(['customers', 'products']));
    }
    public function show($id)
    {
          $orders = Order::where('order_details_id', $id)->get();
        //return $order;
       $order_details = OrderDetail::with("customer")->where('id', $id)->first();

        //return $order_details;
        $company = Setting::latest()->first();
        return view('admin.order.order_confirmation', compact('order_details', 'orders', 'company', 'id'));
    }


    public function pending_order()
    {
      $pendings = Order::join('order_details', 'order_details.id', '=', 'orders.order_details_id')->orderBy("order_details.created_at", "DESC")->get();
        return view('admin.order.pending_orders', compact('pendings'));
    }

    public function approved_order()
    {
        $approveds = Order::latest()->with('customer')->where('order_status', 'approved')->get();
        return view('admin.order.approved_orders', compact('approveds'));
    }

    public function order_confirm($id)
    {
        $order = Order::findOrFail($id);
        $order->order_status = 'approved';
        $order->save();

        Toastr::success('Order has been Approved! Please delivery the products', 'Success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        
        Order::findOrFail($id)->delete();
        OrderDetail::findOrFail($id)->delete();
        Toastr::success('Order has been deleted', 'Success');
        return redirect()->back();
    }

    public function download($order_id)
    {
        $order = Order::with('customer')->where('id', $order_id)->first();
        //return $order;
        $order_details = OrderDetail::with('product')->where('order_id', $order_id)->get();
        //return $order_details;
        $company = Setting::latest()->first();

        set_time_limit(300);

        $pdf = PDF::loadView('admin.order.pdf', ['order'=>$order, 'order_details'=> $order_details, 'company'=> $company]);

        $content = $pdf->download()->getOriginalContent();

        Storage::put('public/pdf/'.$order->customer->name .'-'. str_pad($order->id,9,"0",STR_PAD_LEFT). '.pdf' ,$content) ;

        Toastr::success('PDF successfully saved', 'Success');
        return redirect()->back();

    }

    // for sales report
    public function today_sales()
    {
         $today = date('Y-m-d');
        $today = Carbon::today();
        
         $balance = Order::whereDate('created_at', $today)->get();

        $orders = DB::table('orders')
            ->join('order_details', 'orders.order_details_id', '=', 'order_details.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->select('orders.quantity', 'orders.unit_price', 'orders.total', 'products.selling_price', 'products.name AS product_name', 'products.image', 'order_details.*')
            // ->select('customers.name as customer_name','orders.quantity', 'orders.unit_price', 'orders.total', 'products.selling_price', 'products.name AS product_name', 'products.image', 'order_details.*')
            ->orderBy('order_details.created_at', 'desc')
            ->whereDate('order_details.created_at', '=', $today->format('Y-m-d'))
            ->get();

        return view('admin.sales.today', compact('orders', 'balance'));
    }

    public function monthly_sales($month = null)
    {

        if ($month == null)
        {
            $month = date('m');
        } else {
            $month = date('m', strtotime($month));
        }

        $balance = Order::whereMonth('created_at', $month)->get();


        $orders = DB::table('orders')
            ->join('order_details', 'orders.order_details_id', '=', 'order_details.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            
            ->select('orders.quantity', 'orders.unit_price', 'orders.total', 'products.selling_price', 'products.name AS product_name', 'products.image', 'order_details.*')
            ->orderBy('order_details.created_at', 'desc')
            ->whereMonth('order_details.created_at', $month)
            ->get();

        return view('admin.sales.month', compact('orders', 'month', 'balance'));
    }

    public function total_sales()
    {
        $balance = Order::all();


        $orders = DB::table('orders')
            ->join('order_details', 'orders.order_details_id', '=', 'order_details.id')
            ->join('products', 'orders.product_id', '=', 'products.id')
            // ->join('customers', 'order_details.customer_id', '=', 'customers.id')
            ->select('orders.quantity', 'orders.unit_price', 'orders.total', 'products.selling_price', 'products.name AS product_name', 'products.image', 'order_details.*')
            ->orderBy('order_details.created_at', 'desc')
            ->get();

        return view('admin.sales.index', compact('balance', 'orders'));
    }


}
