<?php
// FILE: routes/web.php  (FULL UPDATED VERSION)

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EndorsementController;
use App\Http\Controllers\HackathonController;
use App\Http\Controllers\HallOfFameController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

// ── Public ──
Route::get('/', fn() => view('welcome'))->name('home');

// ── Authenticated ──
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',        [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile',      [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile',     [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/members/{id}',   [ProfileController::class, 'show'])   ->name('profile.show');

    // Projects
    Route::get('/projects',                             [ProjectController::class, 'index'])            ->name('projects.index');
    Route::get('/projects/mine',                        [ProjectController::class, 'myProjects'])       ->name('projects.mine');
    Route::get('/projects/create',                      [ProjectController::class, 'create'])           ->name('projects.create');
    Route::post('/projects',                            [ProjectController::class, 'store'])            ->name('projects.store');
    Route::get('/projects/{project}',                   [ProjectController::class, 'show'])             ->name('projects.show');
    Route::post('/projects/{project}/apply',            [ProjectController::class, 'apply'])            ->name('projects.apply');
    Route::patch('/applications/{application}/respond', [ProjectController::class, 'updateApplication'])->name('applications.respond');

    // Workspace (Kanban + Announcements + Links + Ghost)
    Route::get('/projects/{project}/workspace',                    [WorkspaceController::class, 'show'])              ->name('workspace.show');
    Route::post('/projects/{project}/tasks',                       [WorkspaceController::class, 'storeTask'])         ->name('workspace.task.store');
    Route::patch('/tasks/{task}/status',                           [WorkspaceController::class, 'updateTask'])        ->name('workspace.task.update');
    Route::delete('/tasks/{task}',                                 [WorkspaceController::class, 'deleteTask'])        ->name('workspace.task.delete');
    Route::post('/projects/{project}/announcements',               [WorkspaceController::class, 'storeAnnouncement'])->name('workspace.announcement.store');
    Route::post('/projects/{project}/links',                       [WorkspaceController::class, 'storeLink'])        ->name('workspace.link.store');
    Route::delete('/links/{link}',                                 [WorkspaceController::class, 'deleteLink'])        ->name('workspace.link.delete');
    Route::post('/projects/{project}/ghost-flag',                  [WorkspaceController::class, 'flagGhost'])         ->name('workspace.ghost.flag');

    // Hackathons
    Route::get('/hackathons',             [HackathonController::class, 'index'])->name('hackathons.index');
    Route::get('/hackathons/{hackathon}', [HackathonController::class, 'show']) ->name('hackathons.show');

    // Members
    Route::get('/members', function () {
        $members = \App\Models\User::with('skills')
                                   ->where('id', '!=', auth()->id())
                                   ->paginate(12);
        return view('members.index', compact('members'));
    })->name('members.index');

    // Endorsements
    Route::get('/endorsements',                       [EndorsementController::class, 'index'])       ->name('endorsements.index');
    Route::post('/endorsements',                      [EndorsementController::class, 'store'])       ->name('endorsements.store');
    Route::post('/members/{user}/endorse',            [EndorsementController::class, 'endorseUser'])->name('endorsements.user');

    // Hall of Fame
    Route::get('/hall-of-fame', [HallOfFameController::class, 'index'])->name('halloffame.index');

    // Mentor Connect
    Route::get('/mentors',                                    [MentorController::class, 'index'])         ->name('mentors.index');
    Route::post('/mentors/become',                            [MentorController::class, 'becomeMentor'])  ->name('mentors.become');
    Route::post('/mentors/{mentor}/request',                  [MentorController::class, 'requestMentor']) ->name('mentors.request');
    Route::patch('/mentor-requests/{mentorRequest}/respond',  [MentorController::class, 'respondRequest'])->name('mentors.respond');
});

// ── Admin ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                           [AdminController::class, 'dashboard'])       ->name('dashboard');
    Route::get('/hackathons',                 [AdminController::class, 'hackathons'])      ->name('hackathons');
    Route::post('/hackathons',                [AdminController::class, 'storeHackathon'])  ->name('hackathons.store');
    Route::delete('/hackathons/{hackathon}',  [AdminController::class, 'deleteHackathon'])->name('hackathons.delete');
    Route::get('/achievements',               [AdminController::class, 'achievements'])    ->name('achievements');
    Route::post('/achievements',              [AdminController::class, 'storeAchievement'])->name('achievements.store');
});

require __DIR__.'/auth.php';