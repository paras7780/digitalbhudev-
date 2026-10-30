@extends('layouts.app')

@section('title', 'Find a Verified Bhudev | DigitalBhudev')
@section('meta_description', 'Discover verified Brahmin Bhudevs across Gujarat by city, ceremony, experience, and languages for home pujas and sacred rituals.')

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">VERIFIED VEDIC DIRECTORY</span>
            <h1>Find a Trusted Bhudev</h1>
            <p>Connect directly with experienced Brahmin priests in your city for authentic Vedic pujas, havans, and ceremonies.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            {{-- Filter & Search Panel --}}
            <div class="search-panel-box" style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);margin-bottom:32px">
                <form method="GET" action="{{ route('bhudevs.index') }}" class="bhudevs-search-grid">
                    <div>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or expertise..." aria-label="Search Bhudevs" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <div>
                        <input type="text" name="city" value="{{ request('city') }}" placeholder="City (e.g. Ahmedabad)" aria-label="Filter by city" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px">
                    </div>

                    <div>
                        <select name="service" aria-label="Filter by service" style="width:100%;padding:10px 14px;border:1px solid var(--line);border-radius:var(--radius-sm);font-size:14px;background:#fff">
                            <option value="">All Pooja Services</option>
                            @foreach($services as $srv)
                                <option value="{{ $srv->slug }}" @selected(request('service') === $srv->slug)>
                                    {{ $srv->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button class="btn btn-primary" type="submit" style="padding:10px 22px;font-size:14px;white-space:nowrap">
                            Search Bhudevs
                        </button>
                    </div>

                    @if(request()->hasAny(['q', 'city', 'service', 'date']))
                        <div>
                            <a class="btn btn-light" href="{{ route('bhudevs.index') }}" style="padding:10px 16px;font-size:14px;white-space:nowrap">
                                Clear Filters
                            </a>
                        </div>
                    @endif
                </form>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:14px;padding-top:12px;border-top:1px solid var(--line);font-size:13px;color:var(--muted);flex-wrap:wrap;gap:8px">
                    <span>
                        Showing <strong>{{ $profiles->total() }}</strong> verified Bhudev{{ $profiles->total() === 1 ? '' : 's' }} in Gujarat
                    </span>
                    <span style="color:var(--primary);font-weight:600">
                        🛡️ 100% Background &amp; Lineage Verified
                    </span>
                </div>
            </div>

            {{-- Profile Cards Grid --}}
            @if($profiles->count())
                <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:24px;margin-bottom:40px">
                    @foreach($profiles as $profile)
                        <article style="background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius-lg);padding:24px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between;transition:transform 0.2s,box-shadow 0.2s">
                            <div>
                                {{-- Card Header: Avatar & Badges --}}
                                <div style="display:flex;gap:16px;align-items:flex-start;margin-bottom:16px">
                                    @if($profile->photo_path)
                                        <img src="{{ asset('storage/' . $profile->photo_path) }}" alt="{{ $profile->display_name }}" loading="lazy" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid var(--primary-border);flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.08)">
                                    @else
                                        <div style="width:72px;height:72px;border-radius:50%;background:var(--primary-soft);display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--primary);font-weight:700;border:2px solid var(--primary-border);flex-shrink:0">
                                            {{ strtoupper(substr($profile->display_name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div style="flex:1">
                                        <span style="background:var(--green-soft);color:var(--green);border:1px solid var(--green-border);border-radius:12px;padding:2px 8px;font-size:10px;font-weight:700;display:inline-flex;align-items:center;gap:3px;margin-bottom:4px">
                                            ✓ Verified
                                        </span>
                                        <h3 style="font-family:'Playfair Display',serif;font-size:19px;margin:2px 0 4px;color:var(--heading)">
                                            <a href="{{ route('bhudevs.show', $profile->slug) }}" style="color:inherit;text-decoration:none">
                                                {{ $profile->display_name }}
                                            </a>
                                        </h3>
                                        <span style="color:var(--muted);font-size:12px;display:block">
                                            📍 {{ collect([$profile->city, $profile->state])->filter()->join(', ') ?: 'Gujarat' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Experience & Availability Pills --}}
                                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px">
                                    @if($profile->experience_years)
                                        <span style="background:var(--primary-soft);color:var(--primary);border:1px solid var(--primary-border);border-radius:var(--radius-full);padding:3px 10px;font-size:11px;font-weight:600">
                                            🕉️ {{ $profile->experience_years }}+ Yrs Exp
                                        </span>
                                    @endif
                                    @if($profile->availability)
                                        <span style="background:var(--green-soft);color:var(--green);border:1px solid var(--green-border);border-radius:var(--radius-full);padding:3px 10px;font-size:11px;font-weight:600">
                                            ⏱️ {{ $profile->availability }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Services Conducted --}}
                                @if($profile->services->isNotEmpty())
                                    <div style="margin-bottom:16px">
                                        <div style="display:flex;gap:5px;flex-wrap:wrap">
                                            @foreach($profile->services->take(3) as $srv)
                                                <span style="background:var(--primary-soft);border:1px solid var(--primary-border);padding:3px 10px;border-radius:var(--radius-full);font-size:11px;color:var(--heading)">
                                                    {{ $srv->name }}
                                                </span>
                                            @endforeach
                                            @if($profile->services->count() > 3)
                                                <span style="font-size:11px;color:var(--muted);align-self:center">
                                                    +{{ $profile->services->count() - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div style="display:flex;gap:10px;padding-top:14px;border-top:1px solid var(--line);align-items:center">
                                <a href="{{ route('bhudevs.show', $profile->slug) }}" class="btn btn-primary" style="flex:1;text-align:center;padding:9px;font-size:13px">
                                    View Profile &rarr;
                                </a>

                                @php
                                    $targetWa = ($profile->show_mobile && $profile->user->phone)
                                        ? preg_replace('/[^0-9]/', '', $profile->user->phone)
                                        : '919876543210';
                                    if (strlen($targetWa) === 10) $targetWa = '91' . $targetWa;
                                    $waMsg = "Namaste Panditji ({$profile->display_name}), I saw your profile on DigitalBhudev and would like to inquire about booking a puja in {$profile->city}.";
                                @endphp
                                <a href="https://api.whatsapp.com/send?phone={{ $targetWa }}&text={{ urlencode($waMsg) }}" target="_blank" class="btn btn-light" style="background:#25d366;color:#fff;border:none;padding:9px 12px;font-size:13px;border-radius:var(--radius-full)" title="Chat on WhatsApp">
                                    💬
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div style="margin-bottom:30px">
                    {{ $profiles->links() }}
                </div>
            @else
                <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:48px 24px;text-align:center;box-shadow:var(--shadow);max-width:600px;margin:0 auto">
                    <div style="font-size:42px;margin-bottom:12px">🔍</div>
                    <h3 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 8px">
                        No Bhudevs Matching Your Filter
                    </h3>
                    <p style="color:var(--muted);font-size:14px;line-height:1.6;margin:0 auto 20px;max-width:440px">
                        We could not find verified Bhudevs matching your specific search criteria. Try clearing the filter or searching for a broader city name.
                    </p>
                    <a href="{{ route('bhudevs.index') }}" class="btn btn-primary">
                        View All Bhudevs &rarr;
                    </a>
                </div>
            @endif
        </div>
    </section>
</main>
@endsection
