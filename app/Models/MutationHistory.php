<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationHistory extends Model
{
    use HasFactory;
    protected $connection = 'simpeg_mysql';
    protected $fillable = [
        'employee_id',
        'date',
        'origin',
        'to',
    ];
    
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
    
    public function originWorkUnit()
    {
        return $this->belongsTo(WorkUnit::class, 'origin');
    }

    public function toWorkUnit()
    {
        return $this->belongsTo(WorkUnit::class, 'to');
    }

}
