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
     * Get ads
     * @var request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $data = BannerSlider::all();
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
     * Create ads
     * @var request
     * @return Response
     */
    public function create(Request $request)
    {
        $input = $request->only(['title', 'url_link', 'is_active', 'ads_type']);

        $validator = Validator::make($input, [
            'title' => "required",
            'url_link' => 'required',
            'is_active' => 'required',
            'banner_type' => 'required | in:mainslider,footerslider,leaderboard'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }

        try {
            $store = BannerSlider::create($input);
            return response()->json([
                'status_code' => 201,
                'message' => 'banner slider is created',
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
        $input = $request->only(['title', 'url_link', 'is_active', 'ads_type']);

        $id = $request->id;

        $validator = Validator::make($input, [
            'title' => 'required',
            'url_link' => 'required',
            'is_active' => 'required',
            'banner_type' => 'required | in:mainslider,footerslider,leaderboard'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
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
            $banner->url_link = isset($input['url_link']) ? $input['url_link'] : $banner->url_link;
            $banner->is_active = isset($input['is_active']) ? $input['is_active'] : $banner->is_active;
            $banner->banner_type = isset($input['banner_type']) ? $input['banner_type'] : $banner->banner_type;
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

        $ads = BannerSlider::find($id);

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
            'message' => 'banner slider is deleted',
            'data' => $destroy
        ], 200);
    }
}
