@extends('layouts.admin')

@section('title', 'Manage Panchang | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Nitya Panchang</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Nitya Panchang Management</h1>
        <p>Record, pre-fetch, and publish date-specific astrological elements: Tithi, Vaar, Nakshatra, Yoga, Karana, Sunrise, Sunset, Moonrise, and Moonset.</p>
    </div>
    <div class="admin-page-actions">
        <form method="POST" action="{{ route('admin.astro.sync') }}" style="display:inline">
            @csrf
            <input type="hidden" name="days" value="7">
            <button type="submit" class="btn-action btn-action-primary" title="Pre-fetch and persist the next 7 days into MySQL so the site is never empty">
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
                <h3>{{ $editing ? 'Edit Panchang Record' : 'Add Panchang Entry' }}</h3>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.panchang.save') }}">
                    @csrf
                    @if($editing)
                        <input type="hidden" name="id" value="{{ $editing->id }}">
                    @endif

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="date">Date <span class="required">*</span></label>
                            <input type="date" id="date" name="date" class="form-control" value="{{ $editing?->date?->toDateString() ?? now('Asia/Kolkata')->toDateString() }}" required>
                        </div>

                        <div class="form-group">
                            <label for="location">Location (Blank = General)</label>
                            <input type="text" id="location" name="location" class="form-control" value="{{ $editing?->location }}" placeholder="E.g., Ahmedabad, Gujarat" maxlength="160">
                        </div>

                        <div class="form-group">
                            <label for="timezone">Timezone <span class="required">*</span></label>
                            <input type="text" id="timezone" name="timezone" class="form-control" value="{{ $editing?->timezone ?? 'Asia/Kolkata' }}" required maxlength="80">
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="vaar">Vaar (Day)</label>
                            <input type="text" id="vaar" name="vaar" class="form-control" value="{{ $editing?->vaar }}" placeholder="Somvaar">
                        </div>

                        <div class="form-group">
                            <label for="tithi">Tithi</label>
                            <input type="text" id="tithi" name="tithi" class="form-control" value="{{ $editing?->tithi }}" placeholder="Shukla Paksha Ekadashi">
                        </div>

                        <div class="form-group">
                            <label for="nakshatra">Nakshatra</label>
                            <input type="text" id="nakshatra" name="nakshatra" class="form-control" value="{{ $editing?->nakshatra }}" placeholder="Rohini">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="yoga">Yoga</label>
                            <input type="text" id="yoga" name="yoga" class="form-control" value="{{ $editing?->yoga }}" placeholder="Siddhi">
                        </div>

                        <div class="form-group">
                            <label for="karana">Karana</label>
                            <input type="text" id="karana" name="karana" class="form-control" value="{{ $editing?->karana }}" placeholder="Bava">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="sunrise">Sunrise</label>
                            <input type="text" id="sunrise" name="sunrise" class="form-control" value="{{ $editing?->sunrise }}" placeholder="06:35 AM">
                        </div>

                        <div class="form-group">
                            <label for="sunset">Sunset</label>
                            <input type="text" id="sunset" name="sunset" class="form-control" value="{{ $editing?->sunset }}" placeholder="06:15 PM">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="moonrise">Moonrise</label>
                            <input type="text" id="moonrise" name="moonrise" class="form-control" value="{{ $editing?->moonrise }}" placeholder="07:20 PM">
                        </div>

                        <div class="form-group">
                            <label for="moonset">Moonset</label>
                            <input type="text" id="moonset" name="moonset" class="form-control" value="{{ $editing?->moonset }}" placeholder="06:50 AM">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Auspicious Muhurats & Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="form-control" placeholder="Abhijit Muhurat: 11:45 AM - 12:35 PM...">{{ $editing?->notes }}</textarea>
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
                            <label for="published_at">Scheduled Publication</label>
                            <input type="datetime-local" id="published_at" name="published_at" class="form-control" value="{{ $editing?->published_at?->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;align-items:center;margin-top:12px">
                        <button type="submit" class="btn-action btn-action-primary" style="padding:10px 20px">
                            {{ $editing ? 'Update Panchang' : 'Save & Publish' }}
                        </button>
                        @if($editing)
                            <a href="{{ route('admin.panchang') }}" class="btn-action">Cancel Edit</a>
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
                <h3>Recorded Dates</h3>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Tithi</th>
                            <th>Status</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->date->format('d M Y') }}</td>
                                <td>{{ $item->location ?: 'General' }}</td>
                                <td>{{ $item->tithi ?: '—' }}</td>
                                <td>
                                    @if($item->status === 'published')
                                        <span class="badge badge-approved">Published</span>
                                    @else
                                        <span class="badge badge-pending">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-group" style="justify-content:flex-end">
                                        <a href="{{ route('admin.panchang', ['edit' => $item->id]) }}" class="btn-action">Edit</a>
                                        <form method="POST" action="{{ route('admin.panchang.delete', $item) }}" onsubmit="return confirm('Delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-action-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:24px;color:var(--admin-muted)">No Panchang entries yet.</td>
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
