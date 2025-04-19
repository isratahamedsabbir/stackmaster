<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Room;

/* Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}); */

Broadcast::channel('test-notify.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notify.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-room.{room_id}', function ($user, $room_id) {
    $room = Room::find($room_id);
    return (int) $user->id === (int) $room?->user_one_id || (int) $user->id === (int) $room?->user_two_id;
});