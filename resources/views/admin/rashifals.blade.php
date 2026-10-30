@extends('layouts.admin')

@section('title', 'Manage Rashifal | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Daily Rashifal</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Daily Rashifal Management</h1>
        <p>Create, pre-fetch, and publish daily horoscope predictions for all 12 zodiac signs.</p>
    </div>
    <div class="admin-page-actions">
        <form method="POST" action="{{ route('admin.astro.sync') }}" style="display:inline">
            @csrf
            <input type="hidden" name="days" value="7">
            <button type="submit" class="btn-action btn-action-primary" title="Pre-fetch and persist the next 7 days of 12-sign Rashifal into MySQL">
                ⚡ Pre-fetch & Sync Next 7 Days
            </button>
        </form>
    </div>
</div>

<div class="detail-grid">
    <!-- Form -->
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>{{ $editing ? 'Edit Rashifal Record' : 'Add Rashifal Entry' }}</h3>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.rashifals.save') }}">
                    @csrf
                    @if($editing)
                        <input type="hidden" name="id" value="{{ $editing->id }}">
                    @endif

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="sign">Zodiac Sign <span class="required">*</span></label>
                            <select id="sign" name="sign" class="form-control" required>
                                @foreach(['Aries','Taurus','Gemini','Cancer','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces'] as $sign)
                                    <option value="{{ $sign }}" @selected(($editing?->sign ?? '') === $sign)>{{ $sign }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">Date <span class="required">*</span></label>
                            <input type="date" id="date" name="date" class="form-control" value="{{ $editing?->date?->toDateString() ?? now('Asia/Kolkata')->toDateString() }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Title / Headline (Optional)</label>
                        <input type="text" id="title" name="title" class="form-control" maxlength="180" value="{{ $editing?->title }}" placeholder="E.g., Auspicious day for new financial beginnings">
                    </div>

                    <div class="form-group">
                        <label for="content">Prediction Content <span class="required">*</span></label>
                        <textarea id="content" name="content" rows="6" class="form-control" required placeholder="Write detailed planetary effects and guidance for this sign...">{{ $editing?->content }}</textarea>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="status">Publication Status <span class="required">*</span></label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="published" @selected(($editing?->status ?? 'published') === 'published')>Published</option>
                                <option value="draft" @selected(($editing?->status ?? '') === 'draft')>Draft</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="published_at">Publish Schedule (Optional)</label>
                            <input type="datetime-local" id="published_at" name="published_at" class="form-control" value="{{ $editing?->published_at?->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;align-items:center;margin-top:12px">
                        <button type="submit" class="btn-action btn-action-primary" style="padding:10px 20px">
                            {{ $editing ? 'Update Rashifal' : 'Save & Publish' }}
                        </button>
                        @if($editing)
                            <a href="{{ route('admin.rashifals') }}" class="btn-action">Cancel Edit</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Existing List -->
    <div>
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Recent Entries</h3>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sign</th>
                            <th>Status</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->date->format('d M Y') }}</td>
                                <td><strong>{{ $item->sign }}</strong></td>
                                <td>
                                    @if($item->status === 'published')
                                        <span class="badge badge-approved">Published</span>
                                    @else
                                        <span class="badge badge-pending">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group" style="justify-content:flex-end">
                                        <a href="{{ route('admin.rashifals', ['edit' => $item->id]) }}" class="btn-action">Edit</a>
                                        <form method="POST" action="{{ route('admin.rashifals.delete', $item) }}" onsubmit="return confirm('Delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;padding:24px;color:var(--admin-muted)">No entries yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
                <div style="padding:12px 18px;border-top:1px solid var(--admin-border)">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
