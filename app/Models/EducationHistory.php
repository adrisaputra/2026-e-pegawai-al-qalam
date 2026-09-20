<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationHistory extends Model
{
    use HasFactory;
    protected $connection = 'simpeg_mysql';
    protected $fillable = [
        'employee_id',
        'educational_no',
        'educational_level',
        'institution',
        'major',
        'year',
        'file',
        'file2'
    ];
    
    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }
}
