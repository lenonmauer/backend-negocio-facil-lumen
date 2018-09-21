<?php

use Illuminate\Http\Request;

$router->get('/', function () use ($router) {
  return $router->app->version();
});

$router->group(['prefix' => 'v1/'], function () use ($router) {
  $router->get('/', function () use ($router) {
    return 'api works';
  });

  $router->group(['prefix' => '/upload'], function () use ($router) {
    $router->post('/photo', [
      'uses' => 'UploadController@postPhoto',
      'middleware' => 'jwt.auth'
    ]);
  });

  $router->group(['prefix' => '/user'], function () use ($router) {
    $router->post('/login-facebook', 'UserController@postLoginFacebook');

    $router->put('/profile', [
      'uses' => 'ProfileController@putProfile',
      'middleware' => 'jwt.auth'
    ]);

    $router->get('/my-profile', [
      'uses' => 'ProfileController@getMyProfile',
      'middleware' => 'jwt.auth'
    ]);

    $router->get('/my-seller-profiles', [
      'uses' => 'ProfileController@getMySellerProfiles',
      'middleware' => 'jwt.auth'
    ]);

    $router->post('/seller-profile', [
      'uses' => 'ProfileController@postSellerProfile',
      'middleware' => 'jwt.auth'
    ]);

    $router->put('/seller-profile/{profile_id}', [
      'uses' => 'ProfileController@putSellerProfile',
      'middleware' => 'jwt.auth'
    ]);
  });

  $router->group(['prefix' => '/interesse-troca', 'middleware' => 'jwt.auth'], function () use ($router) {
    $router->post('/imovel', 'InteresseTrocaController@postImovel');
    $router->put('/imovel/{interesse_id}', 'InteresseTrocaController@putImovel');
    $router->delete('/imovel/{interesse_id}', 'InteresseTrocaController@deleteImovel');
  });

  $router->get('/tipo-imoveis', 'TipoImoveisController@getTipoImoveis');

  $router->get('/states', 'StateController@getStates');

  $router->get('/cities/{state_id}', 'StateController@getCities');
});
