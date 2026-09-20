<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentHistory extends Model
{
    use HasFactory;
    protected $connection = 'simpeg_mysql';
    protected $fillable = [
        'employee_id',
        'category',
        'birthplace',
        'birthdate',
        'address'
    ];

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
}
