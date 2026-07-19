@extends('layouts.app')
@section('title', 'Suggested Teammates — ' . $project->title)
@section('page-title', 'Suggested Teammates')

@section('content')
<div style="max-width:780px;">

    <a href="{{ route('projects.show', $project->id) }}" style="font-size:13px;color:var(--blue);text-decoration:none;display:inline-block;margin-bottom:14px;">
        &larr; Back to project
    </a>

    <div class="card" style="margin-bottom:16px;">
        <div class="card-title">Suggested Teammates for "{{ $project->title }}"</div>
        <p style="font-size:13px;color:var(--muted);margin-bottom:16px;">
            Ranked by skill overlap, department fit, and availability.
        </p>

        @forelse ($candidates as $c)
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 0;border-bottom:1px solid var(--border);">
            <div style="display:flex;align-items:center;gap:10px;flex:1;">
                <div class="sb-avatar" style="width:32px;height:32px;font-size:11px;">
                    {{ collect(explode(' ', $c['name']))->map(fn($p) => strtoupper(substr($p,0,1)))->implode('') }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--white);">
                        <a href="{{ route('profile.show', $c['user_id']) }}" style="color:inherit;text-decoration:none;">
                            {{ $c['name'] }}
                        </a>
                    </div>
                    <div style="font-size:11px;color:var(--muted);">
                        {{ $c['department'] }} · {{ ucfirst(str_replace('_',' ', $c['availability'])) }}
                    </div>
                </div>
            </div>

            <div style="text-align:right;min-width:140px;">
                <div style="font-size:11px;color:var(--muted);margin-bottom:2px;">Skill match: {{ $c['skill_overlap'] }}%</div>
                <div style="display:flex;align-items:center;gap:8px;justify-content:flex-end;">
                    <div style="width:80px;height:6px;background:rgba(255,255,255,0.08);border-radius:4px;overflow:hidden;">
                        <div style="width:{{ $c['match_score'] }}%;height:100%;background:var(--blue);"></div>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:var(--white);">{{ $c['match_score'] }}%</span>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:var(--muted);font-size:13px;">
            No candidates found.
        </div>
        @endforelse
    </div>
</div>
@endsection