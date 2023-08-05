<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProductFormrequest;
use Illuminate\Http\Request;
use App\Models\product;

class ProductController extends Controller
{

    public function index(){
        $products = product::paginate(5);
        return view('product.index',compact('products'))->with('i',(request()->input('page',1)-1)*5);
    }

    public function create()
    {
        return view('product.create');
    }

    public function store(ProductFormrequest $request)
    {
        $data = $request->validated();
        $product =  product::create($data);
        return redirect('/add-product')-> with('message','added successfully');
    }

    public function edit($product_id){
        $products = product::find($product_id);
        return view('product.edit',compact('products'));
    }

    public function update(ProductFormrequest $request,$product_id){
        $data = $request->validated();
        $product =  product::where('id',$product_id)->update([
            'category'=> $data['category'],
            'name'=> $data['name'],
            'price'=> $data['price'],
//            'image'=> $data['image'],
        ]);
        return redirect('/show-products')-> with('message','update successfully');
    }

    public function destroy($product_id){
        $product =  product::find($product_id)->delete();
        return redirect('/show-products')-> with('message','delete successfully');
    }

    public function AddToCart($product_id){
        $product = ProductController::findOrFail($product_id);
        $cart = session()->get('cart',[]);
        if(isset($cart[$product_id])){
            $cart[$product_id]['quantity']++;
        }
        else{
            $cart[$product_id]=[
                'category'=>$product->product_category,
                'name'=>$product->product_name,
                'image'=>$product->product_image,
                'price'=>$product->product_price,
                'quantity'=>1
            ];
        }
        session()->put('cart',$cart);
        return redirect()->back()-> with('message','add to cart successfully');
    }
}
