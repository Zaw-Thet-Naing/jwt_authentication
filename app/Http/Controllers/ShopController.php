<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $pageSize = $request->page_size ? $request->page_size : 10;
        try{
            $shop = Shop::with(['categories'])->paginate($pageSize);
        }catch(QueryException $e){
            return respone()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }
        return response()->json([
            'status' => 'successful',
            'shops' => $shop,
        ]);
    }

    public function show($id){
        try{
            $shop = Shop::find($id);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        return response()->json([
            'status' => 'successful',
            'shop_category' => $shop
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->only('shop_category_id', 'name', 'address', 'phone', 'website', 'email', 'image', 'is_active');

        $validator = Validator::make($input, [
            'shop_category_id' => 'required',
            'name' => 'required|string|max:255|unique:shop,name',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
            'website' => 'required|string',
            'email' => 'required|string|email|max:255',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'is_active' => 'required|boolean',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->first(),
            ]);
        }
        if($request->hasFile('image'))
        {
            $destination_path = 'public/images/uploads';
            $image = $request->file('image');
            $image_name = time(). '.' . $image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($destination_path, $image_name);

            $request->image = $image_name;
        }
        try{
            $shop = Shop::create([
                'shop_category_id' => $request->shop_category_id,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'website' => $request->website,
                'email' => $request->email,
                'image' => $request->image,
                'is_active' => $request->is_active, 
            ]);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        return response()->json([
            'status' => 'successful',
            'message' => 'Shop created successfully',
            'shop' => $shop,
        ]);
    }

    public function update(Request $request, $id){
        try{
            $shop = Shop::find($id);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        $input = $request->only('shop_category_id', 'name', 'address', 'phone', 'website', 'email', 'image', 'is_active');
        $validator = Validator::make($input, [
            'shop_category_id' => 'required',
            'name' => 'required|string|max:255|unique:shop,name',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
            'website' => 'required|string',
            'email' => 'required|string|email|max:255',
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'is_active' => 'required|boolean',
        ]);

        if($request->hasFile('image'))
        {
            $destination_path = 'public/images/uploads';
            $image = $request->file('image');
            $image_name = time(). '.' . $image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($destination_path, $image_name);

            $request->image = $image_name;
        }

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->first(),
            ]);
        }
        try{
            $shop->shop_category_id = $request->shop_category_id;
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
            $shop->website = $request->website;
            $shop->email = $request->email;
            $shop->image = $request->image;
            $shop->image = $request->is_active;
            $shop->save();   
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        return respone()->json([
            'status' => 'successful',
            'message' => 'Shop updated successfully',
            'shop' => $shop,
        ]);
    }

    public function destroy($id){
        try{
            $shop = Shop::find($id);
            $shop->delete();
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }
        return respone()->json([
            'status' => 'successful',
            'message' => 'Shop deleted successfully',
            'shop' => $shop,
        ]);

    }
}
