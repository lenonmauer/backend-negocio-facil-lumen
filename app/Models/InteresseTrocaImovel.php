<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteresseTrocaImovel extends Model
{
  protected $table = 'interesses_troca_imoveis';

  protected $hidden = ['user_id', 'created_at', 'updated_at'];

  protected $fillable = ['user_id', 'imovel_meu_id', 'imovel_interesse_id', 'resumo'];

  public function imovel_meu()
  {
    return $this->belongsTo('App\Models\Imovel');
  }

  public function imovel_interesse()
  {
    return $this->belongsTo('App\Models\Imovel');
  }
}
