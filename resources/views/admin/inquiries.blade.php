@extends('layouts.admin')

@section('title', 'Inquiries & Pooja Bookings | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Inquiries & Pooja Bookings</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Inquiries &amp; Pooja Bookings</h1>
        <p>Review customer booking requests, contact inquiries, and devotee messages.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:130px">Received At</th>
                    <th style="width:110px">Type</th>
                    <th>Devotee Details</th>
                    <th>Booking / Inquiry Details</th>
                    <th style="width:320px;text-align:right">Update Status &amp; Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="white-space:nowrap">
                            <strong>{{ $item->created_at->format('d M Y') }}</strong>
                            <small style="display:block;color:var(--admin-muted)">{{ $item->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            @if($item->type === 'booking')
                                <span class="badge badge-approved" style="font-size:11px">
                                    🪔 Booking
                                </span>
                            @else
                                <span class="badge badge-pending" style="font-size:11px">
                                    ✉️ Contact
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->phone)
                                <div style="font-size:12px;margin:2px 0">
                                    <a href="tel:{{ $item->phone }}" style="color:var(--admin-ink);text-decoration:none">📞 {{ $item->phone }}</a>
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $item->phone);
                                        if (strlen($cleanPhone) === 10) $cleanPhone = '91' . $cleanPhone;
                                    @endphp
                                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}" target="_blank" style="color:#25d366;margin-left:6px;font-weight:700" title="Chat on WhatsApp">
                                        💬 WhatsApp
                                    </a>
                                </div>
                            @endif
                            <div style="font-size:12px">
                                <a href="mailto:{{ $item->email }}" style="color:var(--admin-muted)">{{ $item->email }}</a>
                            </div>
                            @if($item->city)
                                <small style="display:block;color:var(--admin-muted)">📍 {{ $item->city }}</small>
                            @endif
                        </td>
                        <td style="max-width:320px">
                            @if($item->pooja_service)
                                <div style="font-weight:700;color:#b94e20;font-size:13px">
                                    🕉️ {{ $item->pooja_service }}
                                </div>
                            @endif
                            @if($item->preferred_date)
                                <small style="display:block;color:#216a30;font-weight:600">
                                    📅 Date: {{ $item->preferred_date->format('d M Y') }}
                                </small>
                            @endif
                            @if($item->bhudevProfile)
                                <small style="display:block;color:#806f5f;margin:2px 0">
                                    Target Bhudev: <strong>{{ $item->bhudevProfile->display_name }}</strong> ({{ $item->bhudevProfile->city }})
                                </small>
                            @endif
                            @if($item->subject && $item->type !== 'booking')
                                <strong style="color:var(--admin-ink);font-size:13px">{{ $item->subject }}</strong>
                            @endif
                            <p style="margin:4px 0 0;color:var(--admin-muted);font-size:12px;line-height:1.5">{{ $item->message }}</p>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.inquiries.update', $item) }}" style="display:flex;flex-direction:column;gap:6px;align-items:flex-end">
                                @csrf
                                @method('PATCH')
                                <div style="display:flex;gap:6px;width:100%">
                                    <select name="status" class="admin-select-input" style="flex:1">
                                        @foreach(['new','in_progress','closed'] as $s)
                                            <option value="{{ $s }}" @selected($item->status === $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn-action btn-action-primary">Save</button>
                                </div>
                                <input type="text" name="admin_note" value="{{ $item->admin_note }}" placeholder="Add admin note..." class="admin-search-input" style="width:100%">
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:36px;color:var(--admin-muted)">
                            ✓ No inquiries or bookings received yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
        <div style="padding:14px 20px;border-top:1px solid var(--admin-border)">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection
