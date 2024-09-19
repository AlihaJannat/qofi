<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();

        return view('frontend.user.profile', compact('user'));
    }

    public function passwordChange()
    {
        return view('frontend.user.password');
    }

    public function favourite()
    {
        return view('frontend.user.favourite');
    }

    public function profileDelete()
    {
        $userId = auth()->id();
        auth()->logout();
        User::destroy($userId);

        return to_route('home');
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => "required|image|mimes:jpeg,png,jpg,svg,webp|max:2048"
        ]);
        $user = auth()->user();
        $previousImage = $user->image;
        $user->image = '/users/' . upload_image($request->file('image'), 'images/users/');
        $user->save();

        delete_image($previousImage, '/images');

        return response()->json('done');
    }
}
