<?php
namespace App\Http\Middleware;

use Closure;

class TrimStrings
{
  protected $except = [
    'password',
    'password_confirmation',
  ];

  public function handle($request, Closure $next)
  {
    $input = $request->all();

    if ($input) {
      array_walk_recursive($input, function (&$item, $key) {
        if (is_string($item) && !str_contains($key, 'password')) {
          $item = trim($item);
        }
        $item = $item == "" ? null : $item;
      });

      $request->merge($input);
    }

    return $next($request);
  }
}
