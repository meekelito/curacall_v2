<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\EmailTemplate;
use Illuminate\Http\Request;
use DataTables;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('email-template.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function fetchData()  
    {  
       $emails = EmailTemplate::all();
        

        return Datatables::of($emails)
        ->addColumn('action', function ($email) {
          return '<a href="javascript:editTemplate(\''. route('email-template.edit',[$email->id]) .'\')" class="label bg-slate label-rounded label-icon" title="Edit"><i class="icon-pencil"></i></a>'; 
        })
        ->rawColumns(['action'])
        ->make(true);                                                                                
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
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('email-template.edit',['template'=> $emailTemplate]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        
        $email = EmailTemplate::findOrFail($emailTemplate->id);
        $email->subject = $request->subject;
        $email->content = $request->content;
        $result = $email->save();
        
          if($result){
            return response()->json([ 
                "status"=> 1,
                "message"=> "Successfully saved"
              ]);
         }else
           return response()->json([ 
                "status"=> 0,
                "message"=> "Oops. Something went wrong."
              ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }
}
