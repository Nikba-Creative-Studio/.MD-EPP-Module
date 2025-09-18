<?php

declare(strict_types=1);

namespace Tests;

use App\Epp\EppMdClient;
use PHPUnit\Framework\TestCase;

class XmlBuilderTest extends TestCase
{
    private array $config;

    protected function setUp(): void
    {
        $this->config = [
            'base_url' => 'https://test.example.com',
            'client_id' => 'test_client',
            'password' => 'test_password',
            'account' => 'test_account',
            'account_password' => 'test_account_password',
            'transport' => [
                'connect_timeout' => 30,
                'read_timeout' => 60,
                'retries' => 3,
                'verify_ssl' => true,
                'ca_bundle' => '',
                'user_agent' => 'Test-Agent/1.0',
            ],
            'logging' => [
                'enabled' => false,
                'redact' => true,
                'retain_days' => 14,
                'storage_path' => '/tmp/test_logs',
            ],
        ];
    }

    public function testLoginXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildLoginXml');
        $method->setAccessible(true);
        
        $xml = $method->invoke($client, 'TEST123');
        
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $xml);
        $this->assertStringContainsString('<epp xmlns="urn:ietf:params:xml:ns:epp-1.0"', $xml);
        $this->assertStringContainsString('<clID>test_client</clID>', $xml);
        $this->assertStringContainsString('<pw>test_password</pw>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testLogoutXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildLogoutXml');
        $method->setAccessible(true);
        
        $xml = $method->invoke($client, 'TEST123');
        
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $xml);
        $this->assertStringContainsString('<logout/>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testCheckXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildCheckXml');
        $method->setAccessible(true);
        
        $domains = ['example.md', 'test.md'];
        $xml = $method->invoke($client, $domains, 'TEST123');
        
        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $xml);
        $this->assertStringContainsString('<domain:check', $xml);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $xml);
        $this->assertStringContainsString('<domain:name>test.md</domain:name>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testCreateXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildCreateXml');
        $method->setAccessible(true);
        
        $payload = [
            'account' => 'test_account',
            'account_pw' => 'test_password',
            'name' => 'example.md',
            'adm_orgname' => 'Test Org',
            'adm_firstname' => 'John',
            'adm_lastname' => 'Doe',
            'adm_email' => 'john@example.com',
            'adm_type' => 'organization',
            'adm_taxid' => '123456789',
            'ns1_name' => 'ns1.example.com',
            'ns1_ip' => '1.2.3.4',
            'ns2_name' => 'ns2.example.com',
            'ns2_ip' => '5.6.7.8',
        ];
        
        $xml = $method->invoke($client, $payload, 'TEST123');
        
        $this->assertStringContainsString('<domain:create', $xml);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $xml);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $xml);
        $this->assertStringContainsString('<domain:adm_orgname>Test Org</domain:adm_orgname>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testInfoXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildInfoXml');
        $method->setAccessible(true);
        
        $xml = $method->invoke($client, 'example.md', 'TEST123');
        
        $this->assertStringContainsString('<domain:info', $xml);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $xml);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testRenewXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildRenewXml');
        $method->setAccessible(true);
        
        $xml = $method->invoke($client, 'example.md', '2024-12-31', 2, 'TEST123');
        
        $this->assertStringContainsString('<domain:renew', $xml);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $xml);
        $this->assertStringContainsString('curexp="2024-12-31"', $xml);
        $this->assertStringContainsString('years="2"', $xml);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testDeleteXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildDeleteXml');
        $method->setAccessible(true);
        
        $domains = ['example.md'];
        $xml = $method->invoke($client, $domains, 'TEST123');
        
        $this->assertStringContainsString('<domain:delete', $xml);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $xml);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testTransferXmlGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('buildTransferXml');
        $method->setAccessible(true);
        
        // Test transfer request
        $domains = ['example.md'];
        $xml = $method->invoke($client, $domains, 'request', 'TEST123');
        
        $this->assertStringContainsString('<transfer op="request">', $xml);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $xml);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
        
        // Test transfer execute
        $codes = ['TRANSFER123'];
        $xml = $method->invoke($client, $codes, 'execute', 'TEST123');
        
        $this->assertStringContainsString('<transfer op="execute">', $xml);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $xml);
        $this->assertStringContainsString('<domain:trcode>TRANSFER123</domain:trcode>', $xml);
        $this->assertStringContainsString('<clTRID>TEST123</clTRID>', $xml);
    }

    public function testClTRIDGeneration(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('generateClTRID');
        $method->setAccessible(true);
        
        $clTRID = $method->invoke($client);
        
        $this->assertMatchesRegularExpression('/^\d{14}[a-z0-9]{6}$/', $clTRID);
        $this->assertEquals(20, strlen($clTRID));
    }
}
