<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoImovel extends Model
{
  protected $table = 'tipo_imoveis';

  protected $hidden = ['created_at', 'updated_at'];

  protected $fillable = ['nome'];
}
