@extends('layouts.bhudev')

@section('title', 'Edit Profile & Services | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('bhudev.dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <strong>Edit Profile</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Edit Profile & Services</h1>
        <p>Update your personal details, ceremony expertise, and privacy settings.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('bhudev.dashboard') }}" class="btn btn-light">
            &larr; Back to Dashboard
        </a>
    </div>
</div>

@if($profile->status === 'approved')
    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:5px solid #3b82f6;border-radius:8px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px;font-size:13px;color:#1e40af">
        <span style="font-size:18px">ℹ️</span>
        <div>
            <strong>Notice for Approved Bhudevs:</strong>
            Because your profile is currently verified and public, changes saved here will be submitted as a reviewable revision. Your currently live profile remains active on the directory until the changes are reviewed and approved.
        </div>
    </div>
@else
    <div style="background:#fff9eb;border:1px solid #fedb9b;border-left:5px solid #d5a45c;border-radius:8px;padding:14px 18px;margin-bottom:24px;display:flex;align-items:flex-start;gap:12px;font-size:13px;color:#875200">
        <span style="font-size:18px">📝</span>
        <div>
            Fill in your complete and accurate Vedic qualifications, service offerings, and contact information. Once saved, your profile will be reviewed by the administrator for approval.
        </div>
    </div>
@endif

<form method="POST" action="{{ route('bhudev.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Basic Information --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>1. Basic Information & Profile Photo</h3>
        </div>
        <div class="admin-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                <div class="form-row" style="margin-bottom:0">
                    <label for="display_name" class="form-label" style="font-weight:600;font-size:13px">Full Name / Display Name <span style="color:red">*</span></label>
                    <input type="text" id="display_name" name="display_name" class="form-control" value="{{ old('display_name', $profile->display_name) }}" required style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div class="form-row" style="margin-bottom:0">
                    <label for="availability" class="form-label" style="font-weight:600;font-size:13px">Availability Status</label>
                    <input type="text" id="availability" name="availability" class="form-control" value="{{ old('availability', $profile->availability) }}" placeholder="e.g. Available on Booking, Full-time, Weekends only" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div class="form-row" style="margin-bottom:0">
                    <label for="experience_years" class="form-label" style="font-weight:600;font-size:13px">Years of Vedic Experience</label>
                    <input type="number" id="experience_years" name="experience_years" min="0" max="80" class="form-control" value="{{ old('experience_years', $profile->experience_years) }}" placeholder="e.g. 15" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div class="form-row" style="margin-bottom:0">
                    <label for="photo" class="form-label" style="font-weight:600;font-size:13px">Profile Photo (Max 3 MB, JPG/PNG/WebP)</label>
                    <div style="display:flex;gap:12px;align-items:center">
                        @if($profile->photo_path)
                            <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->display_name }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;border:1px solid var(--admin-gold);flex-shrink:0">
                        @endif
                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" style="font-size:12px">
                    </div>
                </div>
            </div>

            <div class="form-row" style="margin-top:18px;margin-bottom:0">
                <label for="qualification" class="form-label" style="font-weight:600;font-size:13px">Vedic Qualifications & Education</label>
                <textarea id="qualification" name="qualification" rows="2" class="form-control" placeholder="e.g. Sanskrit Pathshala, Shastri, Acharya degrees or traditional lineage education" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">{{ old('qualification', $profile->qualification) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Location & Travel Area --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>2. Location & Travel Area</h3>
        </div>
        <div class="admin-card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-bottom:18px">
                <div class="form-row" style="margin-bottom:0">
                    <label for="city" class="form-label" style="font-weight:600;font-size:13px">City / Town</label>
                    <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $profile->city) }}" placeholder="e.g. Ahmedabad" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div class="form-row" style="margin-bottom:0">
                    <label for="state" class="form-label" style="font-weight:600;font-size:13px">State</label>
                    <input type="text" id="state" name="state" class="form-control" value="{{ old('state', $profile->state ?? 'Gujarat') }}" placeholder="e.g. Gujarat" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div class="form-row" style="margin-bottom:0">
                    <label for="service_area" class="form-label" style="font-weight:600;font-size:13px">Service Area / Travel Scope</label>
                    <input type="text" id="service_area" name="service_area" class="form-control" value="{{ old('service_area', $profile->service_area) }}" placeholder="e.g. Entire City, Across State, Abroad on request" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>
            </div>

            <div class="form-row" style="margin-bottom:0">
                <label for="address" class="form-label" style="font-weight:600;font-size:13px">Complete Street Address</label>
                <textarea id="address" name="address" rows="2" class="form-control" placeholder="Your residential / office address for verification" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">{{ old('address', $profile->address) }}</textarea>
                <small style="color:var(--admin-muted);font-size:11px;display:block;margin-top:4px">Note: You can keep your address hidden from public devotees below in Privacy Settings.</small>
            </div>
        </div>
    </div>

    {{-- Professional Details & Expertise --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>3. Languages & Expertise</h3>
        </div>
        <div class="admin-card-body">
            <div class="form-row" style="margin-bottom:18px">
                <label for="preferred_locale" class="form-label" style="font-weight:600;font-size:13px">
                    {{ __('messages.dashboard.preferred_language') }} (Dashboard UI Language)
                </label>
                <select id="preferred_locale" name="preferred_locale" class="form-control" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px;background:#fff">
                    <option value="en" {{ old('preferred_locale', $profile->preferred_locale ?? 'en') === 'en' ? 'selected' : '' }}>English (Default)</option>
                    <option value="gu" {{ old('preferred_locale', $profile->preferred_locale ?? 'en') === 'gu' ? 'selected' : '' }}>ગુજરાતી (Gujarati)</option>
                    <option value="hi" {{ old('preferred_locale', $profile->preferred_locale ?? 'en') === 'hi' ? 'selected' : '' }}>हिन्दी (Hindi)</option>
                </select>
                <small style="color:var(--admin-muted);font-size:12px;margin-top:4px;display:block">
                    {{ __('messages.dashboard.preferred_language_desc') }}
                </small>
            </div>

            <div class="form-row" style="margin-bottom:18px">
                <label for="languages" class="form-label" style="font-weight:600;font-size:13px">Languages Spoken (Comma-separated)</label>
                <input type="text" id="languages" name="languages" class="form-control" value="{{ old('languages', !empty($profile->languages) ? implode(', ', $profile->languages) : 'Gujarati, Hindi, Sanskrit') }}" placeholder="Gujarati, Hindi, Sanskrit, English" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
            </div>

            <div class="form-row" style="margin-bottom:18px">
                <label for="expertise" class="form-label" style="font-weight:600;font-size:13px">Vedic Expertise & Specializations</label>
                <textarea id="expertise" name="expertise" rows="3" class="form-control" placeholder="Describe specific Vedic rituals, Yajnas, Anushthan, Kundali consultation, or Griha Shanti ceremonies you specialize in." style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">{{ old('expertise', $profile->expertise) }}</textarea>
            </div>

            <div class="form-row" style="margin-bottom:0">
                <label for="bio" class="form-label" style="font-weight:600;font-size:13px">Biography / About Yourself</label>
                <textarea id="bio" name="bio" rows="4" class="form-control" placeholder="Share your journey as a Bhudev, lineage background, values, and how you conduct ceremonies." style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">{{ old('bio', $profile->bio) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Pooja Services Offered --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>4. Pooja Services Offered</h3>
            <span style="font-size:12px;color:var(--admin-muted)">Select all rituals you conduct</span>
        </div>
        <div class="admin-card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(260px, 1fr));gap:12px">
                @foreach($services as $srv)
                    <label style="display:flex;align-items:flex-start;gap:10px;padding:12px 14px;border:1px solid var(--admin-border);border-radius:6px;cursor:pointer;background:#faf7f0;transition:all 0.15s">
                        <input type="checkbox" name="services[]" value="{{ $srv->id }}" @checked($profile->services->contains($srv->id)) style="margin-top:3px">
                        <div>
                            <strong style="display:block;font-size:13px;color:var(--admin-ink)">{{ $srv->name }}</strong>
                            @if($srv->short_description)
                                <small style="display:block;font-size:11px;color:var(--admin-muted);line-height:1.4">{{ Str::limit($srv->short_description, 70) }}</small>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Privacy Preferences --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>5. Devotee Privacy & Visibility Settings</h3>
        </div>
        <div class="admin-card-body">
            <p style="margin:0 0 16px;color:var(--admin-muted);font-size:13px">
                Control which personal contact details are publicly visible on your profile page to devotees:
            </p>

            <div style="display:flex;flex-direction:column;gap:12px">
                <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="show_mobile" value="1" @checked(old('show_mobile', $profile->show_mobile))>
                    <span>Show my mobile number (<strong>{{ auth()->user()->phone ?? 'N/A' }}</strong>) on my public profile</span>
                </label>

                <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="show_email" value="1" @checked(old('show_email', $profile->show_email))>
                    <span>Show my email address (<strong>{{ auth()->user()->email }}</strong>) on my public profile</span>
                </label>

                <label style="display:flex;align-items:center;gap:10px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="show_address" value="1" @checked(old('show_address', $profile->show_address))>
                    <span>Show my complete street address on my public profile (if unchecked, only City and State are displayed)</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div style="display:flex;gap:14px;align-items:center;margin-bottom:40px">
        <button type="submit" class="btn btn-primary" style="padding:12px 28px;font-size:14px;font-weight:600">
            💾 Save Profile Details
        </button>
        <a href="{{ route('bhudev.dashboard') }}" class="btn btn-light" style="padding:12px 20px">
            Cancel
        </a>
    </div>
</form>
@endsection
