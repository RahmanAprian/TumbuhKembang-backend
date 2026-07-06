<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model {
    protected $fillable = ['child_id','month','is_achieved','achieved_date'];
    protected $casts    = ['is_achieved' => 'boolean'];
}