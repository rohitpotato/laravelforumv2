<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
    	try{

    		$user = User::where('confirmation_token', request('token'))->firstorFail()->update(['confirmed' => 1]);

    	
    		
    	} catch (\Exception $e) {

    		return redirect('/threads')->with('flash', 'Unknown token');
    	}

    	return redirect('/threads')->with('flash', 'You may now post to the forum');
    }
}
