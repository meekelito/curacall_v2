<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DataTables;
use DB;
use Cache;
use Auth;

class ArchivedCasesController extends Controller
{

  public function index()
  {
    return view( 'archived-cases');
  }

  public function fetchArchiveMessages() 
  {   
    $messages = Message::where( 'id', 40 );
    return Datatables::of($messages)
    ->addColumn('action', function ($messages) {
      return '<a class="btn btn-success btn-xs" title="Edit"><i class="icon-pencil4"></i></a>
      <a class="btn btn-danger btn-xs" title="Remove"><i class="icon-x"></i></a>
      '; 
    })->rawColumns(['action'])
    ->make(true);                                                                                
  } 
}
