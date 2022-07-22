<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ShopCategories;

class ShopCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $pageSize = $request->page_size ? $request->page_size : 10;
        try{
            $shopCategory = ShopCategories::with(['shop'])->paginate($pageSize);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        return response()->json([
            'status' => 'successful',
            'shop-categories' => $shopCategory,
        ]);
    }

    public function show($id){
        try{
            $shopCategory = ShopCategories::find($id);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        return response()->json([
            'status' => 'successful',
            'shop_category' => $shopCategory
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:shop_categories,name',
            'dir_category' => 'required|in:Agriculture,Livestock,Fishery',
            'is_active' => 'required|boolean'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'error' => $validator->errors()->first(),
            ]);
        }

        try{
            $shopCategory = ShopCategories::create([
                'name' => $request->name,
                'dir_category' => $request->dir_category,
                'is_active' => $request->is_active
            ]);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'shop category created successfully',
            'shop_category' => $shopCategory,
        ]);
    }

    public function update(Request $request, $id)
    {
        try{
            $shopCategory = ShopCategories::find($id);
        }catch(QueryException $e){
            return response()->json([
                'error' => 'resource not found',
            ]);
        }

        $validator = Validation::make($request->all(), [
            'name' => 'required|string|max:255|unique:App\shop_categories,name',
            'dir_category' => 'required|in:Agriculture,Livestock,Fishery',
            'is_active' => 'required|boolean',
        ]);

        try{
            $shopCategory->name = $request->name;
            $shopCategory->dir_category = $request->dir_category;
            $shopCategory->is_active = $request->is_active;
            $shopCategory->save();
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }
        return response()->json([
            'status' => 'successful',
            'message' => 'Shop category updated Successfully',
            'shop_category' => $shopCategory,
        ]);
    }

    public function destroy($id)
    {
        try{
            $shopCategory = ShopCategories::find($id);
        }catch(QueryException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown error'
            ]);
        }
        $shopCategory->delete();
        return response()->json([
            'status' => 'successful',
            'message' => 'Shop category deleted Successfully',
            'shop_category' => $shopCategory,
        ]);
    }
}
