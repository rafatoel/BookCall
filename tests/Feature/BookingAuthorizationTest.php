<?php

use App\Models\User;
use App\Models\Booking;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('requires authentication for bookings page', function () {
    $response = $this->get('/bookings');
    
    $response->assertRedirect('/login');
});

it('shows bookings page for authenticated user', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/bookings');
    
    $response->assertStatus(200);
    $response->assertViewIs('bookings.index');
});

it('only shows user\'s own bookings', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    Booking::factory()->create(['user_id' => $user1->id, 'title' => 'User 1 Booking']);
    Booking::factory()->create(['user_id' => $user2->id, 'title' => 'User 2 Booking']);
    
    $response = $this->actingAs($user1)->get('/bookings');
    
    $response->assertSee('User 1 Booking');
    $response->assertDontSee('User 2 Booking');
});

it('prevents unauthorized user from completing another user\'s booking', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    
    $booking = Booking::factory()->create(['user_id' => $owner->id]);
    
    $response = $this->actingAs($otherUser)->patch("/bookings/{$booking->id}/complete");
    
    $response->assertForbidden();
});

it('allows owner to complete their booking', function () {
    $user = User::factory()->create();
    
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'complete' => false
    ]);
    
    $response = $this->actingAs($user)->patch("/bookings/{$booking->id}/complete");
    
    $response->assertRedirect();
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'complete' => true
    ]);
});

it('prevents unauthorized user from confirming another user\'s booking', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    
    $booking = Booking::factory()->create(['user_id' => $owner->id]);
    
    $response = $this->actingAs($otherUser)->patch("/bookings/{$booking->id}/confirm", [
        'meeting_link' => 'https://zoom.us/test'
    ]);
    
    $response->assertForbidden();
});

it('prevents unauthorized user from canceling another user\'s booking', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    
    $booking = Booking::factory()->create(['user_id' => $owner->id]);
    
    $response = $this->actingAs($otherUser)->patch("/bookings/{$booking->id}/cancel");
    
    $response->assertForbidden();
});

it('validates meeting link as URL when confirming booking', function () {
    $user = User::factory()->create();
    
    $booking = Booking::factory()->create(['user_id' => $user->id]);
    
    $response = $this->actingAs($user)->patch("/bookings/{$booking->id}/confirm", [
        'meeting_link' => 'not-a-valid-url'
    ]);
    
    $response->assertSessionHasErrors('meeting_link');
});

it('requires valid URL for meeting link', function () {
    $user = User::factory()->create();
    
    $booking = Booking::factory()->create(['user_id' => $user->id]);
    
    $response = $this->actingAs($user)->patch("/bookings/{$booking->id}/confirm", [
        'meeting_link' => 'https://zoom.us/j/123456789'
    ]);
    
    $response->assertRedirect();
    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'meeting_link' => 'https://zoom.us/j/123456789',
        'confirmed' => true
    ]);
});
