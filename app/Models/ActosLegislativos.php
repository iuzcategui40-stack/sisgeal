<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class ActosLegislativos extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'actos_legislativos';

      public function tipoacto()
    {
        return $this->belongsTo(TipoActos::class, 'tipoacto_id');
    }

     public function discusiones()
    {
    // Un acto legislativo tiene muchas discusiones
    return $this->hasMany(Discusiones::class, 'actolegislativo_id', 'id');
    }
}
