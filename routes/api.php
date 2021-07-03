<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

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

 /*
 * Cron
 */
Route::get("sendEmailNotifications", [CustomAuthController::class, 'sendEmailNotifications']);

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
