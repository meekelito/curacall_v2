<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MobCases;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->input('user_id');
        $status = $request->input('status');
        $offset = $request->has('offset') ? $request->input('offset') : 0;
        $limit = $request->has('limit') ? $request->input('limit') : 20;

        if ($status) {
            $cases = MobCases::Join('case_participants AS b','cases.id','=','b.case_id')
            ->where('b.user_id', $user_id)
            ->where('cases.status', $status)
            ->select('*')
            ->offset($offset)
            ->limit($limit)
            ->get();
        }
        else {
            $cases = MobCases::Join('case_participants AS b','cases.id','=','b.case_id')
            ->where('b.user_id', $user_id)
            ->where('cases.status', '!=', 4)
            ->select('*')
            ->offset($offset)
            ->limit($limit)
            ->get();
        }

        return response()->json([
            'result'=>'success',
            'user_id'=>$user_id,
            'status'=>$status,
            'total'=>count($cases),
            'data'=>$cases,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user_id = $request->input('user_id');
        $cases = MobCases::Join('case_participants AS b','cases.id','=','b.case_id')
        ->where('b.user_id', $user_id)
        ->where('cases.id', $id)
        ->select('*')
        ->first();
        return response()->json($cases);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
