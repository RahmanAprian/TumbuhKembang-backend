<?php
// app/Models/Vaccine.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vaccine extends Model {
    protected $fillable = ['child_id','vaccine_name','recommended_age_months','given_date','is_done','location','notes'];
    protected $casts    = ['is_done' => 'boolean'];
    public function child() { return $this->belongsTo(Child::class); }
}
