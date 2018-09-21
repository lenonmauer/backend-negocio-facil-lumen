<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Log;
use Validator;
use App\Repositories\UserRepository;
use App\Repositories\ProfileRepository;
use App\Helpers\Jwt;
use App\Helpers\FacebookLogin;
use App\Helpers\Format;
use App\Helpers\Sanitize;

class UserController extends Controller
{
  public function postLoginFacebook(Request $request, UserRepository $userRepo,  ProfileRepository $profileRepo)
  {
    $accessToken = $request->post('token');

    try {
      $fbProfile = FacebookLogin::getUserProfile($accessToken);
    }
    catch(Exception $e) {
      return response()->json(['error' => 'Ocorreu um erro neste tentativa de login.'], 400);
    }

    $user = $userRepo->getByEmail($fbProfile['email']);

    if (empty($user)) {
      $user = $userRepo->create(['email' => $fbProfile['email']]);
      $profile = $profileRepo->create(['nome' => $fbProfile['nome'], 'user_id' => $user->id]);
    }
    else {
      $profile = $user->profile;
    }

    $userRepo->updateLastLoginToNow($user->id);
    $profileRepo->updateProfileFoto($profile['id'], $fbProfile['foto_url']);

    $user->load('profile');

    $token = Jwt::encodeUser($user);
    $userdata = [
      'email' => $user->email,
      'nome' => $user->profile->nome,
      'foto_perfil' => $user->profile->foto_facebook_url,
    ];
    $pageData = [
      'token' => $token,
      'user' => $userdata,
    ];

    return response()->json($pageData, 200);
  }
}
