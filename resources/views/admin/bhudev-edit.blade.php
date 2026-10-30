@extends('layouts.admin')

@section('title', 'Edit ' . $profile->display_name . ' | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('admin.bhudevs') }}">Bhudev Directory</a>
    <span class="sep">/</span>
    <a href="{{ route('admin.bhudevs.show', $profile) }}">{{ $profile->display_name }}</a>
    <span class="sep">/</span>
    <strong>Edit</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Edit Bhudev: {{ $profile->display_name }}</h1>
        <p>Update profile details, services, credentials, and visibility settings.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.bhudevs.show', $profile) }}" class="btn-action">← View Profile Details</a>
    </div>
</div>

<form method="POST" action="{{ route('admin.bhudevs.update', $profile) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Section 1: Account & Credentials -->
    <div class="form-section">
        <h3 class="form-section-title">1. Account & Contact Information</h3>
        <p class="form-section-desc">Manage primary account details, email, and password credentials.</p>

        <div class="form-grid-3">
            <div class="form-group">
                <label for="name">Display Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $profile->display_name) }}" required maxlength="120">
            </div>

            <div class="form-group">
                <label for="email">Account Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $profile->user->email) }}" required maxlength="255">
            </div>

            <div class="form-group">
                <label for="phone">Mobile Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $profile->user->phone) }}" maxlength="25">
            </div>
        </div>

        <div class="form-grid-2" style="margin-top:14px">
            <div class="form-group">
                <label for="slug">Profile URL Slug <span class="required">*</span></label>
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="color:var(--admin-muted);font-size:13px;font-weight:600">/bhudevs/</span>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $profile->slug) }}" required maxlength="191" placeholder="e.g. paras-rajyaguru">
                </div>
                <div class="form-help">Custom URL slug for this Bhudev's public profile page.</div>
            </div>

            <div class="form-group">
                <label for="password">Change Password (Optional)</label>
                <input type="password" id="password" name="password" class="form-control" minlength="8" maxlength="64" placeholder="Leave blank to retain current password">
                <div class="form-help">Only fill this if you want to set a new password for this Bhudev account.</div>
            </div>
        </div>
    </div>

    <!-- Section 2: Location & Service Area -->
    <div class="form-section">
        <h3 class="form-section-title">2. Location & Travel Area</h3>
        <p class="form-section-desc">Manage geographical presence and coverage territory.</p>

        <div class="form-grid-3">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $profile->city) }}" maxlength="100">
            </div>

            <div class="form-group">
                <label for="state">State</label>
                <input type="text" id="state" name="state" class="form-control" value="{{ old('state', $profile->state) }}" maxlength="100">
            </div>

            <div class="form-group">
                <label for="service_area">Service Area / Coverage Radius</label>
                <input type="text" id="service_area" name="service_area" class="form-control" value="{{ old('service_area', $profile->service_area) }}" maxlength="255">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Full Address</label>
            <textarea id="address" name="address" class="form-control" rows="2" maxlength="2000">{{ old('address', $profile->address) }}</textarea>
        </div>
    </div>

    <!-- Section 3: Professional Details & Services -->
    <div class="form-section">
        <h3 class="form-section-title">3. Pooja Services & Qualifications</h3>
        <p class="form-section-desc">Update offered pooja services, years of experience, languages, and biography.</p>

        <div class="form-group">
            <label>Offered Pooja & Vedic Ceremonies</label>
            <div class="services-checkbox-grid">
                @foreach($services as $srv)
                    <label class="service-checkbox-card">
                        <input type="checkbox" name="services[]" value="{{ $srv->id }}" @checked(in_array($srv->id, old('services', $profile->services->pluck('id')->toArray())))>
                        <span>{{ $srv->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label for="experience_years">Experience (Years)</label>
                <input type="number" id="experience_years" name="experience_years" class="form-control" value="{{ old('experience_years', $profile->experience_years) }}" min="0" max="80">
            </div>

            <div class="form-group">
                <label for="languages">Languages (comma separated)</label>
                <input type="text" id="languages" name="languages" class="form-control" value="{{ old('languages', !empty($profile->languages) ? implode(', ', $profile->languages) : '') }}" maxlength="300">
            </div>

            <div class="form-group">
                <label for="availability">Availability & Timings</label>
                <input type="text" id="availability" name="availability" class="form-control" value="{{ old('availability', $profile->availability) }}" maxlength="255" placeholder="E.g., Daily 6:00 AM – 8:00 PM">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="qualification">Qualifications & Background</label>
                <textarea id="qualification" name="qualification" class="form-control" rows="2" maxlength="2000">{{ old('qualification', $profile->qualification) }}</textarea>
            </div>

            <div class="form-group">
                <label for="expertise">Specialized Expertise</label>
                <textarea id="expertise" name="expertise" class="form-control" rows="2" maxlength="2000">{{ old('expertise', $profile->expertise) }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Full Biography</label>
            <textarea id="bio" name="bio" class="form-control" rows="4" maxlength="10000">{{ old('bio', $profile->bio) }}</textarea>
        </div>
    </div>

    <!-- Section 4: Media & Photos -->
    <div class="form-section">
        <h3 class="form-section-title">4. Photos & Media</h3>
        <p class="form-section-desc">Manage profile photo and gallery images.</p>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="photo">Profile Photo</label>
                <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-help">Leave empty to keep existing photo. Max size 3 MB.</div>

                <div style="margin-top:12px;display:flex;align-items:center;gap:14px">
                    @if($profile->photo_path)
                        <img id="photoCurrent" src="{{ asset('storage/' . $profile->photo_path) }}" alt="Current Photo" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid var(--admin-border)">
                    @endif
                    <img id="photoPreview" src="" alt="New Preview" style="display:none;width:70px;height:70px;border-radius:50%;object-fit:cover;border:2px solid var(--admin-saffron)">
                </div>
            </div>

            <div class="form-group">
                <label for="gallery">Add More Gallery Images</label>
                <input type="file" id="gallery" name="gallery[]" multiple class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-help">Upload additional ceremony or event images.</div>
            </div>
        </div>

        @if($profile->images->isNotEmpty())
            <div style="margin-top:18px">
                <label style="display:block;font-size:12px;font-weight:700;margin-bottom:8px">Existing Gallery Images ({{ $profile->images->count() }}):</label>
                <div class="gallery-thumbs-grid">
                    @foreach($profile->images as $img)
                        <div class="gallery-thumb-item">
                            <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->alt_text }}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Section 5: Status & Privacy -->
    <div class="form-section">
        <h3 class="form-section-title">5. Status & Public Visibility Settings</h3>
        <p class="form-section-desc">Manage approval status, public accessibility, and contact privacy.</p>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="status">Profile Status <span class="required">*</span></label>
                <select id="status" name="status" class="form-control" required>
                    <option value="approved" @selected(old('status', $profile->status) === 'approved')>Approved</option>
                    <option value="pending" @selected(old('status', $profile->status) === 'pending')>Pending Review</option>
                    <option value="suspended" @selected(old('status', $profile->status) === 'suspended')>Suspended</option>
                    <option value="rejected" @selected(old('status', $profile->status) === 'rejected')>Rejected</option>
                </select>
            </div>

            <div class="form-group" style="display:flex;align-items:flex-end">
                <label class="form-checkbox-label" style="margin-bottom:12px">
                    <input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', $profile->is_visible))>
                    <strong>Publicly Visible on DigitalBhudev directory (Only applies if Approved)</strong>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-top:8px">
            <label style="margin-bottom:8px">Public Contact Privacy Controls:</label>
            <div>
                <label class="form-checkbox-label">
                    <input type="checkbox" name="show_mobile" value="1" @checked(old('show_mobile', $profile->show_mobile))>
                    <span>Display mobile number publicly on profile</span>
                </label>
                <label class="form-checkbox-label">
                    <input type="checkbox" name="show_email" value="1" @checked(old('show_email', $profile->show_email))>
                    <span>Display email address publicly on profile</span>
                </label>
                <label class="form-checkbox-label">
                    <input type="checkbox" name="show_address" value="1" @checked(old('show_address', $profile->show_address))>
                    <span>Display full street address publicly on profile</span>
                </label>
            </div>
        </div>

        <div class="form-grid-2" style="margin-top:16px">
            <div class="form-group">
                <label for="seo_title">Custom SEO Title</label>
                <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{ old('seo_title', $profile->seo_title) }}" maxlength="180">
            </div>

            <div class="form-group">
                <label for="seo_description">SEO Meta Description</label>
                <input type="text" id="seo_description" name="seo_description" class="form-control" value="{{ old('seo_description', $profile->seo_description) }}" maxlength="255">
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display:flex;gap:12px;align-items:center;margin-bottom:40px">
        <button type="submit" class="btn-action btn-action-primary" style="padding:12px 24px;font-size:14px">
            ✓ Save Profile Changes
        </button>
        <a href="{{ route('admin.bhudevs.show', $profile) }}" class="btn-action" style="padding:12px 20px">Cancel</a>
    </div>
</form>
@endsection
