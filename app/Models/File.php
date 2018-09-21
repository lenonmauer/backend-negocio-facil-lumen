<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
  protected $hidden = [
    'upload_user_id', 'created_at', 'size', 'mime_type', 'updated_at',
  ];

  protected $fillable = [
    'upload_user_id', 'name', 'path', 'mime_type', 'size'
  ];

  public function getRelativePath() {
    return $this->path . '/' . $this->name;
  }

  public function getPublicPath() {
    return url($this->path . '/' . $this->name);
  }
}
