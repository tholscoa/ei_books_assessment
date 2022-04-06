<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternalBookController extends Controller
{
    public function createBook(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'isbn' => 'required|string',
            'authors' => 'required|array',
            'country' => 'required|string',
            'number_of_pages' => 'required|integer',
            'publisher' => 'required|string',
            'release_date' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status_code' => 400, 'status' =>  'failed', 'message'=>'Error encountered while validating your input', 'data' => null], 400);
        }

        $input = $request->all();

        try {
            $book = Book::create($input);
            $authors_exist = isset($request['authors']) ? true : false;
            if($authors_exist){
                $authors = $request['authors'];
                foreach($authors as $author){
                    Author::create([
                        'name' => $author,
                        'book_id' => $book->id
                    ]);
                }
            }
            $book_details = Book::with('authors')->whereId($book->id)->get();
            return response()->json(['status_code' => 201, 'status' =>  'success', 'data' => $book_details], 201);

        } catch (\Exception $e) {
            return response()->json(['status_code' => 400, 'status' =>  'failed', 'data' =>$e], 400);
        }
    }

    public function readBooks(){
        $all_books = Book::with('authors')->get();
        return response()->json(['status_code' => 200, 'status' =>  'success', 'data' => $all_books], 200);
    }

    public function updateBook(Request $request, $id){
        $book = Book::find($id)->first();
        $book->name =  isset($request['name']) ? $request['name'] : $book->name;
        $book->isbn =  isset($request['isbn']) ? $request['isbn'] : $book->isbn;
        $book->country =  isset($request['country']) ? $request['country'] : $book->country;
        $book->number_of_pages =  isset($request['number_of_pages']) ? $request['number_of_pages'] : $book->number_of_pages;
        $book->publisher =  isset($request['publisher']) ? $request['publisher'] : $book->publisher;
        $book->release_date =  isset($request['release_date']) ? $request['release_date'] : $book->release_date;
        try {
            $book->update();
            $authors_exist = isset($request['authors']) ? true : false;
            if($authors_exist){
                //remove previous authors
                $book->authors()->delete();

                $authors = $request['authors'];
                foreach($authors as $author){
                    Author::create([
                        'name' => $author,
                        'book_id' => $book->id
                    ]);
                }
            }
            $book_details = Book::with('authors')->whereId($book->id)->get();
        return response()->json(['status_code' => 200, 'status' =>  'success', "message"=>"The book '". $book->name ."' was updated successfully", 'data' => $book_details], 200);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400, 'status' =>  'failed', 'data' => $e], 400);
        }
    }

    public function showBook($id){

        $book = Book::find($id)->get();
        $book_details = Book::with('authors')->whereId($book->id)->get();
        
        return response()->json(['status_code' => 200, 'status' =>  'success', 'data' => $book_details], 200);
        
    }

    public function deleteBook($id){
        $book = Book::whereId($id)->first();
        $authors= Author::whereBookId($book->id)->get();
        foreach($authors as $author)
        {
            $author->delete();
        }
        $book->delete();
        
        return response()->json(['status_code' => 200, 'status' =>  'success', "message"=>"The book '". $book->name ."' was deleted successfully"], 200);
        
    }
}
