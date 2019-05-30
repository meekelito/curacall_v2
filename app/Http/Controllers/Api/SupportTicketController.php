<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MobSupportTicket;
use Image;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function randomNumber() {
        $alphabet = "0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet)-1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
      }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $png_url = "";
        if ($request->base64_image) {
            $png_url = $this->randomNumber().".png";
            $path = public_path().'/uploads/' . $png_url;
        
            Image::make(file_get_contents($request->base64_image))->save($path);     
        }

        $ticket = MobSupportTicket::create([
            'file'=>$png_url,
            'message' =>$request->ticket,
            'name' =>$request->name,
            'type' =>$request->type
        ]);
        return ($ticket) ? $ticket : null;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
