Apple Push Notification Library
===============================

### How to use: ###


// if sandbox certificate is provided we assume that this is under development
$apns = new DietApns('path-to-prod-certificate', 'path-to-sandbox-cert-[optional]');


// start to create the payload
$apns->create('token-id');

// add the alert
$apns->addAlert('this is a message');

// you can add a loc-key and loc-args
$apns->addAlert('this is a message', 'key', 'args');

// add the badge
$apns->addBadge(5);

// add the sound
$apns->addSound('sound.caf');

// send the notification
$apns->send();

// NOTE: after the message is sent successfuly the token will be set to null
// you can do
$apns->create(2);

// and send the same message to a different user
$apns->send();

// you can also chain
$apns->create(1)
    ->addAlert('this is a message', 'key', 'args')
    ->addBadge(2)
    ->addSound('sound.caf')
    ->send()
    ->create(2)
    ->send();


### Todo ###
* Add exceptions
* Add logging
