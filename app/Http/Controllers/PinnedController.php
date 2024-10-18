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

        return redirect()->route('home');
    }

    public function postAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'required|string|max:255',
            'notes' => 'required|string|max:255',

        ]); 
        
        if ($validator->fails()) {
            return redirect()->route('home')
                             ->withErrors($validator)
                             ->withInput();
        }   

        $sauth = Auth::user();

        Pinned::create([
            'user_id' => $sauth->id,
            'header' => $request->header,
            'notes' => $request->notes,
        ]);

        return redirect()->route('home');
    }

    public function postEdit(Request $request)  
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exits:pinned',
            'Header' => 'required|string|max:255',
            'Notes' => 'required|string|max:255',
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
            return redirect()
            ->route('home')
            ->withErrors($validator)
            ->withInput();
        }

        $auth = Auth::user();

        $pinned = Pinned::where('id', $request->id)->where('user_id', $auth->id)->first();
        if ($pinned) {
            $pinned->delete();
        }

        $data = [
            "auth" => $auth,
            "pinned" => Pinned::where('user_id', $auth->id)->orderBy("created_at", "desc")->get(),
        ];

        return view("app.pinned", $data);
    }
}