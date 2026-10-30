<?php

namespace Tests\Feature;

use App\Models\BhudevProfile;
use App\Models\BhudevSamagriCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SamagriStudioTest extends TestCase
{
    use RefreshDatabase;

    private function createBhudev(string $name = 'Shastri Paras Maharaj', string $email = 'paras@example.com'): array
    {
        $user = User::factory()->create([
            'name'              => $name,
            'email'             => $email,
            'role'              => 'bhudev',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $profile = BhudevProfile::create([
            'user_id'          => $user->id,
            'display_name'     => $name,
            'slug'             => 'shastri-paras-' . rand(100, 999),
            'city'             => 'Ahmedabad',
            'status'           => 'approved',
            'is_visible'       => true,
            'preferred_locale' => 'gu',
        ]);

        return [$user, $profile];
    }

    public function test_guest_is_redirected_from_samagri_studio(): void
    {
        $response = $this->get(route('bhudev.samagri-studio'));
        $response->assertRedirect(route('login'));
    }

    public function test_bhudev_can_access_samagri_studio_with_presets(): void
    {
        [$user, $profile] = $this->createBhudev();

        $response = $this->actingAs($user)->get(route('bhudev.samagri-studio'));
        $response->assertStatus(200);
        $response->assertSee('પૂજા સામગ્રી પત્રિકા સ્ટુડિયો');
        $response->assertSee('ગણપતિ પૂજા માટે ની યાદી');
        $response->assertSee($profile->display_name);
    }

    public function test_bhudev_can_save_new_custom_samagri_card(): void
    {
        [$user, $profile] = $this->createBhudev();

        $payload = [
            'pooja_slug'         => 'ganpati',
            'pooja_title'        => 'ગણપતિ પૂજા માટે ની યાદી',
            'mantra_line'        => '|| શ્રી ગણેશાય નમઃ ||',
            'firm_title'         => 'શ્રી નારાયણ જ્યોતિષ',
            'left_priest_name'   => 'મેહુલ દાદા',
            'left_priest_phone'  => '8980555756',
            'right_priest_name'  => 'પારસ દાદા',
            'right_priest_phone' => '9586072822',
            'column1_items'      => "ગણપતિ દાદાની મૂર્તિ\nબાજોઠ - ૨\nઘઉં ૫૦૦ ગ્રામ",
            'column2_items'      => "ધરો\nનાગરવેલ ના પાન - પ\nહાર - ૨",
            'yajman_name'        => 'શ્રી રમેશભાઈ પટેલ',
            'locale'             => 'gu',
        ];

        $response = $this->actingAs($user)->post(route('bhudev.samagri-studio.save'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('bhudev_samagri_cards', [
            'bhudev_profile_id' => $profile->id,
            'pooja_slug'        => 'ganpati',
            'pooja_title'       => 'ગણપતિ પૂજા માટે ની યાદી',
            'firm_title'        => 'શ્રી નારાયણ જ્યોતિષ',
            'right_priest_name' => 'પારસ દાદા',
        ]);
    }

    public function test_bhudev_can_update_existing_samagri_card(): void
    {
        [$user, $profile] = $this->createBhudev();

        $card = BhudevSamagriCard::create([
            'bhudev_profile_id' => $profile->id,
            'pooja_slug'        => 'satyanarayan',
            'pooja_title'       => 'સત્યનારાયણ કથા નો પૂજાપો',
            'mantra_line'       => '|| શ્રી ગણેશાય નમઃ ||',
            'firm_title'        => 'નારાયણ જ્યોતિષ',
            'right_priest_name' => 'શાસ્ત્રીજી',
            'locale'            => 'gu',
        ]);

        $payload = [
            'card_id'            => $card->id,
            'pooja_slug'         => 'satyanarayan',
            'pooja_title'        => 'શ્રી સત્યનારાયણ વ્રત કથા પૂજાપો (સુધારેલ)',
            'mantra_line'        => '|| શ્રી લક્ષ્મીનારાયણાય નમઃ ||',
            'firm_title'         => 'નારાયણ જ્યોતિષ કાર્યાલય',
            'right_priest_name'  => 'શાસ્ત્રી પારસ દાદા',
            'right_priest_phone' => '9586072822',
            'column1_items'      => "કંકુ - ૧૦ ગ્રામ\nસોપારી - ૧૦૦ ગ્રામ",
            'column2_items'      => "શ્રીફળ - ૨\nપંચામૃત",
            'yajman_name'        => 'શ્રી જયેશભાઈ સોની',
        ];

        $response = $this->actingAs($user)->post(route('bhudev.samagri-studio.save'), $payload);

        $response->assertRedirect();
        $card->refresh();
        $this->assertEquals('શ્રી સત્યનારાયણ વ્રત કથા પૂજાપો (સુધારેલ)', $card->pooja_title);
        $this->assertEquals('નારાયણ જ્યોતિષ કાર્યાલય', $card->firm_title);
        $this->assertEquals('શાસ્ત્રી પારસ દાદા', $card->right_priest_name);
    }

    public function test_bhudev_cannot_delete_another_bhudevs_card(): void
    {
        [$userA, $profileA] = $this->createBhudev('Bhudev A', 'a@example.com');
        [$userB, $profileB] = $this->createBhudev('Bhudev B', 'b@example.com');

        $cardA = BhudevSamagriCard::create([
            'bhudev_profile_id' => $profileA->id,
            'pooja_slug'        => 'ganpati',
            'pooja_title'       => 'Ganpati Pooja A',
        ]);

        $response = $this->actingAs($userB)->delete(route('bhudev.samagri-studio.delete', $cardA->id));
        $response->assertStatus(403);

        $this->assertDatabaseHas('bhudev_samagri_cards', ['id' => $cardA->id]);
    }

    public function test_bhudev_can_delete_own_card(): void
    {
        [$user, $profile] = $this->createBhudev();

        $card = BhudevSamagriCard::create([
            'bhudev_profile_id' => $profile->id,
            'pooja_slug'        => 'ganpati',
            'pooja_title'       => 'Ganpati Pooja To Delete',
        ]);

        $response = $this->actingAs($user)->delete(route('bhudev.samagri-studio.delete', $card->id));
        $response->assertRedirect(route('bhudev.samagri-studio'));

        $this->assertDatabaseMissing('bhudev_samagri_cards', ['id' => $card->id]);
    }

    public function test_public_devotee_can_view_saved_patrika_card(): void
    {
        [$user, $profile] = $this->createBhudev();

        $card = BhudevSamagriCard::create([
            'bhudev_profile_id'  => $profile->id,
            'pooja_slug'         => 'randal-mataji',
            'pooja_title'        => 'રાંદલ માતાજી નો પૂજાપો',
            'mantra_line'        => '|| શ્રી ગણેશાય નમઃ ||',
            'firm_title'         => 'નારાયણ જ્યોતિષ',
            'right_priest_name'  => 'શાસ્ત્રી પારસ દાદા',
            'right_priest_phone' => '9586072822',
            'column1_items'      => "કંકુ - ૧૦ ગ્રામ\nશ્રીફળ - ૩",
            'column2_items'      => "અખંડ દિવો - ૨\nપંચામૃત",
            'yajman_name'        => 'શ્રી નીલેશભાઈ',
            'locale'             => 'gu',
        ]);

        $response = $this->get(route('samagri.card.show', $card->id));
        $response->assertStatus(200);
        $response->assertSee('રાંદલ માતાજી નો પૂજાપો');
        $response->assertSee('નારાયણ જ્યોતિષ');
        $response->assertSee('શાસ્ત્રી પારસ દાદા');
        $response->assertSee('નીલેશભાઈ');
    }
}
