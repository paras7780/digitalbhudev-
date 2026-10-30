<?php

namespace Tests\Feature;

use App\Models\BhudevProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_locale_is_english(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Find a Bhudev');
        $response->assertSee('Pooja Samagri');
    }

    public function test_guest_switching_language_to_gujarati_sets_cookie_and_shows_gujarati(): void
    {
        $switchResponse = $this->get(route('locale.switch', 'gu'));
        $switchResponse->assertRedirect();
        $switchResponse->assertCookie('locale', 'gu');

        // Subsequent request with the cookie attached
        $response = $this->withUnencryptedCookie('locale', 'gu')->get('/');
        $response->assertStatus(200);
        $response->assertSee('ભૂદેવ શોધો');
        $response->assertSee('પૂજા સામગ્રી');
        $response->assertSee('વિશ્વાસનો સેતુ');
    }

    public function test_guest_switching_language_to_hindi_sets_cookie_and_shows_hindi(): void
    {
        $switchResponse = $this->get(route('locale.switch', 'hi'));
        $switchResponse->assertRedirect();
        $switchResponse->assertCookie('locale', 'hi');

        // Subsequent request with the cookie attached
        $response = $this->withUnencryptedCookie('locale', 'hi')->get('/');
        $response->assertStatus(200);
        $response->assertSee('भूदेव खोजें');
        $response->assertSee('पूजा सामग्री');
        $response->assertSee('विश्वास का सेतु');
    }

    public function test_invalid_locale_falls_back_safely_to_english(): void
    {
        $switchResponse = $this->get(route('locale.switch', 'invalid_locale'));
        $switchResponse->assertRedirect();
        $switchResponse->assertCookie('locale', 'en');

        $response = $this->withUnencryptedCookie('locale', 'invalid_locale')->get('/');
        $response->assertStatus(200);
        $response->assertSee('Find a Bhudev');
    }

    public function test_bhudev_switching_language_persists_to_database_profile(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'          => $user->id,
            'display_name'     => 'Shastri Paras Ji',
            'slug'             => 'shastri-paras-ji',
            'city'             => 'Ahmedabad',
            'status'           => 'pending',
            'preferred_locale' => 'en',
        ]);

        $this->assertEquals('en', $profile->fresh()->preferred_locale);

        // Bhudev switches language to Gujarati
        $response = $this->actingAs($user)->get(route('locale.switch', 'gu'));
        $response->assertRedirect();
        $response->assertCookie('locale', 'gu');

        // Check database is updated
        $this->assertEquals('gu', $profile->fresh()->preferred_locale);

        // Bhudev switches language to Hindi
        $response = $this->actingAs($user)->get(route('locale.switch', 'hi'));
        $response->assertRedirect();
        $this->assertEquals('hi', $profile->fresh()->preferred_locale);
    }

    public function test_logged_in_bhudev_with_db_preference_renders_in_preferred_locale(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        BhudevProfile::create([
            'user_id'          => $user->id,
            'display_name'     => 'Pandit Rameshwar',
            'slug'             => 'pandit-rameshwar',
            'status'           => 'approved',
            'preferred_locale' => 'gu',
        ]);

        // Request without any cookie — should load Gujarati directly from DB profile
        $response = $this->actingAs($user)->get(route('bhudev.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('ઝાંખી (Overview)');
        $response->assertSee('મારી વેબસાઇટ અને ડોમેન');
    }

    public function test_bhudev_can_update_preferred_locale_via_profile_edit(): void
    {
        $user = User::factory()->create(['role' => 'bhudev']);
        $profile = BhudevProfile::create([
            'user_id'          => $user->id,
            'display_name'     => 'Pandit Ji',
            'slug'             => 'pandit-ji',
            'status'           => 'pending',
            'preferred_locale' => 'en',
        ]);

        $response = $this->actingAs($user)->put(route('bhudev.profile.update'), [
            'display_name'     => 'Pandit Ji Updated',
            'preferred_locale' => 'hi',
            'city'             => 'Rajkot',
            'languages'        => 'Hindi, Sanskrit',
        ]);

        $response->assertRedirect(route('bhudev.profile.edit'));
        $this->assertEquals('hi', $profile->fresh()->preferred_locale);
    }
}
