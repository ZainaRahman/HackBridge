<?php
// FILE: app/Models/MentorRequest.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model {
    protected $fillable = ['mentor_id','requester_id','project_id','message','status'];
    public function mentor()    { return $this->belongsTo(Mentor::class); }
    public function requester() { return $this->belongsTo(User::class, 'requester_id'); }
    public function project()   { return $this->belongsTo(Project::class); }
}