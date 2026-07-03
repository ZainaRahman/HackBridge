<?php
// FILE: database/migrations/2024_01_02_000001_create_missing_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Kanban Tasks
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'in_progress', 'done'])->default('todo');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
        });

        // Team Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        // Shared Links
        Schema::create('shared_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('url');
            $table->enum('type', ['GitHub', 'Drive', 'Figma', 'Notion', 'Other'])->default('Other');
            $table->timestamps();
        });

        // Achievements / Hall of Fame
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('result', ['Champion', 'Runner-Up', 'Top-3', 'Finalist', 'Participant'])->default('Participant');
            $table->string('hackathon_name');
            $table->string('demo_link')->nullable();
            $table->string('github_link')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->date('achieved_at')->nullable();
            $table->timestamps();
        });

        // Achievement Team Members (for spotlight)
        Schema::create('achievement_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('achievement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('Member');
            $table->string('contribution')->nullable();
            $table->timestamps();
        });

        // Mentor Profiles
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('expertise');
            $table->text('availability_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Mentor Requests
        Schema::create('mentor_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->text('message');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // Ghost flags
        Schema::create('ghost_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flagged_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flagged_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ghost_flags');
        Schema::dropIfExists('mentor_requests');
        Schema::dropIfExists('mentors');
        Schema::dropIfExists('achievement_members');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('shared_links');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('tasks');
    }
};