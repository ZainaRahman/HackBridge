@extends('layouts.app')
@section('title','Team Workspace')
@section('page-title','Team Workspace')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <h2 style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--white);">{{ $project->title }}</h2>
        <p style="font-size:13px;color:var(--muted);">Team Workspace · {{ $members->count() }} members</p>
    </div>
    <a href="{{ route('projects.show', $project->id) }}" class="btn btn-ghost btn-sm">← Back to Project</a>
</div>

{{-- ── KANBAN BOARD ── --}}
<div class="card" style="margin-bottom:20px;">
    <div class="sec-header">
        <div class="sec-title">📋 Kanban Board</div>
        <button class="btn btn-primary btn-sm" onclick="toggleModal('addTaskModal')">+ Add Task</button>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">

        {{-- TO DO --}}
        <div style="background:var(--bg2);border-radius:10px;padding:14px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:12px;">📌 To Do ({{ $todo->count() }})</div>
            @forelse($todo as $task)
            <div style="background:var(--surface);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:8px;">
                <div style="font-size:13px;font-weight:600;color:var(--white);margin-bottom:4px;">{{ $task->title }}</div>
                @if($task->description)<div style="font-size:11px;color:var(--muted);margin-bottom:8px;">{{ Str::limit($task->description,60) }}</div>@endif
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span class="chip chip-{{ $task->priority === 'high' ? 'red' : ($task->priority === 'medium' ? 'orange' : 'green') }}">{{ $task->priority }}</span>
                    <div style="display:flex;gap:6px;">
                        <form method="POST" action="{{ route('workspace.task.update', $task->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-ghost btn-sm" style="font-size:10px;padding:3px 8px;">→ Start</button>
                        </form>
                        <form method="POST" action="{{ route('workspace.task.delete', $task->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="font-size:10px;padding:3px 8px;">✕</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px;">No tasks yet</div>
            @endforelse
        </div>

        {{-- IN PROGRESS --}}
        <div style="background:var(--bg2);border-radius:10px;padding:14px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--orange);margin-bottom:12px;">⚡ In Progress ({{ $in_progress->count() }})</div>
            @forelse($in_progress as $task)
            <div style="background:var(--surface);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:12px;margin-bottom:8px;">
                <div style="font-size:13px;font-weight:600;color:var(--white);margin-bottom:4px;">{{ $task->title }}</div>
                @if($task->description)<div style="font-size:11px;color:var(--muted);margin-bottom:8px;">{{ Str::limit($task->description,60) }}</div>@endif
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span class="chip chip-{{ $task->priority === 'high' ? 'red' : ($task->priority === 'medium' ? 'orange' : 'green') }}">{{ $task->priority }}</span>
                    <div style="display:flex;gap:6px;">
                        <form method="POST" action="{{ route('workspace.task.update', $task->id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="done">
                            <button type="submit" class="btn btn-green btn-sm" style="font-size:10px;padding:3px 8px;">✓ Done</button>
                        </form>
                        <form method="POST" action="{{ route('workspace.task.delete', $task->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="font-size:10px;padding:3px 8px;">✕</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px;">Nothing in progress</div>
            @endforelse
        </div>

        {{-- DONE --}}
        <div style="background:var(--bg2);border-radius:10px;padding:14px;">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--green);margin-bottom:12px;">✅ Done ({{ $done->count() }})</div>
            @forelse($done as $task)
            <div style="background:var(--surface);border:1px solid rgba(34,197,94,0.15);border-radius:8px;padding:12px;margin-bottom:8px;opacity:.75;">
                <div style="font-size:13px;font-weight:600;color:var(--white);text-decoration:line-through;margin-bottom:4px;">{{ $task->title }}</div>
                <div style="display:flex;justify-content:flex-end;">
                    <form method="POST" action="{{ route('workspace.task.delete', $task->id) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="font-size:10px;padding:3px 8px;">✕</button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:var(--muted);font-size:12px;">Nothing done yet</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── BOTTOM GRID ── --}}
<div class="grid-2" style="gap:16px;margin-bottom:20px;">

    {{-- Announcements --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">📢 Announcements</div>
            @if($isOwner)
            <button class="btn btn-primary btn-sm" onclick="toggleModal('announcementModal')">+ Post</button>
            @endif
        </div>
        @forelse($announcements as $ann)
        <div style="padding:12px 0;border-bottom:1px solid var(--border);">
            <div style="font-size:13.5px;font-weight:700;color:var(--white);margin-bottom:4px;">{{ $ann->title }}</div>
            <div style="font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:6px;">{{ $ann->body }}</div>
            <div style="font-size:11px;color:var(--muted);">{{ $ann->author->name }} · {{ $ann->created_at->diffForHumans() }}</div>
        </div>
        @empty
        <div class="empty-state" style="padding:20px;"><span class="es-icon">📢</span><p>No announcements yet</p></div>
        @endforelse
    </div>

    {{-- Shared Links --}}
    <div class="card">
        <div class="sec-header">
            <div class="sec-title">🔗 Shared Links</div>
            <button class="btn btn-primary btn-sm" onclick="toggleModal('linkModal')">+ Add Link</button>
        </div>
        @forelse($links as $link)
        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border);">
            <div style="width:32px;height:32px;border-radius:8px;background:var(--bg2);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;">
                {{ $link->type === 'GitHub' ? '🐙' : ($link->type === 'Drive' ? '📁' : ($link->type === 'Figma' ? '🎨' : '🔗')) }}
            </div>
            <div style="flex:1;">
                <a href="{{ $link->url }}" target="_blank" style="font-size:13px;font-weight:600;color:var(--blue);text-decoration:none;">{{ $link->title }}</a>
                <div style="font-size:11px;color:var(--muted);">{{ $link->type }} · {{ $link->user->name }}</div>
            </div>
            <form method="POST" action="{{ route('workspace.link.delete', $link->id) }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="padding:3px 8px;font-size:10px;">✕</button>
            </form>
        </div>
        @empty
        <div class="empty-state" style="padding:20px;"><span class="es-icon">🔗</span><p>No links added yet</p></div>
        @endforelse
    </div>

</div>

{{-- ── RESOURCE HUB ── --}}
<div class="card" style="margin-bottom:20px;">
    <div class="sec-header">
        <div class="sec-title">📁 Resource Hub</div>
        <button class="btn btn-primary btn-sm" onclick="toggleModal('uploadModal')">+ Upload File</button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;">
        @forelse($files as $file)
        <div style="background:var(--bg2);border-radius:10px;padding:14px;">
            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:8px;">
                <div style="font-size:20px;flex-shrink:0;">{{ $file->icon() }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--white);word-break:break-word;">{{ $file->title }}</div>
                    <div style="font-size:11px;color:var(--muted);word-break:break-word;">{{ $file->original_name }}</div>
                </div>
            </div>
            <div style="font-size:11px;color:var(--muted);margin-bottom:10px;">
                {{ $file->humanSize() }} · {{ $file->user->name }} · {{ $file->created_at->diffForHumans() }}
            </div>
            <div style="display:flex;gap:6px;">
                <a href="{{ route('workspace.resource.download', $file->id) }}" class="btn btn-outline btn-sm" style="flex:1;text-align:center;justify-content:center;font-size:11px;">⬇ Download</a>
                @if($file->user_id === auth()->id() || $isOwner)
                <form method="POST" action="{{ route('workspace.resource.delete', $file->id) }}" onsubmit="return confirm('Delete this file?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="font-size:10px;padding:6px 8px;">✕</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;">
            <div class="empty-state" style="padding:20px;"><span class="es-icon">📁</span><p>No files uploaded yet. Share slides, docs, or datasets here.</p></div>
        </div>
        @endforelse
    </div>
</div>

{{-- ── TEAM MEMBERS + GHOST FLAG ── --}}
<div class="card">
    <div class="sec-header">
        <div class="sec-title">👥 Team Members</div>
        <span style="font-size:12px;color:var(--muted);">Flag inactive members below</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
        @foreach($members as $member)
        <div style="background:var(--bg2);border-radius:10px;padding:14px;display:flex;align-items:center;gap:10px;">
            <div class="sb-avatar" style="flex-shrink:0;">{{ $member->initials() }}</div>
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:600;color:var(--white);">{{ $member->name }}</div>
                <div style="font-size:11px;color:var(--muted);">{{ $member->department }}</div>
            </div>
            @if($member->id !== auth()->id() && $member->id !== $project->owner_id)
            <form method="POST" action="{{ route('workspace.ghost.flag', $project->id) }}">
                @csrf
                <input type="hidden" name="flagged_user_id" value="{{ $member->id }}">
                <input type="hidden" name="reason" value="Inactive member">
                <button type="submit" class="btn btn-danger btn-sm" style="font-size:10px;padding:3px 8px;" title="Flag as inactive">👻</button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
    @if($ghostFlags->count() > 0 && $isOwner)
    <div style="margin-top:16px;padding:12px;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:8px;">
        <div style="font-size:12px;font-weight:700;color:var(--red);margin-bottom:8px;">⚠️ Ghost Member Alerts</div>
        @foreach($ghostFlags as $flag)
        <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">
            {{ $flag->flaggedUser->name }} was flagged as inactive · {{ $flag->created_at->diffForHumans() }}
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── MODALS ── --}}

{{-- Add Task --}}
<div class="modal-backdrop" id="addTaskModal" onclick="backdropClose(event,'addTaskModal')">
    <div class="modal-box">
        <div class="modal-header-row">
            <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--white);">Add Task</h3>
            <button class="modal-x" onclick="toggleModal('addTaskModal')">✕</button>
        </div>
        <form method="POST" action="{{ route('workspace.task.store', $project->id) }}" style="margin-top:16px;">
            @csrf
            <div class="form-group">
                <label class="form-label">Task Title *</label>
                <input name="title" class="form-control" placeholder="Implement login API" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Optional details..."></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Assign To</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">Unassigned</option>
                        @foreach($members as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Add Task</button>
        </form>
    </div>
</div>

{{-- Announcement --}}
<div class="modal-backdrop" id="announcementModal" onclick="backdropClose(event,'announcementModal')">
    <div class="modal-box">
        <div class="modal-header-row">
            <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--white);">Post Announcement</h3>
            <button class="modal-x" onclick="toggleModal('announcementModal')">✕</button>
        </div>
        <form method="POST" action="{{ route('workspace.announcement.store', $project->id) }}" style="margin-top:16px;">
            @csrf
            <div class="form-group">
                <label class="form-label">Title *</label>
                <input name="title" class="form-control" placeholder="Meeting tomorrow at 9pm" required>
            </div>
            <div class="form-group">
                <label class="form-label">Message *</label>
                <textarea name="body" class="form-control" rows="3" required placeholder="Details..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Post Announcement</button>
        </form>
    </div>
</div>

{{-- Shared Link --}}
<div class="modal-backdrop" id="linkModal" onclick="backdropClose(event,'linkModal')">
    <div class="modal-box">
        <div class="modal-header-row">
            <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--white);">Add Shared Link</h3>
            <button class="modal-x" onclick="toggleModal('linkModal')">✕</button>
        </div>
        <form method="POST" action="{{ route('workspace.link.store', $project->id) }}" style="margin-top:16px;">
            @csrf
            <div class="form-group">
                <label class="form-label">Label *</label>
                <input name="title" class="form-control" placeholder="Main Repo" required>
            </div>
            <div class="form-group">
                <label class="form-label">URL *</label>
                <input name="url" type="url" class="form-control" placeholder="https://github.com/..." required>
            </div>
            <div class="form-group">
                <label class="form-label">Type</label>
                <select name="type" class="form-control">
                    <option>GitHub</option><option>Drive</option><option>Figma</option><option>Notion</option><option>Other</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Add Link</button>
        </form>
    </div>
</div>

{{-- Upload File --}}
<div class="modal-backdrop" id="uploadModal" onclick="backdropClose(event,'uploadModal')">
    <div class="modal-box">
        <div class="modal-header-row">
            <h3 style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--white);">Upload File</h3>
            <button class="modal-x" onclick="toggleModal('uploadModal')">✕</button>
        </div>
        <form method="POST" action="{{ route('workspace.resource.store', $project->id) }}" enctype="multipart/form-data" style="margin-top:16px;">
            @csrf
            <div class="form-group">
                <label class="form-label">Label *</label>
                <input name="title" class="form-control" placeholder="Final Presentation Slides" required>
            </div>
            <div class="form-group">
                <label class="form-label">File * <span style="color:var(--muted);font-weight:400;">(max 20MB — docs, sheets, slides, images, zip)</span></label>
                <input type="file" name="file" class="form-control" required
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.csv,.zip,.rar,.png,.jpg,.jpeg,.gif,.txt,.md">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Upload</button>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
.modal-backdrop { position:fixed;inset:0;z-index:500;background:rgba(5,8,15,0.7);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .3s; }
.modal-backdrop.open { opacity:1;pointer-events:all; }
.modal-box { width:90%;max-width:460px;background:#0D1220;border:1px solid rgba(255,255,255,0.12);border-radius:18px;padding:28px;transform:translateY(16px);transition:transform .3s,opacity .3s;opacity:0; }
.modal-backdrop.open .modal-box { transform:translateY(0);opacity:1; }
.modal-header-row { display:flex;align-items:center;justify-content:space-between; }
.modal-x { background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:var(--muted);width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:13px; }
</style>
@endpush

@push('scripts')
<script>
function toggleModal(id) {
    document.getElementById(id).classList.toggle('open');
    document.body.style.overflow = document.getElementById(id).classList.contains('open') ? 'hidden' : '';
}
function backdropClose(e, id) {
    if (e.target.id === id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow = ''; }
}
</script>
@endpush