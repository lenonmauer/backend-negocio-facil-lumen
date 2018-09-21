<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT as JwtFirebase;
use Firebase\JWT\ExpiredException;

class Jwt
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');

        if (empty($token)) {
          $error = ['error' => 'Token is required for this action.'];
          return response()->json($error, 401);
        }

        try {
            $credentials = JwtFirebase::decode($token, env('JWT_SECRET'), ['HS256']);
        }
        catch (ExpiredException $e) {
          $error = ['error' => $e->getMessage()];
          return response()->json($error, 401);
        }
        catch (Exception $e) {
          $error = ['error' => $e->getMessage()];
          return response()->json($error, 401);
        }

        $user = User::find($credentials->sub);

        if (empty($user)) {
          $error = ['error' => 'User of token does not exists'];
          return response()->json($error, 401);
        }

        $request->auth = $user;

        return $next($request);
    }
}
