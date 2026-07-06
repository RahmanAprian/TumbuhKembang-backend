<?php
// app/Models/Child.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Child extends Model {
    protected $fillable = ['user_id','name','birth_date','gender','blood_type'];
    public function user()          { return $this->belongsTo(User::class); }
    public function growthRecords() { return $this->hasMany(GrowthRecord::class); }
    public function vaccines()      { return $this->hasMany(Vaccine::class); }
}
