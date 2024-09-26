<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ip)
    {
        return History::where('ip', $ip )->orderBy('created_at', 'desc')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->answer != ''){
            $history = History::create($request->all());

            return response()->json(['success' => true, 'history' => $history ], 201);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $history = History::where('id', $id)->first();
        if($history){
            $history->delete();
        }

        return response()->json(['success' => true]);
    }
}
