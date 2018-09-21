<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Profile extends Model
{
  const ESTADO_CIVIL_SOLTEIRO = 'SOLTEIRO';
  const ESTADO_CIVIL_CASADO = 'CASADO';

  protected $hidden = [
    'id', 'created_at', 'updated_at', 'user_id',
  ];

  protected $fillable = [
    'user_id',
    'city_id',
    'foto_file_id',
    'nome',
    'cpf',
    'data_nascimento',
    'profissao',
    'phone_1',
    'phone_2',
    'logradouro',
    'numero',
    'bairro',
    'cep',
    'estado_civil',
  ];

  protected $appends = ['state_id'];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }

  public function city()
  {
    return $this->belongsTo('App\Models\City');
  }

  public function getStateIdAttribute()
  {
    return !empty($this->city) ? $this->city->state_id : null;
  }
}
