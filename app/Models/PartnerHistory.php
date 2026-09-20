<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerHistory extends Model
{
    use HasFactory;
    protected $connection = 'simpeg_mysql';
    protected $fillable = [
        'employee_id',
        'name',
        'birthplace',
        'birthdate',
        'work'
    ];

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
}
