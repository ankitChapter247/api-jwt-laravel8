<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Validator;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::latest()->paginate(5);
        if (count($post)) {
            return response()->json(
                [
                    'message' => 'show posts ',
                    'data'  => $post
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no post are avilable"
                ]
            ,204);   
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string',
            'shortDesc'     => 'required|string',
            'description'   => 'required|string',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        } 

        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $imagePath = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imagePath);
        }
    
        $post = Post::create(
            [
                'title' => $request->title,
                'shortDesc' => $request->shortDesc,
                'description' => $request->description,
                'image' => $imagePath
            ]
        );
     
        if ($post) {
            return response()->json([
                'message' => 'Successfully add new post',
                'data'  => $post
            ]);
        } else {
            return response()->json(
                [
                'message'=> "something went wrong"
                ], 
                401
            );   
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string',
            'shortDesc'     => 'required|string',
            'description'   => 'required|string',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
  
        $post = Post::find($id);

        if (!$post) {
            return response()->json(
                [
                'message'=> "post are not found"
                ], 
                401
            );
        }
          
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $imagePath = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imagePath);
        } else {
            $imagePath = $post->image;
        }
                
        $post->title = $request->title;
        $post->shortDesc = $request->shortDesc;
        $post->description = $request->description;
        $post->image = $imagePath;
        $post->save();
         
        if ($post) {
            
            return response()->json(
                [
                'message' => 'post updated successfully',
                'data'  => $post
                ]
            );

        } else {
            return response()->json(
                [
                'message'=> "something went wrong"
                ], 
                401
            );    
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);           
        
        if ($post) {

            $post->delete();             
            return response()->json(
                [
                'message' => 'post delete successfully'
                ]
            );

        } else {
            return response()->json(
                [
                'message'=> "something went wrong"
                ], 
                401
            );    
        }
    }
}
