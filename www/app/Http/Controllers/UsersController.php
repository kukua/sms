<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

class UsersController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$users = User::all();
		return view('users.index', ['users' => $users]);
	}

	public function create() {
		$user = new User();
		return view('users.create', ['user' => $user]);
	}

	public function delete($id) {
		if (\Auth::user()->id != $id) {
			$user = User::destroy($id);
		}

		return redirect()->route("users");
	}

	public function store(Request $request) {
		$this->_validate($request);

		$user = new User();
		$user->name		= $request->name;
		$user->password = \Hash::make($request->password);
		$user->email	= $request->email;
		$user->save();

		return redirect()->route("users");
	}

	protected function _validate(Request $request) {
		$this->validate($request, [
			'name'		=> 'required|max:255',
			'email'		=> 'required|max:255',
			'password'	=> 'required|max:255|confirmed',
			'password_confirmation'	=> 'required|max:255'
		]);
	}
}
