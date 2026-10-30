<?php

namespace Tests\Feature;

use App\Mail\NewInquiryAlertMail;
use App\Models\BhudevProfile;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CustomerReachAndEngagementTest extends TestCase
{
    use RefreshDatabase;

    private function createBhudev(): array
    {
        $user = User::factory()->create([
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $profile = BhudevProfile::create([
            'user_id'      => $user->id,
            'display_name' => 'Pandit Harish Shastri',
            'slug'         => 'pandit-harish-shastri',
            'city'         => 'Ahmedabad',
            'state'        => 'Gujarat',
            'status'       => 'approved',
            'is_visible'   => true,
            'show_mobile'  => true,
        ]);

        return [$user, $profile];
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_devotee_can_view_pooja_samagri_index_and_detail_pages()
    {
        $response = $this->get(route('samagri.index'));
        $response->assertStatus(200);
        $response->assertSee('Shree Satyanarayan Bhagwan Katha');
        $response->assertSee('Griha Pravesh');

        $detailResponse = $this->get(route('samagri.show', 'satyanarayan-katha'));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('શ્રી સત્યનારાયણ ભગવાન કથા');
        $detailResponse->assertSee('Haldi');
        $detailResponse->assertSee('Panchamrit');
        $detailResponse->assertSee('Book a Bhudev for this Ceremony');
    }

    public function test_devotee_can_view_shubh_muhurat_guide()
    {
        $response = $this->get(route('muhurat.index'));
        $response->assertStatus(200);
        $response->assertSee('Vivah Sanskar');
        $response->assertSee('Griha Pravesh');
        $response->assertSee('Vehicle Purchase');
        $response->assertSee('Book Bhudev for this Date');
    }

    public function test_contact_form_saves_inquiry_with_phone_and_dispatches_email()
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name'    => 'Devotee Jayesh Shah',
            'email'   => 'jayesh@example.com',
            'phone'   => '9898012345',
            'subject' => 'Need information about Vastu puja',
            'message' => 'Please connect me with a priest in Ahmedabad.',
        ]);

        $response->assertSessionHas('status');

        $this->assertDatabaseHas('inquiries', [
            'name'  => 'Devotee Jayesh Shah',
            'email' => 'jayesh@example.com',
            'phone' => '9898012345',
            'type'  => 'contact',
        ]);

        Mail::assertSent(NewInquiryAlertMail::class);
    }

    public function test_spam_honeypot_rejects_bot_submissions()
    {
        $response = $this->post(route('contact.store'), [
            'name'    => 'Spam Bot',
            'email'   => 'bot@spam.com',
            'message' => 'Buy cheap medications',
            'website' => 'http://spam-link.com', // honeypot triggered
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseMissing('inquiries', ['email' => 'bot@spam.com']);
    }

    public function test_devotee_can_submit_pooja_booking_request_for_specific_bhudev()
    {
        Mail::fake();
        [$bhudev, $profile] = $this->createBhudev();

        $response = $this->post(route('booking.store'), [
            'name'              => 'Chetan Trivedi',
            'phone'             => '9876543210',
            'email'             => 'chetan@example.com',
            'city'              => 'Ahmedabad',
            'pooja_service'     => 'Satyanarayan Katha',
            'preferred_date'    => '2026-11-20',
            'bhudev_profile_id' => $profile->id,
            'message'           => 'Looking to conduct katha at our new residence.',
        ]);

        $response->assertSessionHas('status');

        $this->assertDatabaseHas('inquiries', [
            'name'              => 'Chetan Trivedi',
            'phone'             => '9876543210',
            'type'              => 'booking',
            'pooja_service'     => 'Satyanarayan Katha',
            'bhudev_profile_id' => $profile->id,
        ]);

        Mail::assertSent(NewInquiryAlertMail::class);

        // Bhudev can see this booking on their dashboard
        $bhudevDashboard = $this->actingAs($bhudev)->get(route('bhudev.dashboard'));
        $bhudevDashboard->assertStatus(200);
        $bhudevDashboard->assertSee('Chetan Trivedi');
        $bhudevDashboard->assertSee('Satyanarayan Katha');
        $bhudevDashboard->assertSee('Reply on WhatsApp');
    }

    public function test_admin_can_manage_inquiries_and_bookings()
    {
        $admin = $this->createAdmin();

        $inquiry = Inquiry::create([
            'name'          => 'Kishore Dave',
            'email'         => 'kishore@example.com',
            'phone'         => '9825123456',
            'type'          => 'booking',
            'pooja_service' => 'Rudrabhishek',
            'status'        => 'new',
            'message'       => 'Need puja on Monday morning.',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.inquiries'));
        $response->assertStatus(200);
        $response->assertSee('Kishore Dave');
        $response->assertSee('Rudrabhishek');
        $response->assertSee('Booking');

        // Admin updates status
        $updateResponse = $this->actingAs($admin)->patch(route('admin.inquiries.update', $inquiry), [
            'status'     => 'in_progress',
            'admin_note' => 'Connected with Shastriji on phone.',
        ]);

        $updateResponse->assertSessionHas('status');

        $inquiry->refresh();
        $this->assertSame('in_progress', $inquiry->status);
        $this->assertSame('Connected with Shastriji on phone.', $inquiry->admin_note);
    }

    public function test_panchang_and_rashifal_contain_whatsapp_share_triggers()
    {
        $panchangResponse = $this->get(route('panchang.index'));
        $panchangResponse->assertStatus(200);
        $panchangResponse->assertSee('Share on WhatsApp');

        $rashifalResponse = $this->get(route('rashifal.index'));
        $rashifalResponse->assertStatus(200);
        $rashifalResponse->assertSee('Share on WhatsApp');
    }
}
