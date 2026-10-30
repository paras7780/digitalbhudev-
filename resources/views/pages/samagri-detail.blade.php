@extends('layouts.app')

@section('title', $selected['name'] . ' Samagri Checklist | DigitalBhudev')
@section('meta_description', 'Complete Vedic samagri list for ' . $selected['name'] . '. Check items off on your mobile, print, or share on WhatsApp.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">POOJA SAMAGRI CHECKLIST</span>
            <h1>{{ $selected['name'] }}</h1>
            <p>{{ $selected['name_gu'] }} &bull; Traditional Vedic Checklist</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            {{-- Top Action Bar --}}
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:18px 24px;margin-bottom:32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;box-shadow:var(--shadow)">
                <div style="display:flex;align-items:center;gap:14px">
                    <span style="font-size:24px">🪔</span>
                    <div>
                        <strong style="color:var(--heading);font-size:15px;display:block">{{ $selected['name'] }}</strong>
                        <small style="color:var(--muted)">Estimated Duration: {{ $selected['duration'] }} &bull; Deity: {{ $selected['deity'] }}</small>
                    </div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    @php
                        $waText = "🕉️ *Pooja Samagri List: {$selected['name']}* ({$selected['name_gu']})\n"
                            . "Checklist for sacred items required for the ceremony:\n"
                            . route('samagri.show', $slug);
                        $patrikaCard = \App\Models\BhudevSamagriCard::where('pooja_slug', $slug)->first();
                    @endphp
                    @if($patrikaCard)
                        <a href="{{ route('samagri.card.show', $patrikaCard->id) }}" class="btn btn-primary" style="font-size:13px;display:inline-flex;align-items:center;gap:6px">
                            <span>📜</span> View &amp; Download Card Image
                        </a>
                    @endif
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($waText) }}" target="_blank" class="btn btn-light" style="background:#eaf7ee;color:#1e7e34;border-color:#bce5c7;font-size:13px;display:inline-flex;align-items:center;gap:6px;border-radius:var(--radius-full)">
                        <span>📲</span> Share on WhatsApp
                    </a>
                    <button type="button" onclick="window.print()" class="btn btn-light" style="font-size:13px;display:inline-flex;align-items:center;gap:6px;border-radius:var(--radius-full)">
                        <span>🖨️</span> Print Checklist
                    </button>
                    <a href="#book-bhudev" class="btn btn-primary" style="font-size:13px">
                        🕉️ Book a Bhudev for this Pooja
                    </a>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1.8fr 1fr;gap:32px;align-items:start">
                {{-- Left Column: Interactive Checklists --}}
                <div>
                    <div style="background:var(--blue-soft);border:1px solid var(--blue-border);border-radius:var(--radius-sm);padding:14px 18px;margin-bottom:24px;font-size:13px;color:#1e40af">
                        💡 <strong>Devotee Tip:</strong> Tap or click any item below to check it off as you shop or prepare your puja thali. Your progress is saved automatically on your phone!
                    </div>

                    @foreach($selected['sections'] as $sIndex => $sec)
                        <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;margin-bottom:24px;box-shadow:var(--shadow)">
                            <h2 style="font-family:'Playfair Display',serif;font-size:18px;color:var(--heading);margin:0 0 16px;padding-bottom:12px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between">
                                <span>{{ $sec['title'] }}</span>
                                <span style="font-size:12px;color:var(--muted);font-family:sans-serif;font-weight:normal">
                                    {{ count($sec['items']) }} items
                                </span>
                            </h2>

                            <div style="display:flex;flex-direction:column;gap:10px">
                                @foreach($sec['items'] as $iIndex => $item)
                                    @php $chkId = 'chk_' . $sIndex . '_' . $iIndex; @endphp
                                    <label for="{{ $chkId }}" style="display:flex;align-items:flex-start;gap:12px;padding:8px 12px;border-radius:var(--radius-sm);cursor:pointer;background:var(--primary-soft);transition:background 0.15s;font-size:14px;color:var(--ink)" class="samagri-item-row">
                                        <input type="checkbox" id="{{ $chkId }}" style="margin-top:4px;transform:scale(1.2);cursor:pointer" onchange="toggleItem(this)">
                                        <span class="item-text" style="line-height:1.4">{{ $item }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div style="margin-top:20px;text-align:center">
                        <a href="{{ route('samagri.index') }}" class="text-link" style="font-size:14px;color:var(--primary);font-weight:600">
                            &larr; View all Pooja Samagri Checklists
                        </a>
                    </div>
                </div>

                {{-- Right Column: Instant Booking Card & Other Lists --}}
                <div style="display:flex;flex-direction:column;gap:24px">
                    {{-- Instant Booking Form --}}
                    <div id="book-bhudev" style="background:var(--card-bg);border:1.5px solid var(--primary-border);border-radius:var(--radius-lg);padding:26px;box-shadow:var(--shadow)">
                        <div style="text-align:center;margin-bottom:18px">
                            <span style="font-size:30px">🙏</span>
                            <h3 style="font-family:'Playfair Display',serif;font-size:20px;margin:6px 0 4px;color:var(--heading)">
                                Book a Bhudev for this Ceremony
                            </h3>
                            <p style="color:var(--muted);font-size:12px;margin:0">
                                Connect with an authentic Brahmin pandit to perform your {{ $selected['name'] }}.
                            </p>
                        </div>

                        @if(session('status'))
                            <div style="background:var(--green-soft);border:1px solid var(--green-border);border-radius:var(--radius-sm);padding:10px;margin-bottom:16px;color:var(--green);font-size:12px;text-align:center">
                                ✓ {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('booking.store') }}">
                            @csrf
                            <input type="hidden" name="pooja_service" value="{{ $selected['name'] }}">

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Your Name <span style="color:var(--primary)">*</span></label>
                                <input type="text" name="name" required placeholder="e.g. Rajeshbhai Patel" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">WhatsApp / Mobile Number <span style="color:var(--primary)">*</span></label>
                                <input type="text" name="phone" required placeholder="e.g. 98250 12345" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">City / Town <span style="color:var(--primary)">*</span></label>
                                <input type="text" name="city" required placeholder="e.g. Ahmedabad, Surat, Vadodara" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:12px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Preferred Date</label>
                                <input type="date" name="preferred_date" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px">
                            </div>

                            <div style="margin-bottom:16px">
                                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:4px;color:var(--heading)">Additional Message / Special Request</label>
                                <textarea name="message" rows="2" placeholder="e.g. At our home, morning muhurat preferred" style="width:100%;padding:9px 12px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:13px"></textarea>
                            </div>

                            {{-- Honeypot --}}
                            <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

                            <button type="submit" class="btn btn-primary" style="width:100%;padding:11px;font-size:14px;font-weight:600">
                                📩 Request Bhudev Booking &rarr;
                            </button>
                        </form>

                        <div style="margin-top:14px;text-align:center">
                            <a href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Namaste DigitalBhudev, I need a Bhudev for ' . $selected['name'] . '.') }}" target="_blank" style="font-size:12px;color:#1e7e34;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px">
                                <span>💬</span> Or chat on WhatsApp for urgent booking
                            </a>
                        </div>
                    </div>

                    {{-- Other Checklists --}}
                    <div style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:20px;box-shadow:var(--shadow)">
                        <h4 style="font-family:'Playfair Display',serif;font-size:16px;margin:0 0 12px;color:var(--heading)">
                            Other Samagri Checklists
                        </h4>
                        <div style="display:flex;flex-direction:column;gap:8px">
                            @foreach($all as $otherSlug => $otherItem)
                                @if($otherSlug !== $slug)
                                    <a href="{{ route('samagri.show', $otherSlug) }}" style="padding:10px 14px;border-radius:var(--radius-sm);text-decoration:none;color:var(--heading);font-size:13px;display:flex;justify-content:space-between;align-items:center;background:var(--primary-soft);border:1px solid var(--primary-border)">
                                        <span>{{ $otherItem['name'] }}</span>
                                        <span style="color:var(--primary);font-weight:700">&rarr;</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function toggleItem(checkbox) {
    const row = checkbox.closest('.samagri-item-row');
    const text = row.querySelector('.item-text');
    if (checkbox.checked) {
        text.style.textDecoration = 'line-through';
        text.style.opacity = '0.5';
        row.style.background = '#f2efe9';
    } else {
        text.style.textDecoration = 'none';
        text.style.opacity = '1';
        row.style.background = '#faf8f5';
    }
}
</script>
@endsection
