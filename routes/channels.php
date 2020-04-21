<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('storeRegistration',function(){
  return true;
});
Broadcast::channel('subscriptionChannel',function(){
    return true;
  });
  Broadcast::channel('requestNotifChannel',function(){
    return true;
  });
  Broadcast::channel('CustomerOrderChannel',function(){
    return true;
  });
  Broadcast::channel('BlockCustomerChannel',function(){
    return true;
  });
  Broadcast::channel('ChatUnseenChannel',function(){
    return true;
  });
  Broadcast::channel('listenToCustomerChannel',function(){
    return true;
  });
  Broadcast::channel('listenToStoreChannel',function(){
    return true;
  });
  Broadcast::channel('singleOrderChannel',function(){
    return true;
  });

