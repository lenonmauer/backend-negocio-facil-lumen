<?php
namespace App\Helpers;

use \Facebook\Facebook;
use \Facebook\Exceptions\FacebookResponseException;
use \Facebook\Exceptions\FacebookSDKException;

class FacebookLogin
{
  public static function getUserProfile($token)
  {
    $fb = new \Facebook\Facebook([
      'app_id' => env('FACEBOOK_CLIENT_ID'),
      'app_secret' => env('FACEBOOK_SECRET'),
      'default_graph_version' => 'v3.0',
    ]);

    $response = $fb->get('/me?fields=name,picture.width(255).height(255),email', $token);
    $graphNode = $response->getGraphUser();

    return [
      'nome' => $graphNode->getName(),
      'email' => $graphNode->getEmail(),
      'foto_url' => $graphNode->getPicture()->getUrl(),
    ];
  }
}