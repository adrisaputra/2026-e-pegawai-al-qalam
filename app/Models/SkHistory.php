<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkHistory extends Model
{
    use HasFactory;
    protected $connection = 'simpeg_mysql';
    protected $fillable = [
        'employee_id',
        'name',
        'sk_number',
        'date',
        'file'
    ];
    
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
}
