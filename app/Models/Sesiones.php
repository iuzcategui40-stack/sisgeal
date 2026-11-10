<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Sesiones extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'sesiones';

    public function diariodebates()
    {
    // Un acto legislativo tiene muchas discusiones
    return $this->hasMany(Diario_debates::class, 'sesion_id', 'id');
    }

}
