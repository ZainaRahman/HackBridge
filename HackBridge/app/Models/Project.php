<?php
// FILE: app/Models/Project.php  (UPDATED — replace existing file)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'owner_id', 'title', 'description', 'category',
        'required_skills', 'prerequisites', 'team_size',
        'hackathon_id', 'dept_preference', 'deadline', 'status'
    ];

    protected $casts = ['required_skills' => 'array'];

    public function owner()        { return $this->belongsTo(User::class, 'owner_id'); }
    public function hackathon()    { return $this->belongsTo(Hackathon::class); }
    public function applications() { return $this->hasMany(ProjectApplication::class); }
    public function team()         { return $this->hasOne(Team::class); }

    // ── New relationships for missing features ──
    public function tasks()        { return $this->hasMany(Task::class); }
    public function announcements(){ return $this->hasMany(Announcement::class); }
    public function sharedLinks()  { return $this->hasMany(SharedLink::class); }
    public function ghostFlags()   { return $this->hasMany(GhostFlag::class); }
    public function achievements() { return $this->hasMany(Achievement::class); }
}