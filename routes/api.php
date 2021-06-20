<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

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

Route::get('/http-status', function (Request $request) {
    $sbusApiUrl = env("SBUS_API_URL");
    $sbusApiToken = env("SBUS_API_TOKEN");
    
    $data = urlencode($request->query("url"));
    $apiUrl = $sbusApiUrl . "website-monitoring/http-status?url=$data";

    $client = new GuzzleHttp\Client();

    $result = $client->get($apiUrl, [
        'headers' => ['x-auth-token' => $sbusApiToken],
        'http_errors' => false
    ]);

    $jsonStr = $result->getBody()->getContents();
    $jsonArr = json_decode($jsonStr, true);
    return (new Response($jsonArr, $result->getStatusCode()));
});

Route::post('/data-multiplier', function (Request $request) {
    $data = $request->json()->all();

    $apiUrl = env("SBUS_API_URL")."file-services/multiply-data";
    
    $client = new GuzzleHttp\Client();

    $result = $client->post($apiUrl, [
        'headers' => ['x-auth-token' => env("SBUS_API_TOKEN")],
        'http_errors' => false,
        'json' => $data,
    ]);

    $jsonStr = $result->getBody()->getContents();
    $jsonArr = json_decode($jsonStr, true);
    return (new Response($jsonArr, $result->getStatusCode()));
});


Route::post('/site-file-checker', function (Request $request) {
    $data = $request->json()->all();

    $apiUrl = env("SBUS_API_URL")."website-monitoring/find-url-on-site";
    
    $client = new GuzzleHttp\Client();

    $result = $client->post($apiUrl, [
        'headers' => ['x-auth-token' => env("SBUS_API_TOKEN")],
        'http_errors' => false,
        'json' => $data,
    ]);

    $jsonStr = $result->getBody()->getContents();
    $jsonArr = json_decode($jsonStr, true);
    return (new Response($jsonArr, $result->getStatusCode()));
});
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
/*
  public function datamultiplier() {
        if ($_POST) {
            $apiUrl = "https://sbus.t.thehost.guru/api/file-services/multiply-data";
            $apiToken = "sbus-user-jcgbvfc2@ae5cf6224090d9e0f75c.tst!e46df53302bfcf798dc64562818fa7f3be5c3a49e95116876f4213082a2a";

            //require_once './vendor/autoload.php';

            $dataStr = file_get_contents('php://input');
            $data = json_decode($dataStr, true);

            $client = new GuzzleHttp\Client();

            $result = $client->post($apiUrl, [
                'headers' => ['x-auth-token' => $apiToken],
                'http_errors' => false,
                'json' => $data,
            ]);

            $jsonStr = $result->getBody()->getContents();
            http_response_code($result->getStatusCode());

            header('Content-Type: application/json');
        }
        return view('data-multiplier');
    }*/