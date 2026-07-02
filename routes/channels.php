<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('emergency-campaign.{id}', function ($user, $id) {
    return true; // public channel — anyone can see donations
});
