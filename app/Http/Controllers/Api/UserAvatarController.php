<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store(Request $request)
    {
        $user = auth()->user();
 
        if($request->hasFile("avatar"))
        {
            $avatar = $request->avatar;
            $avatar_new_name = time(). $avatar->getClientOriginalName();

            $avatar->move('uploads/avatars', $avatar_new_name);

            $user->avatar_path = 'uploads/avatars/' . $avatar_new_name;

            $user->save();
        }

    	return response([], 204);
    }
}
