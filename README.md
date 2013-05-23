Apple Push Notification Library
===============================

### How to use: ###


// if sandbox certificate is provided we assume that this is under development
$apns = new DietApns('path-to-prod-certificate', 'path-to-sandbox-cert-[optional]');

// start to create the payload
$apns->create('token-id');

<p>// add the alert</p>
<pre><code>$apns->addAlert('this is a message');</code></pre>

<p>// you can add a loc-key and loc-args</p>
<pre><code>$apns->addAlert('this is a message', 'key', 'args');</code></pre>

<p>// add the badge</p>
<pre><code>$apns->addBadge(5);</code></pre>

<p>// add the sound</p>
<pre><code>$apns->addSound('sound.caf');</pre></code>

<p>// send the notification</p>
<pre><code>$apns->send();</code></pre>

<p>
// NOTE: after the message is sent successfuly the token will be set to null
// you can do
</p>
<pre><code>$apns->create(2);</code></pre>

<p>// and send the same message to a different user</p>
<pre><code>$apns->send();</code></pre>

<p>// you can also chain</p>
<pre><code>$apns->create(1)
    ->addAlert('this is a message', 'key', 'args')
    ->addBadge(2)
    ->addSound('sound.caf')
    ->send()
    ->create(2)
    ->send();
</code></pre>

### Todo ###
* Add exceptions
* Add logging
