<?php

namespace App\Http\Controllers;

use App\Models\BannerSlider;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerSliderController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get banner
     * @var request
     * @return Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ? $request->page_size : 10;
        try {
            $data = BannerSlider::paginate($pageSize);
            return response()->json([
                'status code' => 200,
                'message' => 'banner slider list',
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
     * Create banner
     * @var request
     * @return Response
     */
    public function create(Request $request)
    {
        $input = $request->only(['title', 'image', 'url_link', 'is_active', 'type_banner']);

        $validator = Validator::make($input, [
            'title' => "required",
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            'url_link' => 'required',
            'is_active' => 'required',
            'type_banner' => 'required | in:mainslider,footerslider,leaderboard'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }

        if ($request->hasFile('image')) {
            $destination_path = 'public/images/uploads';
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($destination_path, $image_name);
            $request->image = $image_name;
        }

        try {
            $store = BannerSlider::create([
                'title' => $request->title,
                'image' => $request->image,
                'url_link' => $request->url_link,
                'is_active' => $request->is_active,
                'type_banner' => $request->type_banner,
            ]);
            return response()->json([
                'status_code' => 201,
                'message' => 'banner slider is created',
                'data' => $store
            ], 201);
        } catch (QueryException $e) {
            dd($e);
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
        $input = $request->only(['title', 'url_link', 'is_active','image' , 'type_banner']);

        $id = $request->id;

        $validator = Validator::make($input, [
            // 'title' => 'required',
            // 'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            // 'url_link' => 'required',
            // 'is_active' => 'required',
            // 'type_banner' => 'required | in:mainslider,footerslider,leaderboard'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }

        if ($request->hasFile('image')) {
            $destination_path = 'public/images/uploads';
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($destination_path, $image_name);

            $input['image'] = $image_name;
        }

        $banner = BannerSlider::find($id);

        if (!$banner) {
            return response()->json([
                'status_code' => 404,
                'message' => 'not found',
                'data' => []
            ], 404);
        }

        try {
            $banner->title = isset($input['title']) ? $input['title'] : $banner->title;
            $banner->image = isset($input['image']) ? $input['image'] : $banner->image;
            $banner->url_link = isset($input['url_link']) ? $input['url_link'] : $banner->url_link;
            $banner->is_active = isset($input['is_active']) ? $input['is_active'] : $banner->is_active;
            $banner->type_banner = isset($input['type_banner']) ? $input['type_banner'] : $banner->type_banner;
            $update = $banner->save($input);

            return response()->json([
                'status_code' => 201,
                'message' => 'banner slider is Updated',
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

        $banner = BannerSlider::find($id);

        if ($banner === null) {
            return response()->json([
                'status_code' => 400,
                'message' => 'not found',
                'data' => null
            ], 400);
        }

        $destroy = $banner->delete();

        return response()->json([
            'status_code' => 200,
            'message' => 'banner slider is deleted',
            'data' => $destroy
        ], 200);
    }
}
