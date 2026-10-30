<?php

namespace Tests\Feature;

use App\Mail\CronFailedMail;
use App\Models\PanchangEntry;
use App\Models\Rashifal;
use App\Models\User;
use App\Services\VedicAstroEngine;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AstroSyncTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'              => 'admin',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);
    }

    public function test_vedic_astro_engine_calculates_valid_panchang_and_rashifal(): void
    {
        $engine = new VedicAstroEngine();
        $date = Carbon::parse('2026-10-24');

        $panchang = $engine->computePanchang($date);

        $this->assertEquals('2026-10-24', $panchang['date']);
        $this->assertNotEmpty($panchang['vaar']);
        $this->assertNotEmpty($panchang['tithi']);
        $this->assertNotEmpty($panchang['nakshatra']);
        $this->assertNotEmpty($panchang['yoga']);
        $this->assertNotEmpty($panchang['karana']);
        $this->assertNotEmpty($panchang['sunrise']);
        $this->assertNotEmpty($panchang['sunset']);
        $this->assertStringContainsString('Abhijit Muhurat', $panchang['notes']);

        $rashifal = $engine->computeRashifal($date);

        $this->assertCount(12, $rashifal);
        $this->assertArrayHasKey('Aries', $rashifal);
        $this->assertArrayHasKey('Pisces', $rashifal);
        $this->assertStringContainsString('Lucky Color', $rashifal['Aries']['content']);
    }

    public function test_artisan_astro_sync_command_caches_upcoming_days(): void
    {
        $this->artisan('astro:sync --days=3 --date=2026-11-01')
            ->assertSuccessful();

        $this->assertEquals(3, PanchangEntry::whereDate('date', '>=', '2026-11-01')->whereDate('date', '<=', '2026-11-03')->count());
        $this->assertEquals(36, Rashifal::whereDate('date', '>=', '2026-11-01')->whereDate('date', '<=', '2026-11-03')->count());
    }

    public function test_admin_can_trigger_sync_from_admin_panel(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/astro/sync', [
            'days' => 5,
            'date' => '2026-12-01',
        ]);

        $response->assertSessionHas('status');
        $this->assertEquals(5, PanchangEntry::whereDate('date', '>=', '2026-12-01')->whereDate('date', '<=', '2026-12-05')->count());
        $this->assertEquals(60, Rashifal::whereDate('date', '>=', '2026-12-01')->whereDate('date', '<=', '2026-12-05')->count());
    }

    public function test_public_panchang_and_rashifal_strictly_show_today_only(): void
    {
        $today = now('Asia/Kolkata')->toDateString();

        // Even if user attempts to pass a future or past date in query string
        $resPanchang = $this->get('/panchang?date=2029-12-25');
        $resPanchang->assertOk();
        $resPanchang->assertSee('Tithi');
        $resPanchang->assertSee(now('Asia/Kolkata')->format('d M Y'));

        // Today's Panchang entry is persisted in MySQL
        $this->assertEquals(1, PanchangEntry::whereDate('date', $today)->count());

        // Even if user attempts to pass a past date in query string
        $resRashifal = $this->get('/rashifal?date=2020-01-01');
        $resRashifal->assertOk();
        $resRashifal->assertSee('Aries');
        $resRashifal->assertSee('Pisces');
        $resRashifal->assertSee(now('Asia/Kolkata')->format('d F Y'));

        // Today's 12 Rashifal entries are persisted in MySQL
        $this->assertEquals(12, Rashifal::whereDate('date', $today)->count());
    }

    public function test_cron_failure_dispatches_email_notification_to_admin(): void
    {
        Mail::fake();

        $adminEmail = $this->admin->email;
        $failedCommand = 'astro:sync';
        $errorMessage = 'Connection timeout to external API host.';

        Mail::to($adminEmail)->send(new CronFailedMail($failedCommand, $errorMessage));

        Mail::assertSent(CronFailedMail::class, function ($mail) use ($adminEmail, $failedCommand) {
            return $mail->hasTo($adminEmail)
                && $mail->commandName === $failedCommand
                && str_contains($mail->envelope()->subject, 'CRITICAL ALERT');
        });
    }
}
