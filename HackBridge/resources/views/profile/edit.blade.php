@extends('layouts.app')
@section('title','Edit Profile')
@section('page-title','My Profile')

@section('content')
<div style="max-width:720px;">

<form method="POST" action="{{ route('profile.update') }}">
@csrf @method('PATCH')

{{-- Profile Header --}}
<div class="card" style="margin-bottom:16px;">
    <div class="profile-header">
        <div class="profile-avatar-lg">{{ $user->initials() }}</div>
        <div class="profile-meta">
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-dept">{{ $user->department }} · {{ $user->university }} · Year {{ $user->year }}</div>
            <span style="font-size:11px;font-weight:700;padding:4px 12px;border-radius:20px;background:{{ $user->availabilityColor() }}20;color:{{ $user->availabilityColor() }};">
                ● {{ $user->availabilityLabel() }}
            </span>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Department</label>
            <select name="department" class="form-control">
                @foreach(['CSE','EEE','ME','CE','ECE','Other'] as $d)
                <option value="{{ $d }}" {{ $user->department === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Year</label>
            <select name="year" class="form-control">
                @foreach([1=>'1st Year',2=>'2nd Year',3=>'3rd Year',4=>'4th Year',5=>'Masters'] as $val => $label)
                <option value="{{ $val }}" {{ $user->year == $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">University</label>
            <input name="university" class="form-control" value="{{ old('university', $user->university ?? 'KUET') }}">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Bio</label>
        <textarea name="bio" class="form-control" rows="3" placeholder="Tell teams about yourself...">{{ old('bio', $user->bio) }}</textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label class="form-label">GitHub URL</label>
            <input name="github" type="url" class="form-control" placeholder="https://github.com/username" value="{{ old('github', $user->github) }}">
        </div>
        <div class="form-group">
            <label class="form-label">LinkedIn URL</label>
            <input name="linkedin" type="url" class="form-control" placeholder="https://linkedin.com/in/username" value="{{ old('linkedin', $user->linkedin) }}">
        </div>
    </div>
</div>

{{-- Availability --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-title">Availability Status</div>
    <div class="avail-options">
        @foreach(['open'=>['Open to Team Up','#22C55E'],'looking'=>['Looking for 1 More','#F59E0B'],'busy'=>['Busy This Semester','#EF4444'],'in_team'=>['In a Team','#8B5CF6']] as $val => [$label, $color])
        <label>
            <input type="radio" name="availability" value="{{ $val }}" {{ $user->availability === $val ? 'checked' : '' }} style="display:none;" onchange="updateAvail(this)">
            <div class="avail-option {{ $user->availability === $val ? 'selected' : '' }}" onclick="this.previousElementSibling.click();" style="{{ $user->availability === $val ? 'border-color:'.$color.';color:'.$color.';background:'.$color.'15;' : '' }}">
                <div style="font-size:14px;margin-bottom:2px;">● {{ $label }}</div>
            </div>
        </label>
        @endforeach
    </div>
</div>

{{-- Skills --}}
<div class="card" style="margin-bottom:16px;">
    <div class="card-title">My Skills</div>
    <p style="font-size:12px;color:var(--muted);margin-bottom:16px;">Select skills and set your proficiency level</p>

    @foreach($skills->groupBy('category') as $category => $catSkills)
    <div style="margin-bottom:20px;">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:10px;">{{ $category }}</div>
        <div class="skill-grid">
            @foreach($catSkills as $skill)
            @php $userSkill = $user->skills->where('id', $skill->id)->first(); @endphp
            <div class="skill-item {{ $userSkill ? 'selected-skill' : '' }}" style="{{ $userSkill ? 'border-color:rgba(59,130,246,0.4);background:rgba(59,130,246,0.06);' : '' }}">
                <div style="display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" id="skill_{{ $skill->id }}"
                        {{ $userSkill ? 'checked' : '' }}
                        onchange="toggleSkill(this, {{ $skill->id }})"
                        style="accent-color:var(--blue);">
                    <label for="skill_{{ $skill->id }}" style="font-size:12px;color:var(--white);font-weight:500;cursor:pointer;">{{ $skill->name }}</label>
                </div>
                <select name="skills[{{ $skill->id }}]" id="level_{{ $skill->id }}"
                    class="skill-level"
                    style="display:{{ $userSkill ? 'block' : 'none' }};background:rgba(59,130,246,0.15);color:#93C5FD;border:none;outline:none;font-size:10px;border-radius:10px;padding:2px 6px;cursor:pointer;">
                    @foreach(['Beginner','Intermediate','Expert'] as $level)
                    <option value="{{ $level }}" {{ $userSkill && $userSkill->pivot->level === $level ? 'selected' : '' }}>{{ $level }}</option>
                    @endforeach
                </select>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<div style="display:flex;gap:10px;justify-content:flex-end;">
    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Profile →</button>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
function toggleSkill(checkbox, skillId) {
    const select = document.getElementById('level_' + skillId);
    select.style.display = checkbox.checked ? 'block' : 'none';
    if (!checkbox.checked) select.name = '';
    else select.name = 'skills[' + skillId + ']';
}

function updateAvail(radio) {
    document.querySelectorAll('.avail-option').forEach(el => {
        el.classList.remove('selected');
        el.style.borderColor = '';
        el.style.color = '';
        el.style.background = '';
    });
    const option = radio.nextElementSibling;
    option.classList.add('selected');
    option.style.borderColor = 'var(--blue)';
    option.style.color = 'var(--blue)';
    option.style.background = 'rgba(59,130,246,0.1)';
}
</script>
@endpush