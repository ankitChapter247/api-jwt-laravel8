<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Post, Setting, Category };
use Validator;

class FrontController extends Controller
{
    public function trandingList() {
        $post = Post::select('id', 'title')->skip(0)->take(5)->get();
        if (count($post)) {
            return response()->json(
                [
                    'message' => 'show posts ',
                    'data'  => $post,
                    'status' => 200
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no post are avilable"
                ]
            , 204);   
        }
    }

    public function socialMedia() {

        $setting = Setting::first();
        if ($setting) {
            return response()->json(
                [
                    'message' => 'All socail media link',
                    'data'  => $setting,
                    'status' => 200
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no  socail media link are avilable"
                ]
            , 204);   
        }
        
    }

    public function categories() {

        $categories = Category::select('id', 'title')->get();
        if (count($categories)) {
            return response()->json(
                [
                    'message' => 'show all category ',
                    'data'  => $categories,
                    'status' => 200
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no category are avilable"
                ]
            , 204);   
        }
                
    }

    public function postList(Request $request) {

        $validator = Validator::make($request->all(), [
            'start'     => 'required|numeric',
            'total'     => 'required|numeric',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        }

        $post = Post::with('category')->skip($request->start)->take($request->total)->get();

        if (count($post)) {
            return response()->json(
                [
                    'message' => 'show posts ',
                    'data'  => $post,
                    'status' => 200
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no post are avilable"
                ]
            , 204);   
        }
    }

    public function postByCategory(Request $request) {

        $validator = Validator::make($request->all(), [
            'total'     => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        }

        $post = Post::where('category_id', $request->category_id)->take($request->total)->get();

        if (count($post)) {
            return response()->json(
                [
                    'message' => 'show posts ',
                    'data'  => $post,
                    'status' => 200
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no post are avilable"
                ]
            , 204);   
        }

    }
}
