<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Validator;

class SettingController extends Controller
{
    public function index()
    {

        $setting = Setting::get();
        if (count($setting)) {
            return response()->json(
                [
                    'message' => 'show settings ',
                    'data'  => $setting
                ]
            );
        } else {
            return response()->json(
                [
                    'message'=> "no setting are avilable"
                ]
            ,204);   
        } 
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facebook_url'         => 'required',
            'twitter_url'   => 'required',
            'youtube_url'     => 'required',
            'instagram_url'   => 'required',
            'vimo_url'         => 'required',
        ]);
  
        if ($validator->fails()) {             
            return response()->json(['error'=>$validator->errors()], 401);                        
        } 

        $input = $request->all();

        $setting = Setting::find(1);

        if (!$setting) {
            return response()->json(
                [
                'message'=> "post are not found"
                ], 
                401
            );
        }
        $setting->facebook_url = $request->facebook_url;
        $setting->twitter_url = $request->twitter_url;
        $setting->youtube_url = $request->youtube_url;
        $setting->instagram_url = $request->instagram_url;
        $setting->vimo_url = $request->vimo_url;

        $setting->save();
      
        if ($setting) {
            return response()->json([
                'message' => 'Successfully add new setting',
                'status' => 200,
                'data'  => $setting
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
}
