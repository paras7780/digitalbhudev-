@extends('layouts.bhudev')

@section('title', __('messages.samagri_studio.title') . ' | ' . config('app.name'))

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@400;500;600;700&family=Noto+Sans+Gujarati:wght@400;600;700;800&family=Rozha+One&display=swap" rel="stylesheet">
<script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
<style>
    /* Studio Layout Styles */
    .studio-container {
        display: grid;
        grid-template-columns: 460px 1fr;
        gap: 28px;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .studio-container {
            grid-template-columns: 1fr;
        }
    }

    .studio-panel {
        background: #ffffff;
        border: 1px solid #e7ded5;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(32, 26, 21, 0.05);
        overflow: hidden;
    }

    .studio-panel-header {
        background: #fdfaf6;
        border-bottom: 1px solid #ebd8c5;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .studio-panel-body {
        padding: 20px;
    }

    .form-group-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .form-label-sm {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #4a3828;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-vedic {
        width: 100%;
        padding: 8px 12px;
        font-size: 13.5px;
        font-family: 'Hind Vadodara', 'Noto Sans Gujarati', sans-serif;
        color: #201a15;
        background: #ffffff;
        border: 1px solid #d5c4b2;
        border-radius: 6px;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .form-control-vedic:focus {
        border-color: #b81414;
        box-shadow: 0 0 0 3px rgba(184, 20, 20, 0.12);
        outline: none;
    }

    .textarea-vedic {
        min-height: 220px;
        line-height: 1.6;
        resize: vertical;
        white-space: pre;
    }

    /* Live Preview Canvas Wrapper */
    .preview-canvas-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #e9e3dc;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #dcd1c4;
    }

    .preview-toolbar {
        width: 100%;
        max-width: 595px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .btn-action-vedic {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action-vedic.primary {
        background: #b81414;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(184, 20, 20, 0.3);
    }
    .btn-action-vedic.primary:hover {
        background: #960f0f;
    }

    .btn-action-vedic.whatsapp {
        background: #25D366;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 211, 102, 0.3);
    }
    .btn-action-vedic.whatsapp:hover {
        background: #1ebc57;
    }

    .btn-action-vedic.secondary {
        background: #ffffff;
        color: #4a3828;
        border: 1px solid #cfbeaf;
    }
    .btn-action-vedic.secondary:hover {
        background: #f7f3ee;
        border-color: #a89481;
    }

    /* The Sacred Patrika Card (Exact 595 x 842 pt A4 Scale) */
    .patrika-card-stage {
        position: relative;
        width: 595px;
        height: 842px;
        background-color: #fcf9f2;
        background-image: url("{{ asset('assets/images/samagri/clean_card_template.png') }}");
        background-size: 100% 100%;
        background-repeat: no-repeat;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.22), 0 2px 8px rgba(0, 0, 0, 0.12);
        border-radius: 4px;
        overflow: hidden;
        user-select: text;
        font-family: 'Hind Vadodara', 'Noto Sans Gujarati', sans-serif;
        color: #a01010;
        box-sizing: border-box;
    }

    /* Scale Patrika on Smaller Screens */
    @media (max-width: 680px) {
        .preview-canvas-wrapper {
            padding: 12px 6px;
            overflow-x: hidden;
            width: 100%;
            box-sizing: border-box;
        }
        .preview-toolbar {
            flex-direction: column;
            gap: 10px;
            align-items: stretch;
        }
        .preview-toolbar > div {
            width: 100%;
            justify-content: space-between;
        }
        .preview-toolbar .btn-action-vedic {
            flex: 1;
            justify-content: center;
            padding: 9px 8px;
            font-size: 12px;
        }
        .patrika-card-stage {
            transform: scale(calc((100vw - 36px) / 595));
            transform-origin: top center;
            margin-bottom: calc(-842px * (1 - ((100vw - 36px) / 595)));
        }
    }

    /* Figma Layout Coordinates */
    .card-priest-left {
        position: absolute;
        top: 36px;
        left: 45px;
        width: 180px;
        text-align: left;
        line-height: 1.35;
    }
    .card-priest-right {
        position: absolute;
        top: 36px;
        right: 45px;
        width: 180px;
        text-align: right;
        line-height: 1.35;
    }
    .card-priest-name {
        font-size: 15px;
        font-weight: 700;
        color: #a01010;
        letter-spacing: 0.3px;
    }
    .card-priest-phone {
        font-size: 13.5px;
        font-weight: 600;
        color: #a01010;
    }

    .card-top-mantra {
        position: absolute;
        top: 130px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 14.5px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #a01010;
    }

    .card-top-firm {
        position: absolute;
        top: 152px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #a01010;
        text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
    }

    .card-title-banner {
        position: absolute;
        top: 198px;
        left: 70px;
        right: 70px;
        text-align: center;
        padding: 5px 12px;
        background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.85) 0%, rgba(255, 248, 230, 0.4) 75%, transparent 100%);
        border-top: 1px dashed rgba(160, 16, 16, 0.45);
        border-bottom: 1px dashed rgba(160, 16, 16, 0.45);
    }

    .card-title-text {
        font-size: 22px;
        font-weight: 800;
        color: #a01010;
        letter-spacing: 0.5px;
        margin: 0;
    }

    /* Columns Area */
    .card-columns-container {
        position: absolute;
        top: 252px;
        left: 48px;
        right: 48px;
        bottom: 78px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        overflow: hidden;
    }

    .card-column {
        height: 100%;
        overflow: hidden;
    }

    .samagri-item-list {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 13.5px;
        line-height: 1.62;
        font-weight: 600;
        color: #a01010;
    }

    .samagri-item-list.compact {
        font-size: 12px;
        line-height: 1.48;
    }

    .samagri-item-list.large {
        font-size: 15px;
        line-height: 1.72;
    }

    .samagri-item-list li {
        display: flex;
        align-items: baseline;
        gap: 6px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .samagri-item-list li::before {
        content: "•";
        color: #a01010;
        font-size: 14px;
        font-weight: 800;
        flex-shrink: 0;
    }

    /* Bottom Yajman Area */
    .card-bottom-yajman {
        position: absolute;
        bottom: 34px;
        left: 55px;
        right: 55px;
        text-align: left;
        font-size: 14.5px;
        font-weight: 700;
        color: #a01010;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden !important;
        }
        .patrika-card-stage, .patrika-card-stage * {
            visibility: visible !important;
        }
        .patrika-card-stage {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@section('breadcrumbs')
    <span class="sep">/</span>
    <a href="{{ route('bhudev.dashboard') }}">{{ __('messages.nav.my_dashboard') }}</a>
    <span class="sep">/</span>
    <strong>{{ __('messages.samagri_studio.title') }}</strong>
@endsection

@section('content')
<div class="admin-page-header">
    <div class="admin-page-title">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
            <span style="background:#b81414;color:#fff;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px">
                {{ __('messages.samagri_studio.badge') }}
            </span>
            <span style="font-size:13px;color:#8f7d6e">100% Authentic Gujarati Vedic Artwork</span>
        </div>
        <h1>{{ __('messages.samagri_studio.title') }}</h1>
        <p>{{ __('messages.samagri_studio.subtitle') }}</p>
    </div>
    <div class="admin-page-actions">
        <button type="button" class="btn btn-primary" onclick="downloadPatrikaPNG()" id="btn-top-download">
            📥 {{ __('messages.samagri_studio.download_png') }}
        </button>
        <button type="button" class="btn btn-outline" onclick="shareOnWhatsApp()">
            💬 {{ __('messages.samagri_studio.share_whatsapp') }}
        </button>
    </div>
</div>

@if(session('status'))
    <div style="background:#f0faf2;border:1px solid #bce1c5;border-left:5px solid #2e6930;border-radius:8px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:10px">
        <span style="color:#2e6930;font-size:18px">✓</span>
        <span style="color:#285e33;font-size:14px;font-weight:600">{{ session('status') }}</span>
    </div>
@endif

<div class="studio-container">
    {{-- Left Column: Form Controls --}}
    <div class="studio-panel">
        <div class="studio-panel-header">
            <h3 style="margin:0;font-size:16px;color:#201a15;font-weight:700;display:flex;align-items:center;gap:8px">
                <span>⚙️</span>
                <span>Customize Patrika</span>
            </h3>
            <span style="font-size:12px;color:#8f7d6e">Real-time sync</span>
        </div>

        <div class="studio-panel-body">
            <form id="samagri-form" method="POST" action="{{ route('bhudev.samagri-studio.save') }}">
                @csrf
                <input type="hidden" name="card_id" id="input_card_id" value="{{ $activeCard?->id }}">
                <input type="hidden" name="pooja_slug" id="input_pooja_slug" value="{{ $activeCard?->pooja_slug ?? $initialPoojaSlug }}">

                {{-- Preset Chooser --}}
                <div style="margin-bottom:18px;background:#fdfaf6;border:1px solid #ecd8c2;border-radius:8px;padding:12px">
                    <label class="form-label-sm" style="color:#a01010">
                        ⚡ {{ __('messages.samagri_studio.choose_preset') }}
                    </label>
                    <select id="preset-selector" class="form-control-vedic" onchange="onPresetChange(this.value)">
                        <option value="ganpati" {{ ($activeCard?->pooja_slug ?? $initialPoojaSlug) === 'ganpati' ? 'selected' : '' }}>
                            🐘 ગણપતિ પૂજા માટે ની યાદી (Ganpati Pooja)
                        </option>
                        <option value="satyanarayan" {{ ($activeCard?->pooja_slug ?? $initialPoojaSlug) === 'satyanarayan' ? 'selected' : '' }}>
                            📜 સત્યનારાયણ કથા નો પૂજાપો (Satyanarayan Katha)
                        </option>
                        <option value="randal-mataji" {{ ($activeCard?->pooja_slug ?? $initialPoojaSlug) === 'randal-mataji' ? 'selected' : '' }}>
                            🪔 રાંદલ માતાજી નો પૂજાપો (Randal Mataji Pooja)
                        </option>
                        <option value="dharmraja" {{ ($activeCard?->pooja_slug ?? $initialPoojaSlug) === 'dharmraja' ? 'selected' : '' }}>
                            ☀️ ધર્મરાજા ની ઉજવણા ની યાદી (Dharmraja Udyapan)
                        </option>
                        <option value="custom" {{ ($activeCard?->pooja_slug ?? $initialPoojaSlug) === 'custom' ? 'selected' : '' }}>
                            ✍️ {{ __('messages.samagri_studio.custom_blank') }}
                        </option>
                    </select>
                </div>

                {{-- Header & Firm Title --}}
                <div style="margin-bottom:14px">
                    <label class="form-label-sm">{{ __('messages.samagri_studio.mantra_line') }}</label>
                    <input type="text" name="mantra_line" id="input_mantra_line" class="form-control-vedic"
                           value="{{ $activeCard?->mantra_line ?? $defaultMantra }}" oninput="updateLivePreview()">
                </div>

                <div style="margin-bottom:14px">
                    <label class="form-label-sm">{{ __('messages.samagri_studio.firm_title') }}</label>
                    <input type="text" name="firm_title" id="input_firm_title" class="form-control-vedic"
                           value="{{ $activeCard?->firm_title ?? $defaultFirm }}" oninput="updateLivePreview()">
                </div>

                <div style="margin-bottom:14px">
                    <label class="form-label-sm">{{ __('messages.samagri_studio.pooja_title') }}</label>
                    <input type="text" name="pooja_title" id="input_pooja_title" class="form-control-vedic"
                           value="{{ $activeCard?->pooja_title ?? ($initialPreset['title_gu'] ?? 'ગણપતિ પૂજા માટે ની યાદી') }}" oninput="updateLivePreview()">
                </div>

                {{-- Priests Contact Details --}}
                <div style="background:#faf7f2;border:1px solid #ebd9c7;border-radius:8px;padding:12px;margin-bottom:16px">
                    <div style="font-size:11px;font-weight:700;color:#8a4d19;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px">
                        👥 Priest Names & Contacts
                    </div>
                    <div class="form-group-row" style="margin-bottom:10px">
                        <div>
                            <label class="form-label-sm">{{ __('messages.samagri_studio.left_priest_name') }}</label>
                            <input type="text" name="left_priest_name" id="input_left_priest_name" class="form-control-vedic"
                                   value="{{ $activeCard?->left_priest_name ?? $defaultLeftName }}" oninput="updateLivePreview()">
                        </div>
                        <div>
                            <label class="form-label-sm">{{ __('messages.samagri_studio.left_priest_phone') }}</label>
                            <input type="text" name="left_priest_phone" id="input_left_priest_phone" class="form-control-vedic"
                                   value="{{ $activeCard?->left_priest_phone ?? $defaultLeftPhone }}" oninput="updateLivePreview()">
                        </div>
                    </div>
                    <div class="form-group-row">
                        <div>
                            <label class="form-label-sm">{{ __('messages.samagri_studio.right_priest_name') }}</label>
                            <input type="text" name="right_priest_name" id="input_right_priest_name" class="form-control-vedic"
                                   value="{{ $activeCard?->right_priest_name ?? $defaultRightName }}" oninput="updateLivePreview()">
                        </div>
                        <div>
                            <label class="form-label-sm">{{ __('messages.samagri_studio.right_priest_phone') }}</label>
                            <input type="text" name="right_priest_phone" id="input_right_priest_phone" class="form-control-vedic"
                                   value="{{ $activeCard?->right_priest_phone ?? $defaultRightPhone }}" oninput="updateLivePreview()">
                        </div>
                    </div>
                </div>

                {{-- Column 1 & Column 2 Items --}}
                <div style="margin-bottom:16px">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                        <label class="form-label-sm" style="margin:0">{{ __('messages.samagri_studio.column1_title') }}</label>
                        <span id="col1-count" style="font-size:11px;color:#8f7d6e;font-weight:600">0 items</span>
                    </div>
                    <textarea name="column1_items" id="input_column1_items" class="form-control-vedic textarea-vedic"
                              placeholder="{{ __('messages.samagri_studio.items_placeholder') }}" oninput="updateLivePreview()">{{ $activeCard?->column1_items ?? ($initialPreset['column1'] ?? '') }}</textarea>
                </div>

                <div style="margin-bottom:16px">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                        <label class="form-label-sm" style="margin:0">{{ __('messages.samagri_studio.column2_title') }}</label>
                        <span id="col2-count" style="font-size:11px;color:#8f7d6e;font-weight:600">0 items</span>
                    </div>
                    <textarea name="column2_items" id="input_column2_items" class="form-control-vedic textarea-vedic"
                              placeholder="{{ __('messages.samagri_studio.items_placeholder') }}" oninput="updateLivePreview()">{{ $activeCard?->column2_items ?? ($initialPreset['column2'] ?? '') }}</textarea>
                </div>

                {{-- Yajman Name --}}
                <div style="margin-bottom:16px">
                    <label class="form-label-sm">{{ __('messages.samagri_studio.yajman_name') }}</label>
                    <input type="text" name="yajman_name" id="input_yajman_name" class="form-control-vedic"
                           value="{{ $activeCard?->yajman_name ?? '' }}" placeholder="શ્રી ...................................." oninput="updateLivePreview()">
                </div>

                {{-- Action Buttons in Form --}}
                <div style="display:flex;gap:10px;margin-top:20px">
                    <button type="submit" class="btn-action-vedic primary" style="flex:1;justify-content:center;padding:11px">
                        💾 {{ $activeCard ? __('messages.samagri_studio.update_card') : __('messages.samagri_studio.save_card') }}
                    </button>
                    @if($activeCard)
                        <a href="{{ route('bhudev.samagri-studio') }}" class="btn-action-vedic secondary" style="padding:11px">
                            + New
                        </a>
                    @endif
                </div>
            </form>

            {{-- Saved Cards Section --}}
            <div style="margin-top:28px;padding-top:20px;border-top:1px solid #e7ded5">
                <h4 style="margin:0 0 12px;font-size:14px;color:#4a3828;font-weight:700;display:flex;align-items:center;justify-content:space-between">
                    <span>🗂️ {{ __('messages.samagri_studio.saved_cards') }} ({{ $savedCards->count() }})</span>
                </h4>
                @if($savedCards->isEmpty())
                    <p style="margin:0;font-size:12.5px;color:#8f7d6e;font-style:italic">
                        {{ __('messages.samagri_studio.no_saved_cards') }}
                    </p>
                @else
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @foreach($savedCards as $saved)
                            <div style="display:flex;align-items:center;justify-content:space-between;background:#fdfaf6;border:1px solid #ebd9c7;border-radius:6px;padding:8px 12px">
                                <div>
                                    <strong style="font-size:13px;color:#201a15;display:block">{{ $saved->pooja_title }}</strong>
                                    <small style="color:#8f7d6e;font-size:11px">
                                        {{ $saved->created_at->format('d M Y') }} • {{ $saved->firm_title ?: 'Patrika' }}
                                    </small>
                                </div>
                                <div style="display:flex;gap:6px">
                                    <a href="{{ route('bhudev.samagri-studio', ['card_id' => $saved->id]) }}" class="btn-action-vedic secondary" style="font-size:11px;padding:4px 8px">
                                        {{ __('messages.samagri_studio.load_card') }}
                                    </a>
                                    <form method="POST" action="{{ route('bhudev.samagri-studio.delete', $saved->id) }}" onsubmit="return confirm('Are you sure you want to delete this saved patrika?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-vedic secondary" style="font-size:11px;padding:4px 8px;color:#b81414;border-color:#f0c5c5">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column: Live A4 Patrika Canvas --}}
    <div class="preview-canvas-wrapper">
        <div class="preview-toolbar">
            <div style="display:flex;align-items:center;gap:8px">
                <span style="font-size:13px;font-weight:700;color:#4a3828">
                    🔍 {{ __('messages.samagri_studio.live_preview') }}
                </span>
                <div style="display:inline-flex;border:1px solid #d5c4b2;border-radius:5px;overflow:hidden;background:#fff">
                    <button type="button" onclick="setFontSize('compact')" id="btn-size-compact" style="border:none;background:transparent;padding:4px 8px;font-size:11px;cursor:pointer">Compact</button>
                    <button type="button" onclick="setFontSize('normal')" id="btn-size-normal" style="border:none;background:#b81414;color:#fff;padding:4px 8px;font-size:11px;cursor:pointer">Normal</button>
                    <button type="button" onclick="setFontSize('large')" id="btn-size-large" style="border:none;background:transparent;padding:4px 8px;font-size:11px;cursor:pointer">Large</button>
                </div>
            </div>
            <div style="display:flex;gap:8px">
                <button type="button" class="btn-action-vedic secondary" onclick="window.print()" title="Print A4">
                    🖨️ {{ __('messages.samagri_studio.print_card') }}
                </button>
                <button type="button" class="btn-action-vedic whatsapp" onclick="shareOnWhatsApp()">
                    💬 WhatsApp
                </button>
                <button type="button" class="btn-action-vedic primary" onclick="downloadPatrikaPNG()">
                    📥 HD PNG
                </button>
            </div>
        </div>

        {{-- The Exact Figma A4 Patrika Card Stage --}}
        <div id="patrika-card-stage" class="patrika-card-stage">
            {{-- Top Left Priest Info --}}
            <div class="card-priest-left">
                <div id="preview-left-priest" class="card-priest-name">{{ $activeCard?->left_priest_name ?? $defaultLeftName }}</div>
                <div id="preview-left-phone" class="card-priest-phone">
                    @if($activeCard?->left_priest_phone || $defaultLeftPhone)
                        મો. {{ $activeCard?->left_priest_phone ?? $defaultLeftPhone }}
                    @endif
                </div>
            </div>

            {{-- Top Right Priest Info --}}
            <div class="card-priest-right">
                <div id="preview-right-priest" class="card-priest-name">{{ $activeCard?->right_priest_name ?? $defaultRightName }}</div>
                <div id="preview-right-phone" class="card-priest-phone">
                    @if($activeCard?->right_priest_phone || $defaultRightPhone)
                        મો. {{ $activeCard?->right_priest_phone ?? $defaultRightPhone }}
                    @endif
                </div>
            </div>

            {{-- Top Center Mantra & Firm --}}
            <div id="preview-mantra" class="card-top-mantra">{{ $activeCard?->mantra_line ?? $defaultMantra }}</div>
            <div id="preview-firm" class="card-top-firm">{{ $activeCard?->firm_title ?? $defaultFirm }}</div>

            {{-- Main Title Banner --}}
            <div class="card-title-banner">
                <h2 id="preview-title" class="card-title-text">
                    {{ $activeCard?->pooja_title ?? ($initialPreset['title_gu'] ?? 'ગણપતિ પૂજા માટે ની યાદી') }}
                </h2>
            </div>

            {{-- Columns Grid --}}
            <div class="card-columns-container">
                <div class="card-column">
                    <ul id="preview-col1" class="samagri-item-list">
                        {{-- Rendered via JS updateLivePreview() --}}
                    </ul>
                </div>
                <div class="card-column">
                    <ul id="preview-col2" class="samagri-item-list">
                        {{-- Rendered via JS updateLivePreview() --}}
                    </ul>
                </div>
            </div>

            {{-- Bottom Yajman Area --}}
            <div id="preview-yajman" class="card-bottom-yajman">
                {{-- Rendered via JS updateLivePreview() --}}
            </div>
        </div>

        <p style="margin:16px 0 0;font-size:12px;color:#7a6857;text-align:center">
            {{ __('messages.samagri_studio.preview_hint') }}
        </p>
    </div>
</div>

<script>
    // Presets database loaded from backend
    const POOJA_PRESETS = @json($presets);
    let currentFontSize = 'normal';

    function setFontSize(size) {
        currentFontSize = size;
        const list1 = document.getElementById('preview-col1');
        const list2 = document.getElementById('preview-col2');
        list1.classList.remove('compact', 'large');
        list2.classList.remove('compact', 'large');

        document.getElementById('btn-size-compact').style.background = 'transparent';
        document.getElementById('btn-size-compact').style.color = '#4a3828';
        document.getElementById('btn-size-normal').style.background = 'transparent';
        document.getElementById('btn-size-normal').style.color = '#4a3828';
        document.getElementById('btn-size-large').style.background = 'transparent';
        document.getElementById('btn-size-large').style.color = '#4a3828';

        if (size === 'compact') {
            list1.classList.add('compact');
            list2.classList.add('compact');
            document.getElementById('btn-size-compact').style.background = '#b81414';
            document.getElementById('btn-size-compact').style.color = '#fff';
        } else if (size === 'large') {
            list1.classList.add('large');
            list2.classList.add('large');
            document.getElementById('btn-size-large').style.background = '#b81414';
            document.getElementById('btn-size-large').style.color = '#fff';
        } else {
            document.getElementById('btn-size-normal').style.background = '#b81414';
            document.getElementById('btn-size-normal').style.color = '#fff';
        }
    }

    function onPresetChange(slug) {
        document.getElementById('input_pooja_slug').value = slug;
        if (slug === 'custom') {
            document.getElementById('input_pooja_title').value = 'શુભ પૂજા સામગ્રી યાદી';
            document.getElementById('input_column1_items').value = '';
            document.getElementById('input_column2_items').value = '';
        } else if (POOJA_PRESETS[slug]) {
            const preset = POOJA_PRESETS[slug];
            document.getElementById('input_pooja_title').value = preset.title_gu || preset.title_en || '';
            document.getElementById('input_column1_items').value = preset.column1 || '';
            document.getElementById('input_column2_items').value = preset.column2 || '';
        }
        updateLivePreview();
    }

    function updateLivePreview() {
        // Mantra & Firm
        const mantra = document.getElementById('input_mantra_line').value || '|| શ્રી ગણેશાય નમઃ ||';
        const firm = document.getElementById('input_firm_title').value || '';
        document.getElementById('preview-mantra').textContent = mantra;
        document.getElementById('preview-firm').textContent = firm;

        // Priests
        const leftName = document.getElementById('input_left_priest_name').value;
        const leftPhone = document.getElementById('input_left_priest_phone').value;
        const rightName = document.getElementById('input_right_priest_name').value;
        const rightPhone = document.getElementById('input_right_priest_phone').value;

        document.getElementById('preview-left-priest').textContent = leftName;
        document.getElementById('preview-left-phone').textContent = leftPhone ? (leftPhone.startsWith('મો.') ? leftPhone : 'મો. ' + leftPhone) : '';

        document.getElementById('preview-right-priest').textContent = rightName;
        document.getElementById('preview-right-phone').textContent = rightPhone ? (rightPhone.startsWith('મો.') ? rightPhone : 'મો. ' + rightPhone) : '';

        // Title
        const title = document.getElementById('input_pooja_title').value || 'પૂજા સામગ્રી યાદી';
        document.getElementById('preview-title').textContent = title;

        // Column 1
        const col1Text = document.getElementById('input_column1_items').value;
        const col1Lines = col1Text.split('\n').map(l => l.trim()).filter(l => l.length > 0);
        document.getElementById('col1-count').textContent = col1Lines.length + ' items';
        const col1El = document.getElementById('preview-col1');
        col1El.innerHTML = '';
        col1Lines.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            col1El.appendChild(li);
        });

        // Column 2
        const col2Text = document.getElementById('input_column2_items').value;
        const col2Lines = col2Text.split('\n').map(l => l.trim()).filter(l => l.length > 0);
        document.getElementById('col2-count').textContent = col2Lines.length + ' items';
        const col2El = document.getElementById('preview-col2');
        col2El.innerHTML = '';
        col2Lines.forEach(item => {
            const li = document.createElement('li');
            li.textContent = item;
            col2El.appendChild(li);
        });

        // Yajman
        const yajman = document.getElementById('input_yajman_name').value.trim();
        const yajmanEl = document.getElementById('preview-yajman');
        if (yajman) {
            yajmanEl.textContent = 'શ્રી ' + yajman.replace(/^શ્રી\s*/, '');
        } else {
            yajmanEl.textContent = 'શ્રી ............................................................................................';
        }

        // Auto-scale font size if items are numerous
        const maxLines = Math.max(col1Lines.length, col2Lines.length);
        if (maxLines > 17 && currentFontSize === 'normal') {
            setFontSize('compact');
        }
    }

    // High Definition PNG Export using html2canvas
    function downloadPatrikaPNG() {
        const stage = document.getElementById('patrika-card-stage');
        const btn = document.getElementById('btn-top-download');
        const originalText = btn.innerHTML;
        btn.innerHTML = '⏳ {{ __("messages.samagri_studio.generating_image") }}';
        btn.disabled = true;

        // Use scale 2 for crystal-clear 1190 x 1684 px high resolution export
        html2canvas(stage, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#fcf9f2',
            logging: false
        }).then(canvas => {
            const link = document.createElement('a');
            const slug = document.getElementById('input_pooja_slug').value || 'pooja-samagri';
            link.download = `${slug}-patrika.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch(err => {
            console.error('Error generating canvas:', err);
            alert('Failed to generate image. Please try again.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Share formatted message via WhatsApp
    function shareOnWhatsApp() {
        const firm = document.getElementById('input_firm_title').value;
        const title = document.getElementById('input_pooja_title').value;
        const rightPriest = document.getElementById('input_right_priest_name').value;
        const rightPhone = document.getElementById('input_right_priest_phone').value;
        const yajman = document.getElementById('input_yajman_name').value;

        const col1Text = document.getElementById('input_column1_items').value;
        const col2Text = document.getElementById('input_column2_items').value;

        const items1 = col1Text.split('\n').map(l => l.trim()).filter(l => l.length > 0);
        const items2 = col2Text.split('\n').map(l => l.trim()).filter(l => l.length > 0);

        let msg = `🕉️ *${firm}*\n`;
        msg += `📋 *${title}*\n`;
        if (yajman) {
            msg += `👤 *યજમાન:* શ્રી ${yajman.replace(/^શ્રી\s*/, '')}\n`;
        }
        msg += `━━━━━━━━━━━━━━━━━━━━\n\n`;

        msg += `*પૂજા સામગ્રી યાદી:*\n`;
        items1.forEach(i => { msg += `• ${i}\n`; });
        items2.forEach(i => { msg += `• ${i}\n`; });

        msg += `\n━━━━━━━━━━━━━━━━━━━━\n`;
        msg += `🙏 *પૂજ્ય શાસ્ત્રીજી:* ${rightPriest} ${rightPhone ? '(' + rightPhone + ')' : ''}\n`;
        msg += `🌐 *DigitalBhudev Vedic Patrika Studio*\n`;

        const waUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`;
        window.open(waUrl, '_blank');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function () {
        updateLivePreview();
    });
</script>
@endsection
