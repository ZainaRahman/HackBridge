<?php
// FILE: app/Models/Task.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    protected $fillable = ['project_id','created_by','assigned_to','title','description','status','priority'];
    public function project()    { return $this->belongsTo(Project::class); }
    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
    public function assignee()   { return $this->belongsTo(User::class, 'assigned_to'); }
}