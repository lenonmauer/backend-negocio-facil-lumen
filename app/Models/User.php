<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Validator;

class User extends Model
{
    protected $casts = [
        'admin' => 'boolean',
        'email_confirmed' => 'boolean',
    ];

    protected $fillable = [
        'email', 'password'
    ];

    protected $hidden = [
        'id', 'password', 'created_at', 'updated_at', 'last_login', 'admin',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function seller_profiles()
    {
        return $this->hasMany('App\Models\SellerProfile');
    }

    public static function rules()
    {
        return [
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6',
        ];
    }
}
