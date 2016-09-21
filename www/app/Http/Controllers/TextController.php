<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Library\Twilio;
use App\Content;

class TextController extends Controller {

    /**
     * @access public
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * @access public
     * @param  Request $request
     */
	public function example(Request $request) {
        $content1 = Content::find(2);
        $content2 = Content::find(3);
        $content3 = Content::find(4);

        $data = [
            'content' => [
                $content1->content,
                $content2->content,
                $content3->content
            ]
        ];
        return view('text/view', $data);
    }
}
