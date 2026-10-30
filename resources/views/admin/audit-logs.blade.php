@extends('layouts.admin')

@section('title', 'Audit Logs | DigitalBhudev Admin')

@section('breadcrumbs')
    <span class="sep">/</span>
    <strong>Audit Logs</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <h1>Administrative Audit Trail</h1>
        <p>Immutable log of sensitive administrative actions, profile status transitions, and authentication events.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Actor</th>
                    <th>Action</th>
                    <th>Target Subject</th>
                    <th>Context & Metadata</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td style="white-space:nowrap">
                            <strong>{{ $item->created_at->format('d M Y') }}</strong>
                            <small style="display:block;color:var(--admin-muted)">{{ $item->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            <strong>{{ $item->user?->name ?: 'System' }}</strong>
                            <small style="display:block;color:var(--admin-muted)">{{ $item->user?->email }}</small>
                        </td>
                        <td>
                            <code style="background:#f4ece2;color:#7a4b1c;padding:3px 6px;border-radius:4px;font-size:11px">
                                {{ $item->action }}
                            </code>
                        </td>
                        <td>
                            @if($item->subject_type)
                                <span style="font-weight:600">{{ class_basename($item->subject_type) }}</span>
                                <small style="color:var(--admin-muted)">#{{ $item->subject_id }}</small>
                            @else
                                <span style="color:var(--admin-muted)">—</span>
                            @endif
                        </td>
                        <td>
                            @if(!empty($item->metadata))
                                <details style="cursor:pointer;font-size:11px">
                                    <summary style="color:var(--admin-saffron);font-weight:600">View Details</summary>
                                    <pre style="background:#fcf9f4;border:1px solid var(--admin-border);padding:8px;border-radius:4px;margin-top:4px;overflow-x:auto;max-width:300px;font-size:10px">{{ json_encode($item->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </details>
                            @else
                                <span style="color:var(--admin-muted);font-size:11px">None</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:36px;color:var(--admin-muted)">
                            No audit logs recorded.
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
