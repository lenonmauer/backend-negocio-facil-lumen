<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
      Validator::extend('cep', 'App\Rules\Cep@passes');
      Validator::extend('cpf', 'App\Rules\Cpf@passes');
      Validator::extend('cnpj', 'App\Rules\Cnpj@passes');
      Validator::extend('date_br', 'App\Rules\DateBr@passes');
    }

    public function register()
    {

    }
}
