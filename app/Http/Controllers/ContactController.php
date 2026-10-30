<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryAlertMail;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Support\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Submit general contact inquiry.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:25',
            'subject' => 'nullable|string|max:180',
            'message' => 'required|string|max:5000',
            'website' => 'nullable|max:0', // honeypot spam protection
        ]);

        $inquiry = Inquiry::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
            'type'    => 'contact',
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'status'  => 'new',
            'ip_hash' => $request->ip() ? hash('sha256', $request->ip() . config('app.key')) : null,
        ]);

        Audit::record($request, 'public.inquiry.created', $inquiry);

        $this->notifyAdmin($inquiry);

        return back()->with('status', 'Thank you! Your message has been received. Our team will contact you shortly.');
    }

    /**
     * Submit a pooja booking / ceremony inquiry directly or for a specific Bhudev.
     */
    public function booking(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:120',
            'phone'             => 'required|string|max:25',
            'email'             => 'nullable|email|max:255',
            'city'              => 'nullable|string|max:100',
            'pooja_service'     => 'required|string|max:150',
            'preferred_date'    => 'nullable|date',
            'bhudev_profile_id' => 'nullable|integer|exists:bhudev_profiles,id',
            'message'           => 'nullable|string|max:3000',
            'website'           => 'nullable|max:0', // honeypot
        ]);

        $bhudevProfile = !empty($data['bhudev_profile_id'])
            ? BhudevProfile::find($data['bhudev_profile_id'])
            : null;

        $inquiry = Inquiry::create([
            'name'              => $data['name'],
            'email'             => $data['email'] ?? 'devotee@digitalbhudev.com',
            'phone'             => $data['phone'],
            'type'              => 'booking',
            'city'              => $data['city'] ?? ($bhudevProfile?->city),
            'pooja_service'     => $data['pooja_service'],
            'preferred_date'    => $data['preferred_date'] ?? null,
            'bhudev_profile_id' => $bhudevProfile?->id,
            'subject'           => 'Pooja Booking: ' . $data['pooja_service'] . ($bhudevProfile ? ' with ' . $bhudevProfile->display_name : ''),
            'message'           => $data['message'] ?? 'Direct pooja booking inquiry submitted online.',
            'status'            => 'new',
            'ip_hash'           => $request->ip() ? hash('sha256', $request->ip() . config('app.key')) : null,
        ]);

        Audit::record($request, 'public.booking.created', $inquiry);

        $this->notifyAdmin($inquiry);

        $msg = $bhudevProfile
            ? "Thank you! Your pooja booking request for {$bhudevProfile->display_name} has been received. We will connect with you on WhatsApp / Phone shortly."
            : "Thank you! Your request for {$data['pooja_service']} has been received. Our team will connect you with a verified Bhudev shortly.";

        return back()->with('status', $msg);
    }

    /**
     * Dispatch email notification to site administrator safely.
     */
    private function notifyAdmin(Inquiry $inquiry): void
    {
        try {
            $adminEmail = config('mail.admin_recipient')
                ?: config('mail.from.address')
                ?: 'rajyaguruparas667@gmail.com';

            Mail::to($adminEmail)->send(new NewInquiryAlertMail($inquiry));
        } catch (\Throwable $e) {
            Log::warning('Could not dispatch inquiry email alert: ' . $e->getMessage());
        }
    }
}
