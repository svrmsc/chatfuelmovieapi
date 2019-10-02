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

    public function setText($text) {
        $this->text = $text;
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



class ChatFuelButtonResponse  {
    public $messages = array();
    public function __construct()
    {
        $this->messages[] = new ChatFuelMessages();
    }
}

class ChatFuelText {
    public $text;
    public function __construct($text)
    {
        $this->text = $text;
    }
}

class ChatFuelTextResponse {
    public $messages = array();
    public function __construct(array $messages)
    {
        foreach ($messages as $message) {
            $this->messages[] = new ChatFuelText($message);
        }
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

    $response = new ChatFuelButtonResponse();
    $response->messages[0]->attachment->payload->setText("Quale film intendi di preciso?");
    $i = 0;
    foreach ($movies as $movie) {
        $i++;
        $button = new ChatFuelButton();
        $button->title = $movie->title;
        $id = $movie->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/movies/" . $id . "/select";
        $response->messages[0]->attachment->payload->addButton($button);
        if ($i == 3) break;
    }


    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});

Route::get('movies/{id}/select', function(Request $request) {
    $response = new ChatFuelButtonResponse();
    $id = $request->id;
    $response->messages[0]->attachment->payload->setText("Cosa vuoi sapere del film?");
    $button = new ChatFuelButton();
    $button->title = "Plot";
    $button->url = 'https://chatfuelmovieapi.herokuapp.com/api/movies/' . $id . '/plot';
    $response->messages[0]->attachment->payload->addButton($button);
    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});

Route::get('/movies/{id}/plot', function(Request $request) {
    $headers = array('Accept' => 'application/json');
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/movie/' . $id . '?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=en-US', $headers);
    $resp_obj = json_decode($res->body);
    $plot = $resp_obj->overview;
    $message = "Secondo The Movie DB, la trama è questa:";
    $messages = array();
    $messages[] = $message;
    $messages[] = $plot;

    $response = new ChatFuelTextResponse($messages);

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});
