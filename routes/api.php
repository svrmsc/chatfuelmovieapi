<?php

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Model\Model;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

class ChatFuelButton  {
    public $title;
    public $url;
    public $type = "json_plugin_url";
}

class ChatFuelPayload  {
    public $template_type = "button";
    public $text = "hello!";
    public $buttons = array();

    public function addButton(ChatFuelButton $button) {
        $this->buttons[] = $button;
    }
}

class ChatFuelAttachment  {
    public $type = "template";
    public $payload;
    public function __construct() {
        $this->payload = new ChatFuelPayload();
    }

}

class ChatFuelMessages  {
    public $attachment = array();
    public function __construct()
    {
        $this->attachment = new ChatFuelAttachment();
    }
}

class ChatFuelResponse  {
    public $messages;
    public function __construct()
    {
        //$this->attributes['messages'] = new ChatFuelMessages();
        $this->messages = new ChatFuelMessages();
    }
}

Route::get('/movies/search', function(Request $request) {
    $query = null;
    if ($request->has('q')) {
        $query = $request->q;
    }

    $headers = array('Accept' => 'application/json');
    $res = Requests::get('https://api.themoviedb.org/3/search/movie?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=en-US&query=' . $query, $headers);
    $resp_obj = json_decode($res->body);
    $movies = $resp_obj->results;

    $response = new ChatFuelResponse();

    //dd($movies);

    foreach ($movies as $movie) {
        //dd($movie->title);
        $button = new ChatFuelButton();
        $button->title = $movie->title;
        $id = $movie->id;
        $response->messages->attachment->payload->addButton($button);
    }

    return json_encode($response);
});
