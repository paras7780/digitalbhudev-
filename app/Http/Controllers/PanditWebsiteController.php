<?php

namespace App\Http\Controllers;

use App\Models\BhudevDomain;
use App\Models\Inquiry;
use App\Services\InstagramIntegrationService;
use Illuminate\Http\Request;

class PanditWebsiteController extends Controller
{
    /**
     * Render the Pandit's personal white-labeled website.
     */
    public function show(Request $request, ?BhudevDomain $domain = null)
    {
        $domain = $domain ?? app('current_tenant_domain');
        abort_unless($domain && $domain->isActive(), 404, 'Pandit website is not active.');

        $pandit = $domain->profile->load(['services', 'publicImages', 'user']);

        $instagramService = app(InstagramIntegrationService::class);
        $instagramEligible = $instagramService->isEligibleForPublicDisplay($pandit, $domain);
        $instagramPosts = $instagramEligible ? $instagramService->getEligiblePosts($pandit, $domain) : collect();

        return view('pandit.home', compact('domain', 'pandit', 'instagramEligible', 'instagramPosts'));
    }

    /**
     * Handle direct inquiry on Pandit's personal website.
     * Saves inquiry to database and generates the direct WhatsApp click-to-chat URL.
     */
    public function handleInquiry(Request $request)
    {
        $domain = app('current_tenant_domain');
        abort_unless($domain && $domain->isActive(), 404);

        $pandit = $domain->profile;

        $validated = $request->validate([
            'name'           => 'required|string|max:120',
            'phone'          => 'required|string|max:30',
            'city'           => 'nullable|string|max:100',
            'pooja_service'  => 'nullable|string|max:150',
            'preferred_date' => 'nullable|date',
            'message'        => 'nullable|string|max:2000',
        ]);

        // Save inquiry in central database under this Pandit's profile
        $inquiry = Inquiry::create([
            'name'              => $validated['name'],
            'email'             => 'devotee_' . preg_replace('/[^0-9]/', '', $validated['phone']) . '@guest.devotee',
            'phone'             => $validated['phone'],
            'subject'           => 'Booking via Pandit Website: ' . ($validated['pooja_service'] ?? 'General Consultation'),
            'type'              => 'booking',
            'city'              => $validated['city'] ?? $pandit->city,
            'pooja_service'     => $validated['pooja_service'] ?? null,
            'preferred_date'    => $validated['preferred_date'] ?? null,
            'bhudev_profile_id' => $pandit->id,
            'message'           => $validated['message'] ?? 'Puja booking request from personal website',
            'status'            => 'new',
        ]);

        // Compose pre-filled sacred WhatsApp message
        $pujaName = $validated['pooja_service'] ?: 'Vedic Puja / Ceremony';
        $dateText = !empty($validated['preferred_date']) ? date('d M Y', strtotime($validated['preferred_date'])) : 'Soon';
        $cityName = !empty($validated['city']) ? ' in ' . $validated['city'] : '';

        $waMessage = "Namaste Shastriji,\n\n"
            . "My name is {$validated['name']} (Mobile: {$validated['phone']}).\n"
            . "I would like to book '{$pujaName}'{$cityName} on date: {$dateText}.\n";

        if (!empty($validated['message'])) {
            $waMessage .= "Requirement: " . $validated['message'] . "\n";
        }

        $waMessage .= "\nPlease confirm your availability and Muhurat details.";

        $waUrl = $domain->generateWhatsAppUrl($waMessage);

        if ($request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'whatsapp_url' => $waUrl,
                'message'      => 'Inquiry saved successfully. Redirecting to WhatsApp...',
            ]);
        }

        return redirect()->away($waUrl);
    }
}
