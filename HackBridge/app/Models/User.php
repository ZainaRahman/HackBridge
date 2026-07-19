<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'department', 'year', 'university',
        'bio', 'github', 'linkedin',
        'availability', 'avatar',
        // NOTE: 'is_admin' is intentionally NOT mass-assignable here.
        // It's set explicitly in RegisteredUserController (dev-only) or via tinker/seeder,
        // never taken directly from request input via User::create($request->all()).
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    // ── Relationships ──
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
                    ->withPivot('level')
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    // Projects this user was accepted onto as a team member (not owner).
    // Distinct from projects() above, which only returns projects they created.
    public function joinedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_applications', 'user_id', 'project_id')
                    ->wherePivot('status', 'accepted')
                    ->withTimestamps();
    }

    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function applications()
    {
        return $this->hasMany(ProjectApplication::class);
    }

    public function endorsementsReceived()
    {
        return $this->hasMany(Endorsement::class, 'endorsed_id');
    }

    // ── Helpers ──
    public function initials(): string
    {
        $parts = explode(' ', $this->name);
        return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }

    public function availabilityLabel(): string
    {
        return match($this->availability) {
            'open'    => 'Open to Team Up',
            'looking' => 'Looking for 1 More',
            'busy'    => 'Busy This Semester',
            'in_team' => 'In a Team',
            default   => 'Open to Team Up',
        };
    }

    public function availabilityColor(): string
    {
        return match($this->availability) {
            'open'    => '#22C55E',
            'looking' => '#F59E0B',
            'busy'    => '#EF4444',
            'in_team' => '#8B5CF6',
            default   => '#22C55E',
        };
    }
}