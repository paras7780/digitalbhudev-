<?php
namespace Tests\Feature;
use App\Models\BhudevProfile;use App\Models\User;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class BhudevVisibilityTest extends TestCase { use RefreshDatabase;
 public function test_pending_profile_is_not_publicly_listed_or_accessible(): void {$user=User::factory()->create(['role'=>'bhudev','is_active'=>true]);$profile=BhudevProfile::create(['user_id'=>$user->id,'display_name'=>'Pending Test','slug'=>'pending-test','status'=>'pending','is_visible'=>false]);$this->get('/bhudevs')->assertDontSee('Pending Test');$this->get('/bhudevs/pending-test')->assertNotFound();}
 public function test_approved_visible_profile_can_be_public(): void {$user=User::factory()->create(['role'=>'bhudev','is_active'=>true]);BhudevProfile::create(['user_id'=>$user->id,'display_name'=>'Approved Test','slug'=>'approved-test','status'=>'approved','is_visible'=>true]);$this->get('/bhudevs')->assertOk()->assertSee('Approved Test');$this->get('/bhudevs/approved-test')->assertOk()->assertSee('Approved Test');}
 public function test_suspended_profile_is_not_public_even_if_visibility_flag_is_true(): void {$user=User::factory()->create(['role'=>'bhudev','is_active'=>true]);BhudevProfile::create(['user_id'=>$user->id,'display_name'=>'Suspended Test','slug'=>'suspended-test','status'=>'suspended','is_visible'=>true]);$this->get('/bhudevs/suspended-test')->assertNotFound();}
}
