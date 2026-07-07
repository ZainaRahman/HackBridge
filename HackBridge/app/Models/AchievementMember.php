<?php
// FILE: app/Models/AchievementMember.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AchievementMember extends Model {
    protected $fillable = ['achievement_id','user_id','role','contribution'];
    public function achievement() { return $this->belongsTo(Achievement::class); }
    public function user()        { return $this->belongsTo(User::class); }
}