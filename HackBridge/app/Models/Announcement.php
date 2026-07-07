<?php
// FILE: app/Models/Announcement.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $fillable = ['project_id','user_id','title','body'];
    public function project() { return $this->belongsTo(Project::class); }
    public function author()  { return $this->belongsTo(User::class, 'user_id'); }
}