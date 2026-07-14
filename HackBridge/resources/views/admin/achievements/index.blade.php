@extends('layouts.app')
@section('title','Manage Hall of Fame')
@section('page-title','Manage Hall of Fame')

@section('content')

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="grid-2" style="gap:20px;align-items:start;">

    {{-- Add Achievement Form --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">➕ Add Achievement</div>
        </div>
        <form method="POST" action="{{ route('admin.achievements.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input name="title" class="form-control" placeholder="Champions at FictiPay Datathon" required>
                @error('title')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Hackathon Name *</label>
                <input name="hackathon_name" class="form-control" placeholder="bKash NSUCEC Datathon" required>
                @error('hackathon_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="What the team built and achieved..."></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Result *</label>
                    <select name="result" class="form-control" required>
                        <option value="Champion">Champion</option>
                        <option value="Runner-Up">Runner-Up</option>
                        <option value="Top-3">Top-3</option>
                        <option value="Finalist">Finalist</option>
                        <option value="Participant">Participant</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Achieved On *</label>
                    <input type="date" name="achieved_at" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Demo Link</label>
                <input type="url" name="demo_link" class="form-control" placeholder="https://...">
            </div>
            <div class="form-group">
                <label class="form-label">GitHub Link</label>
                <input type="url" name="github_link" class="form-control" placeholder="https://github.com/...">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="is_featured" value="1" id="is_featured" style="width:auto;">
                <label for="is_featured" style="margin:0;font-size:13px;color:var(--muted);">Feature this on the Hall of Fame page</label>
            </div>

            <div class="form-group">
                <label class="form-label">Team Members</label>
                <input type="text" id="memberSearch" class="form-control" placeholder="🔍 Search users to add..." style="margin-bottom:8px;">
                <div style="max-height:260px;overflow-y:auto;border:1px solid var(--border);border-radius:8px;padding:8px;">
                    @foreach($users as $u)
                    <div class="member-row" data-name="{{ strtolower($u->name) }}" style="border-bottom:1px solid var(--border);padding:8px 4px;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                            <input type="checkbox" name="members[{{ $u->id }}][include]" value="1"
                                   onchange="document.getElementById('member-details-{{ $u->id }}').style.display = this.checked ? 'flex' : 'none';"
                                   style="width:auto;">
                            <span style="font-size:13px;font-weight:600;color:var(--white);">{{ $u->name }}</span>
                        </div>
                        <div id="member-details-{{ $u->id }}" style="display:none;gap:8px;padding-left:22px;">
                            <input type="text" name="members[{{ $u->id }}][role]" class="form-control" placeholder="Role (e.g. Team Lead)" style="font-size:12px;">
                            <input type="text" name="members[{{ $u->id }}][contribution]" class="form-control" placeholder="Contribution" style="font-size:12px;">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px;">Add to Hall of Fame</button>
        </form>
    </div>

    {{-- Existing Achievements --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">🌟 Existing Achievements</div>
        </div>
        @forelse($achievements as $a)
        <div style="padding:14px 0;border-bottom:1px solid var(--border);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                <div style="font-size:14px;font-weight:700;color:var(--white);">{{ $a->title }}</div>
                <span class="chip {{ $a->result === 'Champion' ? 'chip-orange' : ($a->result === 'Runner-Up' ? 'chip-blue' : 'chip-violet') }}">
                    {{ $a->result }}
                </span>
            </div>
            <div style="font-size:12px;color:var(--muted);margin-bottom:6px;">
                {{ $a->hackathon_name }} · {{ \Carbon\Carbon::parse($a->achieved_at)->format('M Y') }}
                @if($a->is_featured) · ⭐ Featured @endif
            </div>
            @if($a->description)
            <div style="font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:8px;">{{ Str::limit($a->description, 140) }}</div>
            @endif
            @if($a->members->isNotEmpty())
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                @foreach($a->members as $m)
                <span class="chip chip-blue" style="font-size:10px;">{{ $m->user->name }} · {{ $m->role }}</span>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="empty-state" style="padding:20px;"><span class="es-icon">🌟</span><p>No achievements added yet</p></div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById('memberSearch').addEventListener('input', function () {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.member-row').forEach(row => {
        row.style.display = row.dataset.name.includes(term) ? 'block' : 'none';
    });
});
</script>
@endpush