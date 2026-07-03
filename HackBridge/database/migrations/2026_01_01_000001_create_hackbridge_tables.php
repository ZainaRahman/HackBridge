<?php
// ============================================================
// FILE: database/migrations/2024_01_01_000001_create_hackbridge_tables.php
// Run: php artisan migrate
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add extra columns to users table (Breeze already creates users)
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('name');
            $table->string('university')->default('KUET')->after('department');
            $table->integer('year')->nullable()->after('university');
            $table->text('bio')->nullable()->after('year');
            $table->string('github')->nullable()->after('bio');
            $table->string('linkedin')->nullable()->after('github');
            $table->enum('availability', ['open','looking','busy','in_team'])->default('open')->after('linkedin');
            $table->string('avatar')->nullable()->after('availability');
        });

        // Skills
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['Programming','Hardware','Design','Research','Management','Other']);
            $table->timestamps();
        });

        // User Skills (pivot)
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['Beginner','Intermediate','Expert'])->default('Beginner');
            $table->timestamps();
        });

        // Hackathons
        Schema::create('hackathons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('organizer');
            $table->text('description')->nullable();
            $table->string('category'); // AI, Web, Hardware, etc.
            $table->enum('type', ['Solo','Team','Both'])->default('Team');
            $table->enum('mode', ['Online','Offline','Hybrid'])->default('Online');
            $table->timestamp('deadline')->nullable();
            $table->string('prize')->nullable();
            $table->string('registration_link')->nullable();
            $table->string('banner_emoji')->default('🏆');
            $table->string('banner_color')->default('linear-gradient(135deg,#1e3a5f,#0f2542)');
            $table->boolean('is_intra_university')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('hackathon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->json('required_skills')->nullable();
            $table->text('prerequisites')->nullable();
            $table->integer('team_size')->default(4);
            $table->string('dept_preference')->default('Any');
            $table->date('deadline')->nullable();
            $table->enum('status', ['recruiting','in_progress','completed'])->default('recruiting');
            $table->timestamps();
        });

        // Project Applications
        Schema::create('project_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('pitch');
            $table->enum('status', ['pending','accepted','rejected'])->default('pending');
            $table->timestamps();
        });

        // Teams
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        // Team Members
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('Member');
            $table->timestamps();
        });

        // Endorsements
        Schema::create('endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endorser_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('endorsed_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained()->cascadeOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department','university','year','bio','github','linkedin','availability','avatar']);
        });
        Schema::dropIfExists('endorsements');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('project_applications');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('hackathons');
        Schema::dropIfExists('user_skills');
        Schema::dropIfExists('skills');
    }
};