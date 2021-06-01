<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::latest()->paginate(10);
        if (count($category)) {
            return response()->json(
                [
                    'message' => 'show category ',
                    'data'  => $category
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no category are avilable"
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
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
    
        $category = Category::create(
            [
                'title' => $request->title,
            ]
        );
     
        if ($category) {
            return response()->json([
                'message' => 'Successfully add new category',
                'data'  => $category,
                'status' => 200
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
     * @param  \App\Category  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        }
  
        $category = Category::find($id);

        if (!$category) {
            return response()->json(
                [
                'message'=> "category are not found"
                ], 
                401
            );
        }         
                 
        $category->title = $request->title;
        $category->save();
         
        if ($category) {
            
            return response()->json(
                [
                'message' => 'Category updated successfully',
                'data'  => $category
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
     * @param  \App\Category  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);           
        
        if ($category) {

            $category->delete();             
            return response()->json(
                [
                'message' => 'Category delete successfully'
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
