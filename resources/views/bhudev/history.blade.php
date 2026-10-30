@extends('layouts.bhudev')

@section('title', 'Review & Status History | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('bhudev.dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <strong>Review & Status History</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Review & Status History</h1>
        <p>Complete audit record of your profile verification, state transitions, and revision requests.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('bhudev.dashboard') }}" class="btn btn-light">
            &larr; Back to Dashboard
        </a>
    </div>
</div>

{{-- Status History Timeline Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3>Status Transitions & Decisions</h3>
        <span style="font-size:12px;color:var(--admin-muted)">{{ $profile->statusHistory->count() }} total events</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:170px">Date & Time</th>
                    <th style="width:160px">Transition</th>
                    <th>Reason / Administrative Note</th>
                    <th style="width:150px">Changed By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profile->statusHistory as $hist)
                    <tr>
                        <td style="white-space:nowrap;font-size:12px;color:var(--admin-muted)">
                            {{ $hist->created_at->format('d M Y, h:i A') }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $hist->to_status }}">
                                {{ ucfirst($hist->from_status ?: 'Initial') }} &rarr; {{ ucfirst($hist->to_status) }}
                            </span>
                        </td>
                        <td style="font-size:13px">
                            @if($hist->reason)
                                {{ $hist->reason }}
                            @else
                                <span style="color:var(--admin-muted);font-style:italic">No reason specified</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--admin-muted)">
                            {{ $hist->changedBy?->name ?? 'System' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;color:var(--admin-muted);padding:32px">
                            No status events recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Submitted Revisions Card --}}
@if($profile->revisions->isNotEmpty())
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Profile Revision Requests</h3>
            <span style="font-size:12px;color:var(--admin-muted)">Edits made while your profile was active</span>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:170px">Submitted On</th>
                        <th style="width:130px">Review Status</th>
                        <th>Review Notes</th>
                        <th style="width:150px">Reviewed By</th>
                        <th style="width:150px">Reviewed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profile->revisions as $rev)
                        <tr>
                            <td style="white-space:nowrap;font-size:12px;color:var(--admin-muted)">
                                {{ $rev->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td>
                                @if($rev->review_status === 'approved')
                                    <span class="badge badge-approved">Approved</span>
                                @elseif($rev->review_status === 'rejected')
                                    <span class="badge badge-rejected">Rejected</span>
                                @else
                                    <span class="badge badge-pending">Pending Review</span>
                                @endif
                            </td>
                            <td style="font-size:13px">
                                {{ $rev->review_notes ?: '—' }}
                            </td>
                            <td style="font-size:12px;color:var(--admin-muted)">
                                {{ $rev->reviewer?->name ?? '—' }}
                            </td>
                            <td style="font-size:12px;color:var(--admin-muted)">
                                {{ $rev->reviewed_at ? $rev->reviewed_at->format('d M Y, h:i A') : 'Pending' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
