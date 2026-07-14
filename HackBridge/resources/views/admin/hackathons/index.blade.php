@extends('layouts.app')
@section('title','Manage Hackathons')
@section('page-title','Manage Hackathons')

@section('content')
<div class="sec-header" style="margin-bottom:20px;">
    <h2 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--white);">Hackathon Listings</h2>
    <button class="btn btn-primary" onclick="toggleModal('addHackModal')">+ Add Hackathon</button>
</div>

<div class="card" style="padding:0;overflow:hidden;margin-bottom:20px;">
<table class="table">
    <thead><tr><th>Title</th><th>Organizer</th><th>Type</th><th>Deadline</th><th>Scope</th><th>Action</th></tr></thead>
    <tbody>
    @forelse($hackathons as $h)
    <tr>
        <td style="font-weight:600;color:var(--white);">{{ $h->banner_emoji }} {{ $h->title }}</td>
        <td style="color:var(--muted);">{{ $h->organizer }}</td>
        <td><span class="chip chip-blue">{{ $h->mode }}</span></td>
        <td style="color:var(--orange);font-size:12px;">{{ \Carbon\Carbon::parse($h->deadline)->format('M d, Y') }}</td>
        <td><span class="chip chip-{{ $h->is_intra_university ? 'violet' : 'green' }}">{{ $h->is_intra_university ? 'Intra-KUET' : 'National/Intl' }}</span></td>
        <td>
            <form method="POST" action="{{ route('admin.hackathons.delete', $h->id) }}" onsubmit="return confirm('Delete this hackathon?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:24px;">No hackathons yet</td></tr>
    @endforelse
    </tbody>
</table>
</div>

{{-- Add Hackathon Modal --}}
<div class="modal-backdrop" id="addHackModal" onclick="backdropClose(event,'addHackModal')">
    <div class="modal-box" style="max-width:560px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--white);">Add Hackathon</h3>
            <button class="modal-x" onclick="toggleModal('addHackModal')">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.hackathons.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group"><label class="form-label">Title *</label><input name="title" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Organizer *</label><input name="organizer" class="form-control" required></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <input name="category" class="form-control" placeholder="AI, Web, Hardware..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">Prize</label>
                    <input name="prize" class="form-control" placeholder="৳50,000">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control"><option>Team</option><option>Solo</option><option>Both</option></select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mode</label>
                    <select name="mode" class="form-control"><option>Online</option><option>Offline</option><option>Hybrid</option></select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Deadline *</label><input name="deadline" type="datetime-local" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Banner Emoji</label><input name="banner_emoji" class="form-control" value="🏆" maxlength="4"></div>
            </div>
            <div class="form-group"><label class="form-label">Registration Link</label><input name="registration_link" type="url" class="form-control"></div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
            <div style="display:flex;gap:16px;margin-bottom:16px;">
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);cursor:pointer;">
                    <input type="checkbox" name="is_intra_university" value="1" style="accent-color:var(--blue);"> Intra-University Only
                </label>
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);cursor:pointer;">
                    <input type="checkbox" name="is_featured" value="1" style="accent-color:var(--blue);"> Featured
                </label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Add Hackathon</button>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
.modal-backdrop { position:fixed;inset:0;z-index:500;background:rgba(5,8,15,0.7);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s; }
.modal-backdrop.open { opacity:1;pointer-events:all; }
.modal-box { width:90%;max-width:460px;background:#0D1220;border:1px solid rgba(255,255,255,0.12);border-radius:18px;padding:28px;transform:translateY(16px);transition:transform .3s,opacity .3s;opacity:0;max-height:90vh;overflow-y:auto; }
.modal-backdrop.open .modal-box { transform:translateY(0);opacity:1; }
.modal-x { background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:var(--muted);width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:13px; }
</style>
@endpush

@push('scripts')
<script>
function toggleModal(id) { document.getElementById(id).classList.toggle('open'); document.body.style.overflow = document.getElementById(id).classList.contains('open') ? 'hidden' : ''; }
function backdropClose(e, id) { if (e.target.id === id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; } }
</script>
@endpush