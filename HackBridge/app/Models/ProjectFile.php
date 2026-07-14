<?php
// FILE: app/Models/ProjectFile.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id', 'user_id', 'title', 'original_name', 'path', 'mime_type', 'size',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Human-readable size, e.g. "1.4 MB"
    public function humanSize(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    // Emoji icon based on extension, for the resource hub UI
    public function icon(): string
    {
        $ext = strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
        return match (true) {
            in_array($ext, ['pdf'])                    => '📕',
            in_array($ext, ['doc', 'docx'])             => '📄',
            in_array($ext, ['ppt', 'pptx'])             => '📊',
            in_array($ext, ['xls', 'xlsx', 'csv'])      => '📈',
            in_array($ext, ['zip', 'rar', '7z'])        => '🗜️',
            in_array($ext, ['png', 'jpg', 'jpeg', 'gif','webp']) => '🖼️',
            in_array($ext, ['txt', 'md'])                => '📝',
            default                                      => '📁',
        };
    }
}