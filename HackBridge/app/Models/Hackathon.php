<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Hackathon extends Model {
    protected $fillable = [
        'title','organizer','description','category',
        'type','mode','deadline','prize','registration_link',
        'banner_emoji','is_intra_university','is_featured'
    ];
    protected $casts = ['deadline' => 'datetime', 'is_intra_university' => 'boolean', 'is_featured' => 'boolean'];
    public function projects() { return $this->hasMany(Project::class); }
}