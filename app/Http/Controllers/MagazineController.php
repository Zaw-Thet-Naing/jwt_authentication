<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magazine;
use Validator;

class MagazineController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => "index"]);
    }
    public function index() {
        try {
            $magazines = Magazine::all();
            return response()->json([
                "message" => "Succeeded",
                "magazines" => $magazines
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                "error" => "Unknown error"
            ], 500);
        }
    }

    public function show($id) {
        
    }

    public function create(Request $request) {
        $input = $request->only(["title", "download_link", "release_date", "is_active"]);
        $validator = Validator::make($input, [
            "title" => "required",
            "download_link" => "required",
            "release_date" => "required|date",
            "is_active" => "required|boolean"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 400);
        }

        try {
            $new_magazine = Magazine::create($input);
            return response()->json([
                "message" => "Succeeded",
                "new_magazine" => $new_magazine
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "error" => "Unknown error"
            ], 500);
        }
    }

    public function update($id, Request $request) {
        $magazine = Magazine::find($id);
        if(!$magazine) {
            return response()->json([
                "error" => "Resource not found"
            ], 404);
        }
        $input = $request->only(["title", "download_link", "release_date", "is_active"]);

        if(!$input) {
            return response()->json([
                "message" => "Nothing was updated",
            ], 200);
        }

        $magazine->title = $input["title"] ?? $magazine->title;
        $magazine->download_link = $input["download_link"] ?? $magazine->download_link;
        $magazine->release_date = $input["release_date"] ?? $magazine->release_date;
        $magazine->is_active = $input["is_active"] ?? $magazine->is_active;

        try {
            $magazine->update();
            return response()->json([
                "message" => "Succeeded",
                "updated_magazine" => $magazine
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                "error" => "Unknown error",
            ], 500);
        }
    }

    public function destroy($id, Request $request) {
        $magazine = Magazine::find($id);
        if(!$magazine) {
            return response()->json([
                "error" => "Resource not found"
            ], 404);
        }
        try {
            $magazine->delete();
            return response()->json([
                "message" => "Deleted successfully",
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                "error" => "Unknown error"
            ], 500);
        }
    }
}
