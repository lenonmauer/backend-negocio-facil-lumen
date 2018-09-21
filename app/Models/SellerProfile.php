<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
  const TIPO_PESSOA_FISICA = 'FISICA';
  const TIPO_PESSOA_JURIDICA = 'JURIDICA';

  protected $hidden = [
    'created_at', 'updated_at', 'user_id', 'logo_file',
  ];

  protected $fillable = [
    'city_id',
    'user_id',
    'logo_file_id',
    'nome',
    'tipo_pessoa',
    'cpf',
    'cnpj',
    'facebook_link',
    'instagram_link',
    'phone_1',
    'phone_2',
    'logradouro',
    'numero',
    'bairro',
    'cep',
    'resumo',
    'site',
  ];

  protected $appends = ['logo_url', 'state_id'];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  public function city()
  {
    return $this->belongsTo('App\Models\City');
  }

  public function logo_file()
  {
    return $this->belongsTo('App\Models\File');
  }

  public function getLogoUrlAttribute()
  {
    return isset($this->attributes['logo_file_id']) ? $this->logo_file->getPublicPath() : null;
  }

  public function getStateIdAttribute()
  {
    return !empty($this->city) ? $this->city->state_id : null;
  }
}
