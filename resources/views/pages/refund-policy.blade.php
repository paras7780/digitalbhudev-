@extends('layouts.app')

@section('title', 'Cancellation & Refund Policy | DigitalBhudev')
@section('meta_description', 'Cancellation and refund guidelines for Vedic ceremonies, priest bookings, Pooja Samagri kits, and Pandit SaaS domains on DigitalBhudev.')
@section('canonical', route('refund.policy'))

@push('schema')
@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Cancellation & Refund Policy', 'item' => route('refund.policy')],
        ],
    ];
    $pageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Cancellation & Refund Policy | DigitalBhudev',
        'description' => 'Transparent cancellation, rescheduling, and refund policies for Vedic puja bookings, Dakshina, and Pandit domain services.',
        'url' => route('refund.policy'),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'DigitalBhudev',
            'url' => url('/'),
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<main>
    <section class="page-hero">
        <div class="container">
            <span class="eyebrow">TRANSPARENCY & FAIRNESS</span>
            <h1>Cancellation &amp; Refund Policy</h1>
            <p>Our commitment to respectful Vedic traditions, clear scheduling, and fair cancellation standards for both devotees and Brahmin priests.</p>
        </div>
    </section>

    <section class="content-section">
        <div class="container" style="max-width:920px;margin:0 auto">
            <div style="background:var(--card-bg);border:1px solid var(--primary-border);border-radius:var(--radius-lg);padding:38px;box-shadow:var(--shadow);display:flex;flex-direction:column;gap:28px">
                
                {{-- Quick Summary Highlight Box --}}
                <div style="background:linear-gradient(135deg, #fff5f9, #fdf2f8);border:1.5px solid #fbcfe8;border-radius:12px;padding:20px 24px;display:flex;align-items:flex-start;gap:14px">
                    <span style="font-size:24px;line-height:1">🕉️</span>
                    <div>
                        <strong style="font-size:15px;color:var(--heading);display:block;margin-bottom:4px">Zero-Middleman Dakshina Commitment</strong>
                        <p style="font-size:13.5px;color:#831843;line-height:1.6;margin:0">
                            DigitalBhudev never takes a commission cut from the voluntary ritual honorarium (Dakshina) offered directly by devotees to verified priests. Direct Dakshina is settled between devotees and priests with sacred trust and traditional Maryada.
                        </p>
                    </div>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">1. Devotee Ceremony Cancellation &amp; Rescheduling</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0 0 12px">
                        We understand that auspicious family occasions, planetary Muhurats, and travel schedules may occasionally need adjustments. We offer transparent and devotee-friendly cancellation and rescheduling terms:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:0 0 12px">
                        <li><strong>Cancellations 48+ Hours Prior to Muhurat:</strong> Devotees can cancel or reschedule any scheduled ceremony without any penalty or fee.</li>
                        <li><strong>Rescheduling Within 24–48 Hours:</strong> Devotees can request an alternative auspicious Muhurat with the same priest subject to mutual calendar availability.</li>
                        <li><strong>Same-Day / Last-Minute Cancellations:</strong> If a priest has already traveled to the ceremony venue or prepared perishable Samagri items (e.g. fresh flowers, mango leaves, durva grass), a modest conveyance/travel honorarium should be respectfully reimbursed to the priest.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">2. Priest Unavailability &amp; Replacement Assurance</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        In the rare event that a verified Bhudev priest is unable to conduct a scheduled ritual due to sudden illness, family bereavement (Sutak / Ashaucha rules per Shastras), or travel disruptions:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:8px 0 0">
                        <li>DigitalBhudev will immediately coordinate an alternative verified Brahmin priest of equivalent Vedic qualification and Veda branch to ensure your sacred ceremony is not disrupted.</li>
                        <li>If a replacement priest cannot be arranged or is not acceptable to the devotee family, any booking facilitation or advance token will be refunded in full immediately.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">3. Pooja Samagri Checklists &amp; Material Orders</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        DigitalBhudev provides digital Pooja Samagri checklists and customized Patrika cards 100% free of charge to all devotees. For any optional physical Samagri kits, brass utensils, or havan wood orders placed through verified partners, cancellations are accepted anytime prior to dispatch. Once dispatched via courier, non-perishable sealed items can be returned within 48 hours in original sacred condition.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">4. Pandit Personal Domain &amp; SaaS Subscriptions</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        For verified Bhudev priests subscribing to custom branded domain websites (e.g. <em>shastriji.in</em>) and digital portal hosting:
                    </p>
                    <ul style="color:var(--ink);font-size:14px;line-height:1.75;padding-left:22px;margin:8px 0 0">
                        <li><strong>Platform Hosting:</strong> New subscriptions come with a 7-day satisfaction window. If requested within 7 days of activation, platform subscription charges are eligible for a prorated refund.</li>
                        <li><strong>Custom Domain Registrations:</strong> Fees paid to third-party domain registrars (.in / .com / .org registry authorities) are non-refundable once the domain name has been registered and activated in your name.</li>
                    </ul>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">5. Refund Processing Timelines &amp; Methods</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        All eligible refunds are processed directly through the original method of payment (UPI, Net Banking, or Card) within <strong>5 to 7 working days</strong> from the date of approval. You will receive immediate WhatsApp and email confirmation with the transaction reference ID once processed.
                    </p>
                </div>

                <div>
                    <h2 style="font-family:'Playfair Display',serif;font-size:22px;color:var(--heading);margin:0 0 10px">6. Questions, Grievances &amp; Support</h2>
                    <p style="color:var(--ink);font-size:14px;line-height:1.75;margin:0">
                        If you have any questions regarding ceremony cancellations, need assistance with rescheduling, or wish to track an ongoing refund, our dedicated seva team is here to assist you:
                    </p>
                    <div style="margin-top:14px;display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                        <a href="{{ route('contact') }}" class="btn btn-primary btn-sm">
                            <span>💬 Contact Support &amp; Grievance Team</span>
                        </a>
                        <span style="font-size:13px;color:var(--muted)">or email us at: <strong style="color:var(--heading)">support@digitalbhudev.com</strong></span>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
@endsection
