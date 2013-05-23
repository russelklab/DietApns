<?php

require_once __DIR__.'/../DietApns.php';

class DietApnsTest extends PHPUnit_Framework_TestCase
{
    public function testApns()
    {
        $certificate = "";
        $sandbox_certificate = "";

        $apns = new DietApns($certificate, $sandbox_certificate);
        $this->assertTrue(property_exists($apns, 'ssl'));
        $this->assertTrue(property_exists($apns, 'certificate'));
        $this->assertTrue(property_exists($apns, 'sandbox'));
        $this->assertTrue(property_exists($apns, 'sandbox_certificate'));
        $this->assertTrue(property_exists($apns, 'payload'));
        $this->assertTrue(property_exists($apns, 'token'));
    }

    public function testApnsMethods()
    {
        $certificate = "";
        $sandbox_certificate = "";

        $apns = new DietApns($certificate, $sandbox_certificate);
        $this->assertTrue(method_exists($apns, 'create'));
        $this->assertTrue(method_exists($apns, 'addAlert'));
        $this->assertTrue(method_exists($apns, 'addBadge'));
        $this->assertTrue(method_exists($apns, 'addSound'));
        $this->assertTrue(method_exists($apns, 'addCustom'));
        $this->assertTrue(method_exists($apns, 'send'));
    }

    public function testApnsAll()
    {
        $certificate = "";
        $sandbox_certificate = "";

        $apns = new DietApns($certificate, $sandbox_certificate);
        $apns->create(1);

        $this->assertEquals($apns->getToken(), 1);

        $apns->addAlert('This is a message', 'key', 'args');
        $apns->addBadge(2);
        $apns->addSound('sms-received5.caf');

        $payload = $apns->getPayload();
        $this->assertArrayHasKey('aps', $payload);
        $this->assertArrayHasKey('alert', $payload['aps']);
        $this->assertArrayHasKey('badge', $payload['aps']);
        $this->assertArrayHasKey('sound', $payload['aps']);

        $this->assertArrayHasKey('body', $payload['aps']['alert']);
        $this->assertArrayHasKey('loc-key', $payload['aps']['alert']);
        $this->assertArrayHasKey('loc-args', $payload['aps']['alert']);

        $this->assertEquals($payload['aps']['alert']['body'], 'This is a message');
        $this->assertEquals($payload['aps']['alert']['loc-key'], 'key');
        $this->assertEquals($payload['aps']['alert']['loc-args'], 'args');
        $this->assertEquals($payload['aps']['badge'], 2);
        $this->assertEquals($payload['aps']['sound'], 'sms-received5.caf');

        $apns->send();
        $this->assertFalse($apns->getToken());
    }
}