<?php
namespace Tests\Concern;

use App\Models\User;
use App\Models\Profile;
use App\Helpers\Jwt;

trait AttachJwtToken
{
  protected $loginUser;

  public function loginAs(User $user)
  {
    $this->loginUser = $user;

    return $this;
  }

  public function getLoginUser()
  {
    return $this->loginUser;
  }

  protected function getJwtToken()
  {
    $user = $this->loginUser ?: factory(\App\Models\User::class)->create();

    if (empty($user->profile)) {
      factory(Profile::class)->create(['user_id' => $user->id]);
    }

    return Jwt::encodeUser($user);
  }

  public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
  {
    if ($this->requestNeedsToken($method, $uri)) {
      $server = $this->attachToken($server);
    }

    return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
  }

  protected function requestNeedsToken($method, $uri)
  {
    return '/user/login' !== $uri;
  }

  protected function attachToken(array $server)
  {
    return array_merge(
      $server,
      $this->transformHeadersToServerVars([
        'Authorization' => $this->getJwtToken()
      ])
    );
  }
}
