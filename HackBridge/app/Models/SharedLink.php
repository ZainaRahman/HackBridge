<?php
// FILE: app/Models/SharedLink.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SharedLink extends Model {
    protected $fillable = ['project_id','user_id','title','url','type'];
    public function project() { return $this->belongsTo(Project::class); }
    public function user()    { return $this->belongsTo(User::class); }
}