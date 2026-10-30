@extends('layouts.bhudev')

@section('title', 'Ceremony Photo Gallery | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('bhudev.dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <strong>Photo Gallery</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Ceremony Photo Gallery</h1>
        <p>Upload and manage photos of your pujas, Yajnas, and Vedic rituals.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('bhudev.dashboard') }}" class="btn btn-light">
            &larr; Back to Dashboard
        </a>
    </div>
</div>

{{-- Upload Card --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3>Upload New Ceremony Photos</h3>
        <span style="font-size:12px;color:var(--admin-muted)">Max 3 MB per photo (JPG, PNG, WebP)</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('bhudev.gallery.upload') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:flex;gap:16px;align-items:center;flex-wrap:wrap">
                <input type="file" name="gallery[]" multiple accept="image/jpeg,image/png,image/webp" required style="font-size:13px;padding:8px;border:1px solid var(--admin-border);border-radius:6px;background:#faf8f5">
                <button type="submit" class="btn btn-primary" style="padding:9px 20px">
                    📤 Upload Selected Photos
                </button>
            </div>
            <small style="display:block;margin-top:8px;color:var(--admin-muted);font-size:12px">
                You can select multiple photos at once (up to 8 files). High-resolution, authentic ceremony photos enhance your credibility among devotees.
            </small>
        </form>
    </div>
</div>

{{-- Existing Photos Grid --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h3>Current Ceremony Photos ({{ $profile->images->count() }})</h3>
        @if($profile->is_visible)
            <a href="{{ route('bhudevs.show', $profile->slug) }}" target="_blank" style="font-size:12px;color:var(--admin-saffron);text-decoration:none;font-weight:600">
                View on Public Profile ↗
            </a>
        @endif
    </div>
    <div class="admin-card-body">
        @if($profile->images->isNotEmpty())
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:18px">
                @foreach($profile->images as $img)
                    <div style="border:1px solid var(--admin-border);border-radius:8px;overflow:hidden;background:#fff;display:flex;flex-direction:column;box-shadow:0 2px 8px rgba(0,0,0,0.04)">
                        <div style="position:relative;height:170px;background:#f5efe6">
                            <img src="{{ asset('storage/' . $img->path) }}" alt="{{ $img->alt_text ?: 'Ceremony photo' }}" style="width:100%;height:100%;object-fit:cover;display:block">
                            <div style="position:absolute;top:8px;left:8px">
                                @if($img->is_approved)
                                    <span class="badge badge-approved" style="font-size:10px;padding:2px 8px;box-shadow:0 1px 4px rgba(0,0,0,0.2)">✓ Approved</span>
                                @else
                                    <span class="badge badge-pending" style="font-size:10px;padding:2px 8px;box-shadow:0 1px 4px rgba(0,0,0,0.2)">Pending Review</span>
                                @endif
                            </div>
                        </div>

                        <div style="padding:12px;display:flex;align-items:center;justify-content:space-between;gap:8px;background:#faf8f5;border-top:1px solid var(--admin-border);margin-top:auto">
                            <span style="font-size:11px;color:var(--admin-muted)">
                                {{ $img->created_at->format('d M Y') }}
                            </span>

                            <form method="POST" action="{{ route('bhudev.gallery.delete', $img) }}" onsubmit="return confirm('Are you sure you want to remove this photo?');" style="margin:0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:4px 9px;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.15s">
                                    🗑️ Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:48px 20px;color:var(--admin-muted)">
                <div style="font-size:44px;margin-bottom:12px">🖼️</div>
                <h4 style="font-family:'Playfair Display',serif;font-size:18px;margin:0 0 6px;color:var(--admin-ink)">No photos in your gallery yet</h4>
                <p style="margin:0 auto 18px;max-width:440px;font-size:13px;line-height:1.5">
                    Devotees appreciate seeing authentic photos of pujas, altar setups, and Vedic rituals. Select photos using the upload form above to showcase your work.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
