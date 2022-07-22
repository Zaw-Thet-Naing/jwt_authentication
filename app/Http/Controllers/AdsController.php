<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class AdsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get ads
     * @var request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $data = Ads::all();
            return response()->json([
                'status code' => 200,
                'message' => 'ads list',
                'data' => $data
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status code' => 500,
                'message' => $e,
                'data' => null
            ], 500);
        }
    }

    /**
     * Create ads
     * @var request
     * @return Response
     */
    public function create(Request $request)
    {
        $input = $request->only(['title','image' , 'url_link', 'is_active', 'ads_type']);

        $validator = Validator::make($input, [
            'title' => "required",
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'url_link' => 'required',
            'is_active' => 'required',
            'ads_type' => 'required | in:agriupper,agrimiddle,agrilower,fisheryupper,fisherymiddle,fisherylower,livestockupper,livestockmiddle,livestocklower'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }

        if($request->hasFile('image'))
        {
            $destination_path = 'public/images/uploads';
            $image = $request->file('image');
            $image_name = time(). '.' . $image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($destination_path, $image_name);

            $request->image = $image_name;
        }

        try {
            $store = Ads::create([
                'title' => $request->title,
                'image' => $request->image,
                'url_link' => $request->url_link,
                'is_active' => $request->is_active,
                'ads_type' => $request->ads_type
            ]);
            return response()->json([
                'status_code' => 201,
                'message' => 'ads is created',
                'data' => $store
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status_code' => 500,
                'message' => $e,
                'data' => null
            ], 500);
        }
    }

    /**
     * Update Ads
     * @var request
     * @return Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['title','image', 'url_link', 'is_active', 'ads_type']);

        $id = $request->id;

        $validator = Validator::make($input, [
            // 'title' => 'required',
            // 'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            // 'url_link' => 'required',
            // 'is_active' => 'required',
            // 'ads_type' => 'required | in:agriupper,agrimiddle,agrilower,fisheryupper,fisherymiddle,fisherylower,livestockupper,livestockmiddle,livestocklower'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }

        if($request->hasFile('image'))
        {
            $destination_path = 'public/images/uploads';
            $image = $request->file('image');
            $image_name = time(). '.' . $image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($destination_path, $image_name);

            $input['image'] = $image_name;
        }

        $ads = Ads::find($id);

        if (!$ads) {
            return response()->json([
                'status_code' => 404,
                'message' => 'not found',
                'data' => []
            ], 404);
        }

        try {
            $ads->title = isset($input['title']) ? $input['title'] : $ads->title;
            $ads->image = isset($input['image']) ? $input['image'] : $ads->image;
            $ads->url_link = isset($input['url_link']) ? $input['url_link'] : $ads->url_link;
            $ads->is_active = isset($input['is_active']) ? $input['is_active'] : $ads->is_active;
            $ads->ads_type = isset($input['ads_type']) ? $input['ads_type'] : $ads->ads_type;
            $update = $ads->save($input);

            return response()->json([
                'status_code' => 201,
                'message' => 'Ads is Updated',
                'data' => $update
            ], 201);
        } catch (QueryException $e) {
            return $e;
            return response()->json([
                'status_code' => 500,
                'message' => $e,
                'data' => null
            ], 500);
        }
    }

    /**
     * Delete Ads
     * @var request
     * @return Response
     */
    public function destory(Request $request)
    {
        $id = $request->id;

        $ads = Ads::find($id);

        if ($ads === null) {
            return response()->json([
                'status_code' => 400,
                'message' => 'not found',
                'data' => null
            ], 400);
        }

        $destroy = $ads->delete();

        return response()->json([
            'status_code' => 200,
            'message' => 'ads is deleted',
            'data' => $destroy
        ], 200);
    }
}
