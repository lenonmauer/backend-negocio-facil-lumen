<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
  protected $table = 'imoveis';

  const CATEGORIA_IMOVEL_NOVO = 'NOVO';
  const CATEGORIA_IMOVEL_USADO = 'USADO';

  protected $hidden = ['user_id', 'created_at', 'updated_at'];

  protected $fillable = [
    'user_id',
    'tipo_imovel',
    'categoria_imovel',
    'loc_lat',
    'loc_lng',
    'quantidade_quartos',
    'vagas_garagem',
    'medida_m2'
  ];
}
