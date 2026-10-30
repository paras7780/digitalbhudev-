@extends('layouts.admin')

@section('title', 'Add New Bhudev | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('admin.bhudevs') }}">Bhudev Directory</a>
    <span class="sep">/</span>
    <strong>Add New Bhudev</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Register New Bhudev</h1>
        <p>Directly onboard a Bhudev into the directory with full profile details, images, and services.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('admin.bhudevs') }}" class="btn-action">← Back to Listing</a>
    </div>
</div>

<form method="POST" action="{{ route('admin.bhudevs.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Section 1: Account & Credentials -->
    <div class="form-section">
        <h3 class="form-section-title">1. Account & Contact Information</h3>
        <p class="form-section-desc">Primary login credentials and direct contact details for this Bhudev account.</p>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required maxlength="120" placeholder="E.g., Pandit Rajeshbhai Dave">
            </div>

            <div class="form-group">
                <label for="slug">Profile URL Slug (Optional)</label>
                <div style="display:flex;align-items:center;gap:6px">
                    <span style="color:var(--admin-muted);font-size:13px;font-weight:600">/bhudevs/</span>
                    <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}" maxlength="191" placeholder="e.g. rajeshbhai-dave">
                </div>
                <div class="form-help">Leave blank to generate clean slug automatically from name.</div>
            </div>
        </div>

        <div class="form-grid-2" style="margin-top:14px">
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required maxlength="255" placeholder="bhudev@example.com">
            </div>

            <div class="form-group">
                <label for="phone">Mobile Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" maxlength="25" placeholder="+91 98765 43210">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="password">Account Password (Optional)</label>
                <input type="password" id="password" name="password" class="form-control" minlength="8" maxlength="64" placeholder="Leave blank to auto-generate temporary password">
                <div class="form-help">If left empty, a secure password will be generated automatically and displayed upon creation.</div>
            </div>

            <div class="form-group" style="display:flex;align-items:flex-end">
                <label class="form-checkbox-label" style="margin-bottom:12px">
                    <input type="checkbox" name="send_email" value="1" @checked(old('send_email'))>
                    <span>Request password setup email via SMTP</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Section 2: Location & Service Area -->
    <div class="form-section">
        <h3 class="form-section-title">2. Location & Coverage Area</h3>
        <p class="form-section-desc">Where this Bhudev is based and where they are able to travel for ceremonies.</p>

        <div class="form-grid-3">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}" maxlength="100" placeholder="E.g., Ahmedabad, Surat, Rajkot">
            </div>

            <div class="form-group">
                <label for="state">State</label>
                <input type="text" id="state" name="state" class="form-control" value="{{ old('state', 'Gujarat') }}" maxlength="100" placeholder="Gujarat">
            </div>

            <div class="form-group">
                <label for="service_area">Service Area / Travel Radius</label>
                <input type="text" id="service_area" name="service_area" class="form-control" value="{{ old('service_area') }}" maxlength="255" placeholder="E.g., Anywhere in Ahmedabad & Gandhinagar">
            </div>
        </div>

        <div class="form-group">
            <label for="address">Full Address</label>
            <textarea id="address" name="address" class="form-control" rows="2" maxlength="2000" placeholder="Street, landmark, pincode...">{{ old('address') }}</textarea>
        </div>
    </div>

    <!-- Section 3: Professional Details & Pooja Services -->
    <div class="form-section">
        <h3 class="form-section-title">3. Pooja Services & Qualifications</h3>
        <p class="form-section-desc">Select all traditional ceremonies and enter professional experience details.</p>

        <div class="form-group">
            <label>Offered Pooja & Vedic Ceremonies</label>
            <div class="services-checkbox-grid">
                @foreach($services as $srv)
                    <label class="service-checkbox-card">
                        <input type="checkbox" name="services[]" value="{{ $srv->id }}" @checked(is_array(old('services')) && in_array($srv->id, old('services')))>
                        <span>{{ $srv->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label for="experience_years">Experience (Years)</label>
                <input type="number" id="experience_years" name="experience_years" class="form-control" value="{{ old('experience_years') }}" min="0" max="80" placeholder="15">
            </div>

            <div class="form-group">
                <label for="languages">Languages Known (comma separated)</label>
                <input type="text" id="languages" name="languages" class="form-control" value="{{ old('languages', 'Gujarati, Hindi, Sanskrit') }}" maxlength="300" placeholder="Gujarati, Hindi, Sanskrit">
            </div>

            <div class="form-group">
                <label for="availability">Availability & Timings</label>
                <input type="text" id="availability" name="availability" class="form-control" value="{{ old('availability') }}" maxlength="255" placeholder="E.g., Daily 6:00 AM – 8:00 PM, All Muhurats">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="qualification">Qualifications & Gurukul Background</label>
                <textarea id="qualification" name="qualification" class="form-control" rows="2" maxlength="2000" placeholder="E.g., Ved Pathshala Vapi, Shastri in Shukla Yajurveda...">{{ old('qualification') }}</textarea>
            </div>

            <div class="form-group">
                <label for="expertise">Areas of Vedic Expertise</label>
                <textarea id="expertise" name="expertise" class="form-control" rows="2" maxlength="2000" placeholder="E.g., Vivah Sanskar, Vastu Shanti, Janma Kundali...">{{ old('expertise') }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">Full Biography & Background</label>
            <textarea id="bio" name="bio" class="form-control" rows="4" maxlength="10000" placeholder="Share the Bhudev's family tradition, experience, and service mindset...">{{ old('bio') }}</textarea>
        </div>
    </div>

    <!-- Section 4: Media & Photos -->
    <div class="form-section">
        <h3 class="form-section-title">4. Photos & Media</h3>
        <p class="form-section-desc">Upload a high-quality profile photo and ceremony gallery images (JPG, PNG, WebP up to 3 MB each).</p>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="photo">Profile Photo</label>
                <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-help">Square portrait recommended. Auto-resized when PHP GD is available.</div>
                <div style="margin-top:10px">
                    <img id="photoPreview" src="" alt="Preview" style="display:none;width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--admin-border)">
                </div>
            </div>

            <div class="form-group">
                <label for="gallery">Gallery Images (Up to 8)</label>
                <input type="file" id="gallery" name="gallery[]" multiple class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-help">Photos of poojas, havans, certificates or ceremonies.</div>
            </div>
        </div>
    </div>

    <!-- Section 5: Public Visibility & Approval Status -->
    <div class="form-section">
        <h3 class="form-section-title">5. Status, Visibility & Privacy Settings</h3>
        <p class="form-section-desc">Configure whether this profile is approved immediately and control which contact details are shown to the public.</p>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="status">Initial Profile Status <span class="required">*</span></label>
                <select id="status" name="status" class="form-control" required>
                    <option value="approved" @selected(old('status', 'approved') === 'approved')>Approved (Verified & Ready)</option>
                    <option value="pending" @selected(old('status') === 'pending')>Pending Review</option>
                </select>
            </div>

            <div class="form-group" style="display:flex;align-items:flex-end">
                <label class="form-checkbox-label" style="margin-bottom:12px">
                    <input type="checkbox" name="is_visible" value="1" @checked(old('is_visible', true))>
                    <strong>Publicly Visible on DigitalBhudev directory</strong>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-top:8px">
            <label style="margin-bottom:8px">Public Contact Privacy Controls:</label>
            <div>
                <label class="form-checkbox-label">
                    <input type="checkbox" name="show_mobile" value="1" @checked(old('show_mobile', true))>
                    <span>Display mobile number publicly on profile</span>
                </label>
                <label class="form-checkbox-label">
                    <input type="checkbox" name="show_email" value="1" @checked(old('show_email', false))>
                    <span>Display email address publicly on profile</span>
                </label>
                <label class="form-checkbox-label">
                    <input type="checkbox" name="show_address" value="1" @checked(old('show_address', false))>
                    <span>Display full street address publicly on profile</span>
                </label>
            </div>
        </div>

        <div class="form-grid-2" style="margin-top:16px">
            <div class="form-group">
                <label for="seo_title">Custom SEO Title (Optional)</label>
                <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{ old('seo_title') }}" maxlength="180" placeholder="Pandit Rajeshbhai Dave — Trusted Bhudev in Ahmedabad">
            </div>

            <div class="form-group">
                <label for="seo_description">SEO Meta Description (Optional)</label>
                <input type="text" id="seo_description" name="seo_description" class="form-control" value="{{ old('seo_description') }}" maxlength="255" placeholder="Book Pandit Rajeshbhai Dave for Satyanarayan Katha, Griha Pravesh and Vedic pooja services.">
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div style="display:flex;gap:12px;align-items:center;margin-bottom:40px">
        <button type="submit" class="btn-action btn-action-primary" style="padding:12px 24px;font-size:14px">
            ✓ Create Bhudev Account & Profile
        </button>
        <a href="{{ route('admin.bhudevs') }}" class="btn-action" style="padding:12px 20px">Cancel</a>
    </div>
</form>
@endsection
