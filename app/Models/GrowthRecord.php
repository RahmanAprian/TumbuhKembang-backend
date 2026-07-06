<?php
// app/Models/GrowthRecord.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GrowthRecord extends Model {
    protected $fillable = ['child_id','weight','height','head_circumference','age_months','recorded_at','nutritional_status','notes'];
    public function child() { return $this->belongsTo(Child::class); }
}
