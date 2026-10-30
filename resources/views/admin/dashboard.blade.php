@extends('layouts.admin')

@section('title', 'Admin Dashboard | DigitalBhudev')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Dashboard</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Admin Dashboard</h1>
        <p>Real-time overview of the DigitalBhudev network, profile verifications, and platform activity.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.bhudevs.create') }}" class="btn-action btn-action-primary" style="padding:10px 16px;font-size:12px">
            <span>+ Add New Bhudev</span>
        </a>
    </div>
</div>

<!-- KPI Stats Grid -->
<div class="admin-stat-grid">
    <a href="{{ route('admin.bhudevs') }}" class="admin-stat-card stat-total">
        <span class="stat-label">Total Bhudevs</span>
        <span class="stat-value">{{ $counts['total'] }}</span>
        <span class="stat-desc">Registered network profiles</span>
    </a>
    <a href="{{ route('admin.bhudevs', ['status' => 'approved']) }}" class="admin-stat-card stat-approved">
        <span class="stat-label">Approved Profiles</span>
        <span class="stat-value" style="color:var(--admin-green)">{{ $counts['approved'] }}</span>
        <span class="stat-desc">Verified & active profiles</span>
    </a>
    <a href="{{ route('admin.bhudevs', ['status' => 'pending']) }}" class="admin-stat-card stat-pending">
        <span class="stat-label">Pending Verification</span>
        <span class="stat-value" style="color:var(--admin-amber)">{{ $counts['pending'] }}</span>
        <span class="stat-desc">Awaiting admin review</span>
    </a>
    <a href="{{ route('admin.bhudevs', ['status' => 'hidden']) }}" class="admin-stat-card stat-hidden">
        <span class="stat-label">Hidden Profiles</span>
        <span class="stat-value" style="color:#64748b">{{ $counts['hidden'] }}</span>
        <span class="stat-desc">Approved but unlisted</span>
    </a>
    <a href="{{ route('admin.bhudevs', ['status' => 'suspended']) }}" class="admin-stat-card stat-suspended">
        <span class="stat-label">Suspended</span>
        <span class="stat-value" style="color:var(--admin-slate)">{{ $counts['suspended'] }}</span>
        <span class="stat-desc">Temporarily disabled</span>
    </a>
    <a href="{{ route('admin.bhudevs', ['status' => 'rejected']) }}" class="admin-stat-card stat-rejected">
        <span class="stat-label">Rejected</span>
        <span class="stat-value" style="color:var(--admin-red)">{{ $counts['rejected'] }}</span>
        <span class="stat-desc">Submissions declined</span>
    </a>
</div>

<div class="detail-grid">
    <!-- Left Column: Pending Profiles & Revisions -->
    <div>
        <!-- Pending Profiles Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3>Pending Submissions</h3>
                    <p style="font-size:12px;color:var(--admin-muted);margin:3px 0 0">Profiles submitted for approval</p>
                </div>
                @if($pendingProfiles->count())
                    <a href="{{ route('admin.bhudevs', ['status' => 'pending']) }}" class="btn-action">View All ({{ $counts['pending'] }})</a>
                @endif
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Bhudev</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Submitted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingProfiles as $profile)
                            <tr>
                                <td>
                                    <div class="table-user-cell">
                                        @if($profile->photo_path)
                                            <img src="{{ asset('storage/'.$profile->photo_path) }}" alt="{{ $profile->display_name }}" class="table-avatar">
                                        @else
                                            <div class="table-avatar">ॐ</div>
                                        @endif
                                        <div class="table-user-info">
                                            <strong>{{ $profile->display_name }}</strong>
                                            <small>{{ $profile->services->pluck('name')->take(2)->join(', ') ?: 'No pooja listed' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small style="display:block;font-weight:600">{{ $profile->user->email }}</small>
                                    <small style="color:var(--admin-muted)">{{ $profile->user->phone ?: 'No phone' }}</small>
                                </td>
                                <td>{{ $profile->city ?: '—' }}, {{ $profile->state ?: '—' }}</td>
                                <td>{{ $profile->created_at->format('d M, H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.bhudevs.show', $profile) }}" class="btn-action btn-action-primary">Review Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:36px;color:var(--admin-muted)">
                                    <div style="font-size:28px;margin-bottom:8px">✓</div>
                                    <strong>No pending profiles.</strong> All submitted Bhudev accounts have been reviewed.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Revisions Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h3>Profile Revisions Awaiting Review</h3>
                    <p style="font-size:12px;color:var(--admin-muted);margin:3px 0 0">Updates submitted by approved Bhudevs (current public version remains active until approved)</p>
                </div>
            </div>
            <div class="admin-card-body" style="padding:0">
                @forelse($pendingRevisions as $revision)
                    <div style="padding:18px 22px;border-bottom:1px solid var(--admin-border)">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:10px">
                            <div>
                                <strong style="font-size:14px">{{ $revision->profile->display_name }}</strong>
                                <span style="font-size:11px;color:var(--admin-muted);margin-left:8px">Submitted {{ $revision->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <span class="badge badge-pending">Pending Review</span>
                        </div>

                        <!-- Diff Viewer -->
                        <div style="background:#fdfafc;border:1.5px solid var(--admin-border);border-radius:12px;padding:14px;margin-bottom:14px;font-size:12px">
                            <strong style="display:block;margin-bottom:8px;color:var(--admin-primary)">Submitted Modifications:</strong>
                            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:10px">
                                @foreach($revision->payload as $field => $newVal)
                                    @php
                                        $origVal = $revision->profile->$field;
                                        $displayNew = is_array($newVal) ? implode(', ', $newVal) : (is_bool($newVal) ? ($newVal ? 'Yes' : 'No') : (string)$newVal);
                                        $displayOld = is_array($origVal) ? implode(', ', $origVal) : (is_bool($origVal) ? ($origVal ? 'Yes' : 'No') : (string)$origVal);
                                    @endphp
                                    @if($displayNew != $displayOld && $field !== 'service_ids')
                                        <div style="background:#fff;padding:8px 10px;border-radius:8px;border:1px solid var(--admin-border)">
                                            <span style="font-weight:700;color:var(--admin-muted);text-transform:capitalize">{{ str_replace('_', ' ', $field) }}:</span>
                                            <div style="color:var(--admin-primary);font-weight:600;margin-top:2px">{{ Str::limit($displayNew ?: 'None', 80) }}</div>
                                            <div style="color:#8a7f72;font-size:10px;text-decoration:line-through;margin-top:2px">Previous: {{ Str::limit($displayOld ?: 'None', 50) }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Review Action Form -->
                        <form method="POST" action="{{ route('admin.revisions.review', $revision) }}" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                            @csrf
                            <input type="text" name="review_note" placeholder="Admin note (optional)" class="form-control" style="flex:1;min-width:200px;font-size:12px;padding:7px 10px">
                            <button type="submit" name="decision" value="approved" class="btn-action btn-action-success">✓ Approve Changes</button>
                            <button type="submit" name="decision" value="rejected" class="btn-action btn-action-danger">✕ Decline</button>
                        </form>
                    </div>
                @empty
                    <div style="text-align:center;padding:36px;color:var(--admin-muted)">
                        ✓ No profile revisions currently awaiting review.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity & Quick Navigation -->
    <div>
        <!-- Quick Nav Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Quick Management</h3>
            </div>
            <div class="admin-card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('admin.bhudevs.create') }}" class="btn-action btn-action-primary" style="justify-content:center;padding:10px">
                    + Register New Bhudev Profile
                </a>
                <a href="{{ route('admin.rashifals') }}" class="btn-action" style="justify-content:center;padding:10px">
                    ♈ Update Daily Rashifal (12 Signs)
                </a>
                <a href="{{ route('admin.panchang') }}" class="btn-action" style="justify-content:center;padding:10px">
                    🗓️ Manage Nitya Panchang
                </a>
                <a href="{{ route('admin.inquiries') }}" class="btn-action" style="justify-content:center;padding:10px">
                    ✉️ View Contact Inquiries
                </a>
            </div>
        </div>

        <!-- Recent Audit Trail Card -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Recent Admin Actions</h3>
                <a href="{{ route('admin.audit-logs') }}" class="btn-action" style="font-size:11px">Full Log →</a>
            </div>
            <div class="admin-card-body">
                <div class="timeline">
                    @forelse($recentActivity as $act)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-time">{{ $act->created_at->diffForHumans() }}</div>
                                <strong>{{ str_replace(['admin.', 'auth.', '.'], [' ', ' ', ' '], ucwords($act->action, '.')) }}</strong>
                                <div style="color:var(--admin-muted);font-size:11px;margin-top:2px">
                                    by {{ $act->user?->name ?: 'System' }}
                                    @if($act->subject_type)
                                        · {{ class_basename($act->subject_type) }} #{{ $act->subject_id }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p style="color:var(--admin-muted);margin:0;font-size:12px">No recent activity logged.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
