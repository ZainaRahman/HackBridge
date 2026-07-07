<?php
// FILE: app/Models/Endorsement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endorsement extends Model
{
    protected $fillable = ['endorser_id', 'endorsed_id', 'skill_id', 'note'];

    public function endorser()
    {
        return $this->belongsTo(User::class, 'endorser_id');
    }

    public function endorsed()
    {
        return $this->belongsTo(User::class, 'endorsed_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}