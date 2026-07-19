<?php
// FILE: app/Models/GhostFlag.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GhostFlag extends Model {
    protected $fillable = ['project_id','flagged_user_id','flagged_by','reason'];
    public function project()     { return $this->belongsTo(Project::class); }
    public function flaggedUser() { return $this->belongsTo(User::class, 'flagged_user_id'); }
    public function flagger()     { return $this->belongsTo(User::class, 'flagged_by'); }
}