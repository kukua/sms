<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Geocoder;
use App\Http\Requests;
use App\Client;

class ClientsController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$clients = Client::all();
		return view('clients.index', ['clients' => $clients]);
	}

	public function add() {
		$client = new Client();
		return view('clients.create', ['client' => $client]);
	}

	public function edit($id) {
		$client = Client::find($id);
		return view('clients.update', ['client' => $client]);
	}

	public function create(Request $request) {
		$client = new Client();
		$this->_store($request, $client);
		return redirect()->route("clients");
	}

	public function update(Request $request, $id) {
		$client = Client::find($id);
		$this->_store($request, $client);
		return redirect()->route("clients");
	}

	public function delete(Request $request, $id) {
		$client = Client::destroy($id);
		return redirect()->route("clients");
	}

	protected function _store(Request $request, $client) {
		$this->_validate($request, $client->id);
		$googleResults = $this->_getCoordinates($request);

		if ($googleResults === false) {
			throw new Exception("No results for this city");
		}

		$client->name  = $request->name;
		$client->type  = $request->type;
		$client->phone = $request->phone;
		$client->city  = $request->city;
		$client->lat   = $googleResults->lat;
		$client->lng   = $googleResults->lng;

		$client->save();
	}

	protected function _validate($request, $id) {
		$this->validate($request, [
			'name'  => 'required|max:255',
			'phone' => 'required|unique:clients,phone,' . $id,
			'city'  => 'required|max:255'
		]);
	}

	protected function _getCoordinates(Request $request) {
		$param = ["address" => $request->city];
		$api = json_decode(Geocoder::geocode('json', $param));
		if (isset($api->results[0])) {
			return $api->results[0]->geometry->location;
		}
		return false;
	}
}
