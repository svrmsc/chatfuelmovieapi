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

class ChatFuelQuickReplies {
    public $quick_replies = array();

    public function addButton(ChatFuelButton $button) {
        $this->quick_replies[] = $button;
    }

    public function setText($text) {
        $this->text = $text;
    }
}

class ChatFuelQuickReplyResponse {
    public $messages = array();
    public function __construct() {
        $this->messages[] = new ChatFuelQuickReplies();
    }
}

Route::get('/provaqr', function () {
    $response = new ChatFuelQuickReplyResponse();
    $button = new ChatFuelButton();
    $button->title = "prova";
    $response->messages[0]->addButton($button);
    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});

Route::get('/actors/search', function(Request $request) {
    $query = null;
    if ($request->has('q')) {
        $query = $request->q;
    }

    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);


    $headers = array('Accept' => 'application/json');
    $res = Requests::get('https://api.themoviedb.org/3/search/person?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it&query=' . $query, $headers);
    $resp_obj = json_decode($res->body);
    $actors = $resp_obj->results;

    $response = new ChatFuelQuickReplyResponse();
    $response->messages[0]->setText("Quale di questi?");
    $i = 0;
    foreach ($actors as $actor) {
        $i++;
        $button = new ChatFuelButton();
        $button->title = $actor->name;
        $id = $actor->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/actor/" . $id . "/movies?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);
        if ($i ==11) break;
    }

    if($i==0){
        $messages = array();
        $message='Scusami non ho trovato risultati...';
        $messages[] = $message;
        $response = new ChatFuelTextResponse($messages);
    }


    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;

});

Route::get('/directors/search', function(Request $request) {
    $query = null;
    if ($request->has('q')) {
        $query = $request->q;
    }

    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);


    $headers = array('Accept' => 'application/json');
    $res = Requests::get('https://api.themoviedb.org/3/search/person?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it&query=' . $query, $headers);
    $resp_obj = json_decode($res->body);
    $actors = $resp_obj->results;

    $response = new ChatFuelQuickReplyResponse();
    $response->messages[0]->setText("Quale di questi?");
    $i = 0;
    foreach ($actors as $actor) {
        $i++;
        $button = new ChatFuelButton();
        $button->title = $actor->name;
        $id = $actor->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/director/" . $id . "/movies?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);
        if ($i ==11) break;
    }

    if($i==0){
        $messages = array();
        $message='Scusami non ho trovato risultati...';
        $messages[] = $message;
        $response = new ChatFuelTextResponse($messages);
    }


    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;

});

Route::get('/movies/search', function(Request $request) {
    $query = null;
    if ($request->has('q')) {
        $query = $request->q;
    }

    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);


    $headers = array('Accept' => 'application/json');
    $res = Requests::get('https://api.themoviedb.org/3/search/movie?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it&query=' . $query, $headers);
    $resp_obj = json_decode($res->body);
    $movies = $resp_obj->results;

    $response = new ChatFuelButtonResponse();
    $response->messages[0]->attachment->payload->setText("Quale di questi?");
    $i = 0;
    foreach ($movies as $movie) {
        $i++;
        $button = new ChatFuelButton();
        $button->title = $movie->title ;
        $id = $movie->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/movies/" . $id . "/select?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->attachment->payload->addButton($button);
        if ($i == 3) break;
    }

    if($i==0){
        $messages = array();
        $message='Scusami non ho trovato risultati...';
        $messages[] = $message;
        $response = new ChatFuelTextResponse($messages);
    }


    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});

Route::get('discover/genre', function(Request $request){
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $response = new ChatFuelQuickReplyResponse();
    $response->messages[0]->setText("Che genere di film vuoi vedere? Scegli tra i seguenti...");


        $button = new ChatFuelButton();
        $button->title = 'Commedia';
        $id = 35;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Azione';
        $id = 28;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Thriller';
        $id = 53;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Animazione';
        $id = 16;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Horror';
        $id = 27;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Avventura';
        $id = 12;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Drammatico';
        $id = 18;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Fantasy';
        $id = 14;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Documentario';
        $id = 99;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Fantascienza';
        $id = 878;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);

        $button = new ChatFuelButton();
        $button->title = 'Musica';
        $id = 10402;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/discover/" . $id . "/?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);


    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;


});

Route::get('discover/{id}', function(Request $request){

    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }
    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;

    $res = Requests::get('https://api.themoviedb.org/3/discover/movie?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it&sort_by=popularity.desc&include_adult=false&include_video=false&page=1&with_genres=' . $id , $headers);

    $resp_obj = json_decode($res->body);
    $results = $resp_obj->results;
    $i=0;


    $response = new ChatFuelQuickReplyResponse();
    $response->messages[0]->setText("Vediamo se può interessarti uno di questi...");

    foreach($results as $movie){
        $i++;
        $button = new ChatFuelButton();
        $button->title = $movie->title;
        $id = $movie->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/movies/" . $id . "/select?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);
        if ($i ==11) break;
    }

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;

});


Route::get('actor/{id}/movies', function(Request $request){
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/person/' . $id . '/movie_credits?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it', $headers);
    $resp_obj = json_decode($res->body);
    $cast = $resp_obj->cast;
    $i=0;


    $response = new ChatFuelQuickReplyResponse();
    $response->messages[0]->setText("Lo trovi nei seguenti film...");

    foreach($cast as $actor){
        $i++;
        $button = new ChatFuelButton();
        $button->title = $actor->title;
        $id = $actor->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/movies/" . $id . "/select?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);
        if ($i ==11) break;
    }

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});


Route::get('director/{id}/movies', function(Request $request){
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/person/' . $id . '/movie_credits?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it', $headers);
    $resp_obj = json_decode($res->body);
    $crew = $resp_obj->crew;
    $i=0;


    $response = new ChatFuelQuickReplyResponse();
    $response->messages[0]->setText("Ha diretto i seguenti film...");

    foreach($crew as $director){
        $i++;
        $button = new ChatFuelButton();
        $button->title = $director->title;
        $id = $director->id;
        $button->url = "https://chatfuelmovieapi.herokuapp.com/api/movies/" . $id . "/select?s=" . $mood . "&idu=" . $id_utente . "&uname=" . $user_name;
        $response->messages[0]->addButton($button);
        if ($i ==11) break;
    }

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});


Route::get('movies/{id}/select', function(Request $request) {
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }


    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $response = new ChatFuelButtonResponse();
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/movie/' . $id . '?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it', $headers);
    $resp_obj = json_decode($res->body);
    $vote_average = $resp_obj->vote_average;
    $title_film = $resp_obj->title;
    $tagline = $resp_obj->tagline;
    $release_date = $resp_obj->release_date;
    $year = explode("-", $release_date);
    $genre = $resp_obj->genres[0]->name;


    $response->messages[0]->attachment->payload->setText($tagline . " ". $title_film . " è un " . $genre . " del " . $year[0]  . " e il suo voto medio è: ". $vote_average . "/10. " . "Cosa vuoi sapere?");

    $button = new ChatFuelButton();
    $button->title = "Trama";
    $button->url = 'https://chatfuelmovieapi.herokuapp.com/api/movies/' . $id . '/plot?s=' . $mood . "&idu=" . $id_utente . "&uname=" . $user_name ;
    $response->messages[0]->attachment->payload->addButton($button);

    $button = new ChatFuelButton();
    $button->title = "Attori";
    $button->url = 'https://chatfuelmovieapi.herokuapp.com/api/movies/' . $id . '/actors?s=' . $mood . "&idu=" . $id_utente . "&uname=" . $user_name ;
    $response->messages[0]->attachment->payload->addButton($button);

    $button = new ChatFuelButton();
    $button->title = "Regista";
    $button->url = 'https://chatfuelmovieapi.herokuapp.com/api/movies/' . $id . '/videos?s=' . $mood . "&idu=" . $id_utente . "&uname=" . $user_name ;
    $response->messages[0]->attachment->payload->addButton($button);

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});

Route::get('/movies/{id}/plot', function(Request $request) {
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/movie/' . $id . '?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it', $headers);
    $resp_obj = json_decode($res->body);
    $plot = $resp_obj->overview;
    $message = "Da themoviedb:";
    $messages = array();
    $messages[] = $message;
    $messages[] = $plot;

    $response = new ChatFuelTextResponse($messages);

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});

Route::get('/movies/{id}/actors', function(Request $request){
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }
    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/movie/' . $id . '/credits' . '?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it', $headers);
    $resp_obj = json_decode($res->body);

    $cast = $resp_obj->cast;
    $i=0;
    $messages = array();
    $messages[0]='Gli attori principali sono:';
    for($i;$i<4;$i++){
        $messages[$i+1] = $cast[$i]->name . " è " . $cast[$i]->character;
    }

    $response = new ChatFuelTextResponse($messages);

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;


});

Route::get('/movies/{id}/director', function(Request $request){
    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }
    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;
    $res = Requests::get('https://api.themoviedb.org/3/movie/' . $id . '/credits' . '?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=it', $headers);
    $resp_obj = json_decode($res->body);

    $crew = $resp_obj->crew;
    $i=0;
    $messages = array();

    foreach($crew as $director){
        if($director->job == 'Director'){
            $messages[$i]=$director->name;
            $i++;
        }
    }



    $response = new ChatFuelTextResponse($messages);

    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;


});

Route::get('/movies/{id}/videos', function(Request $request){

    if ($request->has('s')) {
        $mood = $request->s;
    }

    if ($request->has('idu')) {
        $id_utente = $request->idu;
    }

    if ($request->has('uname')) {
        $user_name = $request->uname;
    }

    $user_name = str_replace(" ", "+", $user_name);

    $headers = array('Accept' => 'application/json');
    $id = $request->id;

    $res = Requests::get('https://api.themoviedb.org/3/movie/' . $id . '/videos' . '?api_key=8a63e1f0e24bbd552535468ca3a3f323&language=en-US', $headers);

    $resp_obj = json_decode($res->body);

    $key_video = $resp_obj->results[0]->key;

    $messages = array();
    $messages[] = 'Ecco il trailer: '.'https://www.youtube.com/watch?v='.$key_video;

    $response = new ChatFuelTextResponse($messages);
    $response = json_encode($response);
    $response = str_replace("\/", "/", $response);
    return $response;
});



