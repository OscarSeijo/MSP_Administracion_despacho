<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Mail\TestEmail;

use App\User;
use Auth;
use Session;
use Cookie; 
use File;

class dashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){


       if( Auth::user() !== null ){
            return view('home');
        } else{
            return view('index');
            
        }
        
    }



    public function emailsample(){
    

        $data = ['message' => 'This is a test!'];

        Mail::to('john@example.com')->send(new TestEmail());

    }
 
   
}
