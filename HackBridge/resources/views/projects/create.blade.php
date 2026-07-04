{{-- ============================================================ --}}
{{-- FILE: resources/views/projects/create.blade.php            --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title','Post a Project')
@section('page-title','Post a Project')

@section('content')
<div style="max-width:680px;">
<div class="page-header">
    <h2>Post a New Project</h2>
    <p>Describe your idea and the skills you need — teammates will apply to join</p>
</div>

<div class="card">
<form method="POST" action="{{ route('projects.store') }}">
@csrf

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Project Title *</label>
        <input name="title" class="form-control" placeholder="Smart Irrigation System" required value="{{ old('title') }}">
    </div>
    <div class="form-group">
        <label class="form-label">Category *</label>
        <select name="category" class="form-control" required>
            <option value="">Select category</option>
            @foreach(['AI','Web','Mobile','Hardware','Research','Design','Other'] as $cat)
            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Project Description *</label>
    <textarea name="description" class="form-control" rows="4" placeholder="Describe your project idea, goals, and what you hope to build..." required>{{ old('description') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Team Size Needed</label>
        <select name="team_size" class="form-control">
            @foreach(range(2,8) as $n)
            <option value="{{ $n }}" {{ old('team_size') == $n ? 'selected' : '' }}>{{ $n }} members</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Department Preference</label>
        <select name="dept_preference" class="form-control">
            <option value="Any">Any Department</option>
            @foreach(['CSE','EEE','ME','CE','ECE'] as $d)
            <option value="{{ $d }}" {{ old('dept_preference') === $d ? 'selected' : '' }}>{{ $d }} Only</option>
            @endforeach
            <option value="Mixed">Mixed Preferred</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Required Skills <span style="color:var(--muted);font-weight:400;">(comma separated)</span></label>
    <input name="required_skills" class="form-control" placeholder="Python, Machine Learning, React, Arduino" value="{{ old('required_skills') }}">
    <div class="form-hint">These appear as tags on your project card</div>
</div>

<div class="form-group">
    <label class="form-label">Prerequisite Knowledge for Applicants</label>
    <textarea name="prerequisites" class="form-control" rows="2" placeholder="Must know: Git, Basic DSA, C++. Familiarity with Arduino is a plus.">{{ old('prerequisites') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Target Hackathon <span style="color:var(--muted);font-weight:400;">(optional)</span></label>
        <select name="hackathon_id" class="form-control">
            <option value="">Not targeting a hackathon</option>
            @foreach($hackathons as $h)
            <option value="{{ $h->id }}" {{ old('hackathon_id') == $h->id ? 'selected' : '' }}>{{ $h->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Team Formation Deadline</label>
        <input name="deadline" type="date" class="form-control" value="{{ old('deadline') }}">
    </div>
</div>

<div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
    <a href="{{ route('projects.index') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">Post Project →</button>
</div>

</form>
</div>
</div>
@endsection