@extends('layouts.bhudev')

@section('title', 'Change Password | Bhudev Portal')

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('bhudev.dashboard') }}">Dashboard</a>
    <span class="sep">/</span>
    <strong>Change Password</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Change Password</h1>
        <p>Ensure your Bhudev account is protected with a strong, confidential password.</p>
    </div>
    <div class="admin-page-actions">
        <a href="{{ route('bhudev.dashboard') }}" class="btn btn-light">
            &larr; Back to Dashboard
        </a>
    </div>
</div>

<div style="max-width:600px">
    <div class="admin-card">
        <div class="admin-card-header">
            <h3>Update Your Account Password</h3>
        </div>
        <div class="admin-card-body">
            <form method="POST" action="{{ route('bhudev.password.update') }}">
                @csrf
                @method('PUT')

                <div class="form-row" style="margin-bottom:18px">
                    <label for="current_password" class="form-label" style="font-weight:600;font-size:13px">
                        Current Password <span style="color:red">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div class="form-row" style="margin-bottom:18px">
                    <label for="password" class="form-label" style="font-weight:600;font-size:13px">
                        New Password <span style="color:red">*</span>
                    </label>
                    <input type="password" id="password" name="password" minlength="8" class="form-control" required autocomplete="new-password" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                    <small style="display:block;margin-top:4px;color:var(--admin-muted);font-size:11px">
                        Must be at least 8 characters long.
                    </small>
                </div>

                <div class="form-row" style="margin-bottom:24px">
                    <label for="password_confirmation" class="form-label" style="font-weight:600;font-size:13px">
                        Confirm New Password <span style="color:red">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" class="form-control" required autocomplete="new-password" style="width:100%;padding:9px 12px;border:1px solid var(--admin-border);border-radius:6px">
                </div>

                <div style="display:flex;gap:12px;align-items:center">
                    <button type="submit" class="btn btn-primary" style="padding:10px 24px">
                        🔒 Update Password
                    </button>
                    <a href="{{ route('bhudev.dashboard') }}" class="btn btn-light" style="padding:10px 18px">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
