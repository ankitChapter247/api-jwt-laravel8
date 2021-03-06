<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Validator;
use Image;
use File;

class PostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $post = Post::latest()->with('category')->paginate(5);
        if (count($post)) {
            return response()->json(
                [
                    'message' => 'Show posts ',
                    'data'  => $post
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "No post are avilable"
                ]
            ,204);   
        }
        
    }
    public function edit($id)
    {
        $post = Post::with('category')->find($id);

        if ($post) {
            return response()->json(
                [
                'message'=> "Post data",
                 'data'  => $post
                ], 
            );
        }else{
            return response()->json(
                [
                'message'=> "Post not found"
                ], 
                401
            );
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
        //print_r($request->all()); die;
        // return response()->json([
        //     'message' => 'Successfully add new post',
        //     'data'  => $request->all()
        // ]);
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string',
            'category_id'   => 'required',
            'shortDesc'     => 'required|string',
            'description'   => 'required|string',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        } 

        $input = $request->all();
        // dd($input);
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $imagePath = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imagePath);

            $thumbdestinationPath = 'image/thumbnail';
                    if(!File::isDirectory($thumbdestinationPath)){
                        File::makeDirectory($thumbdestinationPath, 0777, true, true);
                    }
                $img = Image::make('image/'.$imagePath);
                $img->resize(80, 80, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbdestinationPath.'/'.$imagePath);

        }
    
        $post = Post::create(
            [
                'title' => $request->title,
                'category_id' => $request->category_id,
                'shortDesc' => $request->shortDesc,
                'description' => $request->description,
                'post_slug' => $request->post_slug,
                'image' => $imagePath,
                'thumbnail' => 'thumbnail/'.$imagePath,
            ]
        );
       
        if ($post) {
            // For making unique slug, adding the inserted post ID to the post slug
            $post->post_slug = $post->post_slug.'_'.$post->id;
            $post->save();
            return response()->json([
                'message' => 'New post added successfully',
                'status' => 200,
                'data'  => $post
            ]);
        } else {
            return response()->json(
                [
                'message'=> "Something went wrong"
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
            'category_id'   => 'required',
            'shortDesc'     => 'required|string',
            'description'   => 'required|string',
            //'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
  
        $post = Post::find($id);

        if (!$post) {
            return response()->json(
                [
                'message'=> "Post not found"
                ], 
                401
            );
        }
          
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $imagePath = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imagePath);

            $thumbdestinationPath = 'image/thumbnail';
                    if(!File::isDirectory($thumbdestinationPath)){
                        File::makeDirectory($thumbdestinationPath, 0777, true, true);
                    }
                $img = Image::make('image/'.$imagePath);
                $img->resize(80, 80, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbdestinationPath.'/'.$imagePath);
        } else {
            $imagePath = $post->image;
        }
                
        $post->title = $request->title;
        $post->shortDesc = $request->shortDesc;
        $post->description = $request->description;
        $post->image = $imagePath;
        $post->category_id = $request->category_id;
        $post->thumbnail = 'thumbnail/'.$imagePath;

        $post->save();
         
        if ($post) {
            
            return response()->json(
                [
                'message' => 'Post updated successfully',
                'status' => 200,
                'data'  => $post
                ]
            );

        } else {
            return response()->json(
                [
                'message'=> "Something went wrong"
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
                'message' => 'Post delete successfully',
                'status' => 200
                ]
            );

        } else {
            return response()->json(
                [
                'message'=> "Something went wrong"
                ], 
                401
            );    
        }
    }
}
