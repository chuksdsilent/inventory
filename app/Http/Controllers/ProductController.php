<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Supplier;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function updateProduct(Request $request, Product $product, $id)
    {
        Product::where("id", $id)->update(['name' => $request->get('name'), 'price' => $request->get('price')]);

        return redirect()->back()->with("msg", "Updated Successfully.");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->with('category', 'supplier')->get();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.product.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->except('_token');

        $inputs = $request->except('_token');
        $rules = [
            'name' => 'required | min:3',
            'price' => 'required',
            'buy_date' => 'required',
        ];

        $validation = Validator::make($inputs, $rules);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $code = Str::random(20);
        $image = $request->file('image');
        $slug =  Str::slug($request->input('name'));

        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->code = $code;
        $product->save();

        Toastr::success('Product Successfully Created', 'Success!!!');
        return redirect()->route('admin.product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {

        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.product.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {


        return $product->all();

        $inputs = $request->except('_token');
        $rules = [
            'name' => 'required | min:3',
            'price' => 'required| integer',

        ];

        $validation = Validator::make($inputs, $rules);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $product->update($request->all(['name' => $request->get('name'), 'price' => $request->get('price')]));

        Toastr::success('Product Successfully Updated', 'Success!!!');
        return redirect()->route('admin.product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // delete old photo
        if (Storage::disk('public')->exists('product/' . $product->image)) {
            Storage::disk('public')->delete('product/' . $product->image);
        }

        $product->delete();
        Toastr::success('Product Successfully Deleted', 'Success!!!');
        return redirect()->route('admin.product.index');
    }
}
