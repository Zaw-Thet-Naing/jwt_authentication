<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Validator;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => "index"]);
    }

    public function index() {
        try {
            $events = Event::all();
            return response()->json([
                "message" => "Succeeded",
                "events" => $events
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
        $input = $request->only(["title", "url_link", "is_active"]);
        $validator = Validator::make($input, [
            "title" => "required", 
            "url_link" => "required",
            "is_active" => "required|boolean"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => $validator->errors()->first()
            ], 400);
        }

        try {
            $new_event = Event::create([$input]);
            return response()->json([
                "message" => "Succeeded",
                "new_event" => $new_event
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "error" => "Unknown error"
            ], 500);
        }
    }

    public function update($id, Request $request) {
        $event = Event::find($id);
        if(!$event) {
            return response()->json([
                "error" => "Resource not found"
            ], 404);
        }
        $input = $request->only(["title", "url_link", "is_active"]);

        if(!$input) {
            return response()->json([
                "message" => "Nothing was updated",
            ], 200);
        }

        $event->title = $input["title"] ?? $event->title;
        $event->url_link = $input["url_link"] ?? $event->url_link;
        $event->is_active = $input["is_active"] ?? $event->is_active;

        try {
            $event->update();
            return response()->json([
                "message" => "Succeeded",
                "updated_event" => $event
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                "error" => "Unknown error",
            ], 500);
        }
    }

    public function destroy($id, Request $request) {
        $event = Event::find($id);
        if(!$event) {
            return response()->json([
                "error" => "Resource not found"
            ], 404);
        }
        try {
            $event->delete();
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
