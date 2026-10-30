@extends('layouts.app')

@section('title', $card->pooja_title . ' | ' . ($card->firm_title ?: config('app.name')))

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@400;500;600;700&family=Noto+Sans+Gujarati:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="{{ asset('assets/js/html2canvas.min.js') }}"></script>
<style>
    .patrika-show-wrapper {
        min-height: 85vh;
        background: var(--bg-page-aura);
        padding: 40px 16px 80px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .patrika-action-bar {
        width: 100%;
        max-width: 595px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        background: var(--card-bg);
        padding: 16px 22px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid var(--primary-border);
    }

    .btn-patrika-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 20px;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: var(--radius-full);
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-patrika-action.primary {
        background: var(--primary-gradient);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(219, 39, 119, 0.25);
    }
    .btn-patrika-action.primary:hover {
        opacity: 0.95;
        transform: translateY(-1px);
    }

    .btn-patrika-action.whatsapp {
        background: #25D366;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(37, 211, 102, 0.25);
    }
    .btn-patrika-action.whatsapp:hover {
        background: #1ebc57;
        transform: translateY(-1px);
    }

    .btn-patrika-action.secondary {
        background: var(--primary-soft);
        color: var(--heading);
        border: 1px solid var(--primary-border);
    }
    .btn-patrika-action.secondary:hover {
        background: #fce7f3;
    }

    /* Patrika A4 Stage */
    .patrika-card-stage {
        position: relative;
        width: 595px;
        height: 842px;
        background-color: #fcf9f2;
        background-image: url("{{ asset('assets/images/samagri/clean_card_template.png') }}");
        background-size: 100% 100%;
        background-repeat: no-repeat;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22);
        border-radius: 4px;
        overflow: hidden;
        user-select: text;
        font-family: 'Hind Vadodara', 'Noto Sans Gujarati', sans-serif;
        color: #a01010;
        box-sizing: border-box;
    }

    @media (max-width: 680px) {
        .patrika-show-wrapper {
            padding: 16px 12px 60px;
            overflow-x: hidden;
        }
        .patrika-action-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 12px 14px;
        }
        .patrika-action-bar > div:last-child {
            width: 100%;
            display: flex;
            gap: 8px;
        }
        .btn-patrika-action {
            flex: 1;
            justify-content: center;
            padding: 9px 8px;
            font-size: 12px;
        }
        .patrika-card-stage {
            transform: scale(calc((100vw - 32px) / 595));
            transform-origin: top center;
            margin-bottom: calc(-842px * (1 - ((100vw - 32px) / 595)));
        }
    }

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

    .samagri-item-list {
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 13.5px;
        line-height: 1.62;
        font-weight: 600;
        color: #a01010;
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

    .card-bottom-yajman {
        position: absolute;
        bottom: 34px;
        left: 55px;
        right: 55px;
        text-align: left;
        font-size: 14.5px;
        font-weight: 700;
        color: #a01010;
    }

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

@section('content')
<div class="patrika-show-wrapper">
    <div class="patrika-action-bar">
        <div>
            <strong style="font-size:14px;color:#201a15;display:block">{{ $card->pooja_title }}</strong>
            <small style="color:#8f7d6e">{{ $card->firm_title ?: 'Pooja Samagri Patrika' }}</small>
        </div>
        <div style="display:flex;gap:8px">
            <button type="button" class="btn-patrika-action secondary" onclick="window.print()">
                🖨️ {{ __('messages.samagri_studio.print_card') }}
            </button>
            <button type="button" class="btn-patrika-action whatsapp" onclick="shareWhatsApp()">
                💬 WhatsApp
            </button>
            <button type="button" class="btn-patrika-action primary" onclick="downloadPNG()" id="btn-download">
                📥 HD PNG
            </button>
        </div>
    </div>

    {{-- Exact Figma Card --}}
    <div id="patrika-card-stage" class="patrika-card-stage">
        {{-- Top Left Priest Info --}}
        <div class="card-priest-left">
            <div class="card-priest-name">{{ $card->left_priest_name }}</div>
            @if($card->left_priest_phone)
                <div class="card-priest-phone">
                    {{ str_starts_with($card->left_priest_phone, 'મો.') ? $card->left_priest_phone : 'મો. ' . $card->left_priest_phone }}
                </div>
            @endif
        </div>

        {{-- Top Right Priest Info --}}
        <div class="card-priest-right">
            <div class="card-priest-name">{{ $card->right_priest_name }}</div>
            @if($card->right_priest_phone)
                <div class="card-priest-phone">
                    {{ str_starts_with($card->right_priest_phone, 'મો.') ? $card->right_priest_phone : 'મો. ' . $card->right_priest_phone }}
                </div>
            @endif
        </div>

        {{-- Top Center Mantra & Firm --}}
        <div class="card-top-mantra">{{ $card->mantra_line }}</div>
        <div class="card-top-firm">{{ $card->firm_title }}</div>

        {{-- Main Title Banner --}}
        <div class="card-title-banner">
            <h1 class="card-title-text">{{ $card->pooja_title }}</h1>
        </div>

        {{-- Columns Grid --}}
        <div class="card-columns-container">
            <div class="card-column">
                <ul class="samagri-item-list">
                    @foreach(array_filter(array_map('trim', explode("\n", (string) $card->column1_items))) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="card-column">
                <ul class="samagri-item-list">
                    @foreach(array_filter(array_map('trim', explode("\n", (string) $card->column2_items))) as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Bottom Yajman Area --}}
        <div class="card-bottom-yajman">
            @if($card->yajman_name)
                શ્રી {{ preg_replace('/^શ્રી\s*/u', '', $card->yajman_name) }}
            @else
                શ્રી ............................................................................................
            @endif
        </div>
    </div>
</div>

<script>
    function downloadPNG() {
        const stage = document.getElementById('patrika-card-stage');
        const btn = document.getElementById('btn-download');
        const orig = btn.innerHTML;
        btn.innerHTML = '⏳ Generating...';
        btn.disabled = true;

        html2canvas(stage, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#fcf9f2',
            logging: false
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = `{{ $card->pooja_slug }}-patrika.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            btn.innerHTML = orig;
            btn.disabled = false;
        }).catch(() => {
            alert('Failed to generate image.');
            btn.innerHTML = orig;
            btn.disabled = false;
        });
    }

    function shareWhatsApp() {
        let msg = `🕉️ *{{ $card->firm_title }}*\n`;
        msg += `📋 *{{ $card->pooja_title }}*\n`;
        @if($card->yajman_name)
            msg += `👤 *યજમાન:* શ્રી {{ preg_replace('/^શ્રી\s*/u', '', $card->yajman_name) }}\n`;
        @endif
        msg += `━━━━━━━━━━━━━━━━━━━━\n\n`;
        msg += `*પૂજા સામગ્રી યાદી:*\n`;
        @foreach(array_filter(array_map('trim', explode("\n", (string) $card->column1_items))) as $item)
            msg += `• {{ $item }}\n`;
        @endforeach
        @foreach(array_filter(array_map('trim', explode("\n", (string) $card->column2_items))) as $item)
            msg += `• {{ $item }}\n`;
        @endforeach
        msg += `\n━━━━━━━━━━━━━━━━━━━━\n`;
        msg += `🙏 *પૂજ્ય શાસ્ત્રીજી:* {{ $card->right_priest_name }} ({{ $card->right_priest_phone }})\n`;
        msg += `🔗 *ઓનલાઇન પત્રિકા જુઓ:* ${window.location.href}\n`;

        window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(msg)}`, '_blank');
    }
</script>
@endsection
