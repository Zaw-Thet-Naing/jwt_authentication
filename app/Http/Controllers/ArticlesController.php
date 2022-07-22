<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get articles
     * @var request
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $data = Articles::all();

            return response()->json([
                'status code' => 200,
                'message' => 'articles list',
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
     * Create articles
     * @var request
     * @return Response
     */
    public function create(Request $request)
    {
        $input = $request->only(['title', 'description', 'content', 'is_highlight', 'is_active','image' ,'category']);

        $validator = Validator::make($input, [
            "title" => "required",
            "description" => "required",
            "content" => "required",
            "is_highlight" => "required",
            "is_active" => "required",
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            "category" => "required | in:agricultureNews,fisheryNews,liveStockNews"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status code" => 422,
                "message" => $validator->errors()->first(),
                "data" => []
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
            $store = Articles::create([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'is_highlight' => $request->is_highlight,
                'is_active' => $request->is_active,
                'image' => $request->image,
                'category' => $request->category
            ]);
            return response()->json([
                'status_code' => 201,
                'message' => 'article is created',
                'data' => $store
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status code' => 500,
                'message' => $e,
                'data' => null
            ], 500);
        }
    }

    /**
     * Update articles
     * @var request
     * @return Response
     */
    public function update(Request $request)
    {
        $input = $request->only(['title', 'description', 'content', 'is_highlight', 'is_active','image' , 'category']);

        $id = $request->id;

        $validator = Validator::make($input, [
            // "title" => "required",
            // "description" => "required",
            // "content" => "required",
            // "is_highlight" => "required",
            // "is_active" => "required",
            // 'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
            // "category" => "required | in:agricultureNews,fisheryNews,liveStockNews"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status code" => 422,
                "message" => $validator->errors()->first(),
                "data" => []
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

        $articles = Articles::find($id);

        if (!$articles) {
            return response()->json([
                'status_code' => 400,
                'message' => 'not found',
                'data' => []
            ], 404);
        }

        try {
            $articles->title = isset($input['title']) ? $input['title'] : $articles->title;
            $articles->description = isset($input['description']) ? $input['description'] : $articles->description;
            $articles->content = isset($input['content']) ? $input['content'] : $articles->content;
            $articles->is_highlight = isset($input['is_highlight']) ? $input['is_highlight'] : $articles->is_highlight;
            $articles->is_active = isset($input['is_active']) ? $input['is_active'] : $articles->is_active;
            $articles->image = isset($input['image']) ? $input['image'] : $articles->image;
            $articles->category = isset($input['category']) ? $input['category'] : $articles->category;

            $update = $articles->save($input);

            return response()->json([
                'status_code' => 200,
                'message' => 'articles is updated',
                'data' => $update
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Unknown Error',
                'data' => null
            ], 500);
        }
    }

    /**
     * Delete Open Number Time
     * @var request
     * @return Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        $articles = Articles::find($id);

        if ($articles === null) {
            return response()->json([
                'status_code' => 400,
                'message' => 'not found',
                'data' => null
            ], 400);
        }

        $destroy = $articles->delete();

        return response()->json([
            'status_code' => 200,
            'message' => 'articles is deleted',
            'data' => $destroy
        ], 200);
    }
}
