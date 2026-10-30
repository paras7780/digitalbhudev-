@extends('layouts.admin')

@section('title', 'Manage Bhudevs | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Bhudev Directory</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Bhudev Management</h1>
        <p>Review, verify, update, and manage public visibility for all Bhudev profiles.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.bhudevs.create') }}" class="btn-action btn-action-primary" style="padding:10px 16px;font-size:12px">
            <span>+ Add New Bhudev</span>
        </a>
    </div>
</div>

<div class="admin-card">
    <!-- Filter Bar: Status Pills & Search Inputs -->
    <div class="admin-filter-bar">
        <div class="status-pills">
            <a href="{{ route('admin.bhudevs', array_merge(request()->except('status', 'page'))) }}" class="status-pill {{ !request()->filled('status') ? 'active' : '' }}">
                <span>All Profiles</span>
                <span class="pill-count">{{ $tabCounts['all'] }}</span>
            </a>
            <a href="{{ route('admin.bhudevs', array_merge(request()->except('page'), ['status' => 'pending'])) }}" class="status-pill {{ request('status') === 'pending' ? 'active' : '' }}">
                <span>Pending</span>
                <span class="pill-count">{{ $tabCounts['pending'] }}</span>
            </a>
            <a href="{{ route('admin.bhudevs', array_merge(request()->except('page'), ['status' => 'approved'])) }}" class="status-pill {{ request('status') === 'approved' ? 'active' : '' }}">
                <span>Approved</span>
                <span class="pill-count">{{ $tabCounts['approved'] }}</span>
            </a>
            <a href="{{ route('admin.bhudevs', array_merge(request()->except('page'), ['status' => 'hidden'])) }}" class="status-pill {{ request('status') === 'hidden' ? 'active' : '' }}">
                <span>Hidden</span>
                <span class="pill-count">{{ $tabCounts['hidden'] }}</span>
            </a>
            <a href="{{ route('admin.bhudevs', array_merge(request()->except('page'), ['status' => 'suspended'])) }}" class="status-pill {{ request('status') === 'suspended' ? 'active' : '' }}">
                <span>Suspended</span>
                <span class="pill-count">{{ $tabCounts['suspended'] }}</span>
            </a>
            <a href="{{ route('admin.bhudevs', array_merge(request()->except('page'), ['status' => 'rejected'])) }}" class="status-pill {{ request('status') === 'rejected' ? 'active' : '' }}">
                <span>Rejected</span>
                <span class="pill-count">{{ $tabCounts['rejected'] }}</span>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.bhudevs') }}" class="filter-search-wrap">
            @if(request()->filled('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, email, phone..." class="admin-search-input" aria-label="Search Bhudevs">
            <input type="text" name="city" value="{{ request('city') }}" placeholder="Filter city..." class="admin-search-input" style="min-width:130px" aria-label="Filter city">
            <button type="submit" class="btn-action btn-action-primary">Search</button>
            @if(request()->hasAny(['q', 'city', 'status']))
                <a href="{{ route('admin.bhudevs') }}" class="btn-action" title="Clear all filters">Reset</a>
            @endif
        </form>
    </div>

    <!-- Profiles Table -->
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Bhudev Profile</th>
                    <th>Contact Info</th>
                    <th>City & State</th>
                    <th>Services</th>
                    <th>Approval Status</th>
                    <th>Public Access</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profiles as $profile)
                    <tr>
                        <td>
                            <div class="table-user-cell">
                                @if($profile->photo_path)
                                    <img src="{{ asset('storage/'.$profile->photo_path) }}" alt="{{ $profile->display_name }}" class="table-avatar">
                                @else
                                    <div class="table-avatar">ॐ</div>
                                @endif
                                <div class="table-user-info">
                                    <strong>
                                        <a href="{{ route('admin.bhudevs.show', $profile) }}" style="color:var(--admin-ink);text-decoration:none">
                                            {{ $profile->display_name }}
                                        </a>
                                    </strong>
                                    <small style="color:var(--admin-muted)">/{{ $profile->slug }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600;font-size:12px">{{ $profile->user->email }}</div>
                            <small style="color:var(--admin-muted)">{{ $profile->user->phone ?: 'No phone provided' }}</small>
                        </td>
                        <td>
                            <div>{{ $profile->city ?: '—' }}</div>
                            <small style="color:var(--admin-muted)">{{ $profile->state ?: '—' }}</small>
                        </td>
                        <td>
                            @if($profile->services->isNotEmpty())
                                <div style="display:flex;gap:4px;flex-wrap:wrap;max-width:220px">
                                    @foreach($profile->services->take(2) as $s)
                                        <span class="tag" style="margin:0;font-size:9px">{{ $s->name }}</span>
                                    @endforeach
                                    @if($profile->services->count() > 2)
                                        <span style="font-size:10px;color:var(--admin-muted);align-self:center">+{{ $profile->services->count() - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span style="color:var(--admin-muted);font-size:11px">None selected</span>
                            @endif
                        </td>
                        <td>
                            @if($profile->status === 'approved')
                                <span class="badge badge-approved">✓ Approved</span>
                            @elseif($profile->status === 'pending')
                                <span class="badge badge-pending">⏳ Pending Review</span>
                            @elseif($profile->status === 'rejected')
                                <span class="badge badge-rejected" title="{{ $profile->rejection_reason }}">✕ Rejected</span>
                            @elseif($profile->status === 'suspended')
                                <span class="badge badge-suspended">⏸ Suspended</span>
                            @endif
                        </td>
                        <td>
                            @if($profile->status === 'approved')
                                @if($profile->is_visible)
                                    <span class="badge badge-visible">🌐 Publicly Visible</span>
                                @else
                                    <span class="badge badge-hidden">🔒 Hidden</span>
                                @endif
                            @else
                                <span style="font-size:11px;color:var(--admin-muted)">Private</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group" style="justify-content:flex-end">
                                <a href="{{ route('admin.bhudevs.show', $profile) }}" class="btn-action" title="View complete profile details">
                                    View
                                </a>
                                <a href="{{ route('admin.bhudevs.edit', $profile) }}" class="btn-action" title="Edit Bhudev details">
                                    Edit
                                </a>

                                <!-- Approval & Visibility Actions -->
                                @if($profile->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.bhudevs.approve', $profile) }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action btn-action-success" title="Approve profile">
                                            ✓ Approve
                                        </button>
                                    </form>
                                @endif

                                @if($profile->status === 'pending' || $profile->status === 'approved')
                                    <button type="button" class="btn-action btn-action-danger" data-action="reject" data-url="{{ route('admin.bhudevs.reject', $profile) }}" data-name="{{ $profile->display_name }}" title="Reject submission with reason">
                                        Reject
                                    </button>
                                @endif

                                @if($profile->status === 'approved')
                                    <form method="POST" action="{{ route('admin.bhudevs.toggle-visibility', $profile) }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($profile->is_visible)
                                            <button type="submit" class="btn-action btn-action-warning" title="Hide this profile from public searches and direct URLs">
                                                Hide
                                            </button>
                                        @else
                                            <button type="submit" class="btn-action btn-action-success" title="Show this profile in public searches and direct URLs">
                                                Show
                                            </button>
                                        @endif
                                    </form>

                                    <button type="button" class="btn-action" data-action="suspend" data-url="{{ route('admin.bhudevs.suspend', $profile) }}" data-name="{{ $profile->display_name }}" title="Suspend profile temporarily">
                                        Suspend
                                    </button>
                                @elseif($profile->status === 'suspended')
                                    <form method="POST" action="{{ route('admin.bhudevs.reactivate', $profile) }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-action btn-action-success" title="Reactivate suspended profile">
                                            Reactivate
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn-action btn-action-danger" data-action="delete" data-url="{{ route('admin.bhudevs.delete', $profile) }}" data-name="{{ $profile->display_name }}" title="Safely delete and archive this account">
                                    🗑
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:48px 20px;color:var(--admin-muted)">
                            <div style="font-size:32px;margin-bottom:10px">🔍</div>
                            <h3 style="margin:0 0 6px;color:var(--admin-ink)">No Bhudev profiles found</h3>
                            <p style="margin:0 0 16px;font-size:12px">No profiles matched your filter criteria or search query.</p>
                            @if(request()->hasAny(['q', 'city', 'status']))
                                <a href="{{ route('admin.bhudevs') }}" class="btn-action btn-action-primary">Clear Filters</a>
                            @else
                                <a href="{{ route('admin.bhudevs.create') }}" class="btn-action btn-action-primary">+ Add First Bhudev</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($profiles->hasPages())
        <div style="padding:16px 20px;border-top:1px solid var(--admin-border)">
            {{ $profiles->links() }}
        </div>
    @endif
</div>
@endsection
