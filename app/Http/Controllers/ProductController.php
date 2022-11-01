<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index() {
        $products=Product::with('getSubCategory')->latest()->paginate(10);
        // dd($products);
        $categories=Category::all();
        $subcategories=Subcategory::with('getCategory')->get();
        // dd($subcategories);

        return view('product', compact('products','subcategories', 'categories'));
    }

    public function productStore(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {

            $data          = array();
            $data['error'] = $validator->errors()->all();
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        } else {
            $product_thumb = $request->thumbnail;
            if ($product_thumb) {
                $product_thumb_name = hexdec(uniqid());
                $product_thumb_ext  = strtolower($product_thumb->getClientOriginalExtension());

                $product_thumb_full_name = $product_thumb_name . '.' . $product_thumb_ext;
                $product_thumb_upload_path     = 'productthumb/';
                $product_thumb_upload_path1    = 'backend/productthumb';
                $product_thumb_url       = $product_thumb_upload_path . $product_thumb_full_name;
                $success                       = $product_thumb->move($product_thumb_upload_path1, $product_thumb_full_name);
            } else {
                $product_thumb_url       = 'productthumb/default.png';
            }

            
            $product = Product::create([
                'title' => $request->title,
                'description' => $request->description,
                'price' => $request->price,
                'subcategory_id' => $request->subcategory_id,
                'thumbnail' => $product_thumb_url,
            ]);
            
            $data = array();
            $data['message'] = 'Category Added Successfully';
            $data['title'] = $product->title;
            $data['price'] = $product->price;
            $data['description'] = $product->description;
            $data['subcategory_id'] = $product->subcategory_id;
            $data['thumbnail'] = $product->thumbnail;
            $data['id'] = $product->id;
            
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        }
    }


    public function productDelete(Request $request){
        // dd($request->all());
        $product = Product::findOrFail($request->id);

        if ($product) {
            File::delete('backend/' . $product->thumbnail);
            $product->delete();
            $data            = array();
            $data['message'] = 'Product deleted successfully';
            $data['id']      = $request->id;
            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } else {
            $data            = array();
            $data['message'] = 'Product can not deleted!';
            return response()->json([
                'success' => false,
                'data'    => $data,
            ]);
        }
    }

    public function subcategoryProduct(Request $request){
        $products=Product::with('getSubCategory')->where('subcategory_id', $request->id)->get();
        if($products){
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found !!',
            ]);
        }
        
    }
    public function filterCategory(Request $request){
        $subcats = Subcategory::where('category_id',$request->id)->get();
        $ids=[];
        foreach($subcats as $subcat){
            $ids[]=$subcat->id;   
        }

        $products = Product::with('getSubCategory')->whereIn('subcategory_id',$ids)->get();
        if($products){
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found !!',
            ]);
        }
    }

    public function searchProduct(Request $request){

        $searchProduct = $request->product_title;
        $products = Product::where('title','like','%'.$searchProduct.'%')->get();
        if($products){
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found !!',
            ]);
        }
    }
    public function filterPrice(Request $request){

        $minPrice = $request->min;
        $maxPrice = $request->max;

        $productMin= Product::min('price');
        $productMax= Product::max('price');
       
        if($minPrice!=null && $maxPrice!=null ){
            $products = Product::whereBetween('price', [$minPrice,$maxPrice])->get();
        }else if($minPrice==null){
            $products = Product::whereBetween('price', [$productMin, $maxPrice])->get();
        }else if( $maxPrice==null){
            $products = Product::whereBetween('price', [$productMin,$productMax])->get();
        }else{
            $products = Product::all();
        }
        
        if($products){
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found !!',
            ]);
        }
    }
    
    public function productList(){
  
        $products = Product::select('title')->get();
        // $products = Product::where('title','like','%'.$search.'%')->get();

        $data = [];

        foreach($products as $item){
            $data[] = $item['title'];
        }
        return $data ;
    }
}
