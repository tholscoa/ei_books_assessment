<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class ExternalBookController extends Controller
{
    public function getBook(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status_code' => 400, 'status' =>  'failed', 'message'=>'Error encountered while validating your input', 'data' => null], 400);
        }
        $name = trim($request['name']);
        $getBook = Http::get("https://www.anapioficeandfire.com/api/books", ['name'=>$name]);
        $book =  json_decode($getBook->body());
        if(empty($book)){
            return response()->json(['status_code' => 404, 'status' =>  'not found', 'data' => []], 404);
        }
        return response()->json(['status_code' => 200, 'status' =>  'success', 'data' => $book], 200);
    }
}
