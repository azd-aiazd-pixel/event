<?php

Broadcast::channel('store.{storeId}.pickups', function ($user, $storeId) {
    
    return $user->store()->where('id', $storeId)->exists();
});



Broadcast::channel('store.{storeId}.queue', function ($user, $storeId) {
    return $user->store()->where('id', $storeId)->exists();
});