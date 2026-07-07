<?php
// FILE: app/Models/Achievement.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model {
    protected $fillable = ['project_id','title','description','result','hackathon_name','demo_link','github_link','is_featured','achieved_at'];
    protected $casts = ['is_featured' => 'boolean', 'achieved_at' => 'date'];
    public function project() { return $this->belongsTo(Project::class); }
    public function members() { return $this->hasMany(AchievementMember::class); }
}