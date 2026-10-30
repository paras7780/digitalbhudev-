<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhudevDomain extends Model
{
    protected $table = 'bhudev_domains';

    protected $fillable = [
        'bhudev_profile_id',
        'domain_name',
        'subdomain',
        'tier',
        'status',
        'suspension_reason',
        'suspended_at',
        'quoted_price',
        'currency',
        'bhudev_notes',
        'admin_notes',
        'hero_title',
        'hero_tagline',
        'about_intro',
        'whatsapp_number',
        'dakshina_qr_path',
        'upi_id',
        'theme_color',
        'separate_db_name',
        'separate_db_host',
        'activated_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'quoted_price' => 'decimal:2',
            'activated_at' => 'datetime',
            'suspended_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class, 'bhudev_profile_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getEffectiveWhatsAppNumber(): ?string
    {
        if (!empty($this->whatsapp_number)) {
            return preg_replace('/[^0-9]/', '', $this->whatsapp_number);
        }

        $user = $this->profile?->user;
        $userPhone = $user?->phone ?? $user?->mobile;
        if (!empty($userPhone)) {
            return preg_replace('/[^0-9]/', '', $userPhone);
        }

        return null;
    }

    public function generateWhatsAppUrl(string $message): string
    {
        $number = $this->getEffectiveWhatsAppNumber() ?? '919876543210';
        // Prefix with 91 if 10 digits
        if (strlen($number) === 10) {
            $number = '91' . $number;
        }

        return 'https://wa.me/' . $number . '?text=' . urlencode($message);
    }

    public function generateSuspensionWhatsAppUrl(?string $reason = null): string
    {
        $reasonText = $reason ?: ($this->suspension_reason ?: 'Annual domain & hosting renewal pending');
        $name = $this->hero_title ?: ($this->profile?->display_name ?? 'Shastri Ji');

        $message = "Namaste {$name},\n\n"
            . "This is an important update from DigitalBhudev regarding your personal website ({$this->domain_name}).\n\n"
            . "⚠️ Status: Temporarily Suspended\n"
            . "📌 Reason: {$reasonText}\n\n"
            . "To restore your live website and continue receiving direct devotee WhatsApp bookings, please connect with us or complete the pending renewal.\n\n"
            . "DigitalBhudev Support Team";

        return $this->generateWhatsAppUrl($message);
    }

    public function generateRenewalReminderWhatsAppUrl(): string
    {
        $name = $this->hero_title ?: ($this->profile?->display_name ?? 'Shastri Ji');
        $price = $this->quoted_price ? '₹' . number_format($this->quoted_price, 2) : 'the agreed annual fee';

        $message = "Namaste {$name},\n\n"
            . "Your personal website ({$this->domain_name}) is due for its annual hosting & domain renewal ({$price}).\n\n"
            . "Please confirm your renewal to ensure uninterrupted website uptime and devotee inquiries.\n\n"
            . "DigitalBhudev Support Team";

        return $this->generateWhatsAppUrl($message);
    }
}
