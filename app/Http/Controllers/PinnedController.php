<?php

namespace App\Http\Controllers;

use App\Models\Pinned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Validator;   

class PinnedController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $pinned = Pinned::where('user_id', $auth->id)->orderBY("created_at", "desc")->get();

        $data = [
            "auth" => $auth,
            "pinned" => $pinned,
        ];

        return view("app.pinned", $data);
    }

    public function postAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required|string|max:255',
        ]); 
        
        if ($validator->fails()) {
            return redirect()->route('home')
                             ->withErrors($validator)
                             ->withInput();
        }   

        $sauth = Auth::user();

        Pinned::create([
            'user_id' => $auth->id,
            'activity' => $request->activity,
        ]);

        return redirect()->route('home');
    }

    public function postEdit(Request $request)  
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exits:pinned',
            'activity' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home')
                             ->withErrors($validator)
                             ->withInput();
        }

        $auth = Auth::user();

        $pinned = Pinned::where('id', $request->id)->where('user_id', $auth->id)->first();
        if (!$pinned) {
            $pinned -> activity = $request->activity;
            $pinned -> status = $request->status;
            $pinned -> save();
        }

        return redirect()->route('home');
    }

    public function postDelete(Request $request)    
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exits:pinned',
        ]); 

        if ($validator->fails()) {
            return redirect()->route('home')
                             ->withErrors($validator)
                             ->withInput();
        }

        $auth = Auth::user();

        $pinned = Pinned::where('id', $request->id)->where('user_id', $auth->id)->first();
        if ($pinned) {
            $pinned->delete();
        }

        return view("app.pinned", $data);
    }
}