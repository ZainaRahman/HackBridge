<?php
// FILE: app/Models/Mentor.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model {
    protected $fillable = ['user_id','expertise','availability_note','is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function user()     { return $this->belongsTo(User::class); }
    public function requests() { return $this->hasMany(MentorRequest::class); }
}