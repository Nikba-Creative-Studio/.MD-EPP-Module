<?php

declare(strict_types=1);

namespace Tests;

use App\Epp\EppMdClient;
use PHPUnit\Framework\TestCase;

class XmlParserTest extends TestCase
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

    public function testParseLoginResponse(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg>User ID was authenticated. Welcome.</msg>
    </result>
    <trID>
      <clTRID>TEST123</clTRID>
    </trID>
  </response>
</epp>';

        $result = $method->invoke($client, $xml);

        $this->assertEquals(1000, $result['code']);
        $this->assertEquals('User ID was authenticated. Welcome.', $result['message']);
        $this->assertStringContainsString('<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">', $result['raw_xml']);
    }

    public function testParseCheckResponse(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <resData>
      <domain:chkData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name res="1">domain1.md</domain:name>
        <domain:name res="0">nic.md</domain:name>
      </domain:chkData>
    </resData>
    <clTRID>20231126093102</clTRID>
  </response>
</epp>';

        $result = $method->invoke($client, $xml);

        $this->assertEquals(1000, $result['code']);
        $this->assertEquals('Command completed successfully', $result['message']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertEquals('domain1.md', $result['data']['name']);
        $this->assertEquals('1', $result['data']['name_res']);
    }

    public function testParseCreateResponse(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $xml = '<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:creData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md=created expiration date 2024-12-31</domain:name>
      <domain:name res="0">nic.md=busy</domain:name>
    </domain:creData>
  </resData>
  <clTRID>20230201150751</clTRID>
</response>';

        $result = $method->invoke($client, $xml);

        $this->assertEquals(1000, $result['code']);
        $this->assertEquals('Command completed successfully', $result['message']);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('name', $result['data']);
        $this->assertEquals('domain1.md=created expiration date 2024-12-31', $result['data']['name']);
        $this->assertEquals('1', $result['data']['name_res']);
    }

    public function testParseInfoResponse(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $xml = '<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:infData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md</domain:name>
      <domain:adm_orgname>MY SRL com</domain:adm_orgname>
      <domain:adm_email>hm@nic.md</domain:adm_email>
      <domain:ns1_name>ns1.dns.md</domain:ns1_name>
      <domain:ns2_name>ns2.dns.md</domain:ns2_name>
      <domain:reg_date>2023-01-01</domain:reg_date>
      <domain:exp_date>2024-01-01</domain:exp_date>
    </domain:infData>
  </resData>
  <clTRID>20230201150755</clTRID>
</response>';

        $result = $method->invoke($client, $xml);

        $this->assertEquals(1000, $result['code']);
        $this->assertEquals('Command completed successfully', $result['message']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('domain1.md', $result['data']['name']);
        $this->assertEquals('1', $result['data']['name_res']);
        $this->assertEquals('MY SRL com', $result['data']['adm_orgname']);
        $this->assertEquals('hm@nic.md', $result['data']['adm_email']);
        $this->assertEquals('ns1.dns.md', $result['data']['ns1_name']);
        $this->assertEquals('ns2.dns.md', $result['data']['ns2_name']);
        $this->assertEquals('2023-01-01', $result['data']['reg_date']);
        $this->assertEquals('2024-01-01', $result['data']['exp_date']);
    }

    public function testParseErrorResponse(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <result code="2200">
      <msg>Authentication error</msg>
    </result>
    <trID>
      <clTRID>TEST123</clTRID>
    </trID>
  </response>
</epp>';

        $result = $method->invoke($client, $xml);

        $this->assertEquals(2200, $result['code']);
        $this->assertEquals('Authentication error', $result['message']);
    }

    public function testParseInvalidXml(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $this->expectException(\App\Epp\Exceptions\EppException::class);
        $this->expectExceptionMessage('Invalid XML response');

        $method->invoke($client, 'This is not valid XML');
    }

    public function testParseXmlWithoutResult(): void
    {
        $client = new EppMdClient($this->config);
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('parseResponse');
        $method->setAccessible(true);

        $this->expectException(\App\Epp\Exceptions\EppException::class);
        $this->expectExceptionMessage('No result element in response');

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <response>
    <trID>
      <clTRID>TEST123</clTRID>
    </trID>
  </response>
</epp>';

        $method->invoke($client, $xml);
    }
}
