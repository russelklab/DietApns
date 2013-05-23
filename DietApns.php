<?php

class DietApns
{
    // Production
    private $ssl = 'ssl://gateway.push.apple.com:2195';
    public $certificate = null;

    // Sandbox & Development
    private $sandbox = 'ssl://gateway.sandbox.push.apple.com:2195';
    public $sandbox_certificate = null;

    protected $token;

    protected $payload= array();

    /**
     *
     * @param String $certificate The production Apple Push notification certificate
     * @param null|string $sandbox_certificate The development Apple Push notification certificate
     */
    public function __construct($certificate, $sandbox_certificate = null)
    {
        $this->certificate = $certificate;
        $this->sandbox_certificate = $sandbox_certificate;
    }

    /**
     * Create a new notification payload
     *
     * @param String $token The receiver of the notification
     * @return DietApns $this The current object
     */
    public function create($token)
    {
        $this->token = $token;
        $this->payload['aps'] = array();
        return $this;
    }

    /**
     * Return the payload array
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Return the token id
     *
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add the alert message to the aps dictionary
     *
     * @param String $body Text of the alert message
     * @param null $loc_key
     * @param null $loc_args
     * @return DietApns $this The current object
     */
    public function addAlert($body, $loc_key = null, $loc_args = null)
    {
        $this->payload['aps']['alert'] = array();

        if ($loc_key && $loc_args) {
            $this->payload['aps']['alert']['loc-key'] = $loc_key;
            $this->payload['aps']['alert']['body'] = $body;
            $this->payload['aps']['alert']['loc-args'] = $loc_args;
        } else {
            $this->payload['aps']['alert'] = $body;
        }

        return $this;
    }

    /**
     * Add the badge key and value to the aps dictionary
     *
     * @param $badge
     * @return DietApns $this The current object
     */
    public function addBadge($badge)
    {
        $this->payload['aps']['badge'] = $badge;
        return $this;
    }

    /**
     * Add the sound key and value to the aps dictionary
     *
     * @param $sound
     * @return $this
     */
    public function addSound($sound)
    {
        $this->payload['aps']['sound'] = $sound;
        return $this;
    }

    /**
     * Add a custom key to the aps payload
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addCustom($key, $value)
    {
        $this->payload[$key] = $value;
        return $this;
    }

    /**
     * Send the APNS message
     *
     * @return $this|bool
     */
    public function send()
    {
        if (!isset($this->token)) {
            return false;
        }

        $ctx = stream_context_create();

        if (!empty($this->sandbox_certificate)) {
            stream_context_set_option($ctx, 'ssl', 'local_cert', $this->sandbox_certificate);
            $fp = stream_socket_client($this->sandbox, $err, $err_str, 60, STREAM_CLIENT_CONNECT, $ctx);
        } elseif (!empty($this->certificate)) {
            stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificate);
            $fp = stream_socket_client($this->ssl, $err, $err_str, 60, STREAM_CLIENT_CONNECT, $ctx);
        } else {
            return false;
        }

        if(!$fp) {
            return false;
        }

        $json_payload = json_encode($this->payload);
        $msg = chr(0).pack("n",32).pack('H*', str_replace(' ', '', $this->token)).pack("n",strlen($json_payload)).$json_payload;

        fwrite($fp, $msg);
        fclose($fp);

        // unset the token to indicate that the message was sent
        unset($this->token);

        return $this;
    }
}
