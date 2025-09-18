<?php

declare(strict_types=1);

namespace Tests;

use App\Epp\Logger\Redactor;
use PHPUnit\Framework\TestCase;

class LoggerRedactionTest extends TestCase
{
    private Redactor $redactor;

    protected function setUp(): void
    {
        $this->redactor = new Redactor();
    }

    public function testRedactPasswordInXml(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <login>
      <clID>test_client</clID>
      <pw>secret_password</pw>
    </login>
  </command>
</epp>';

        $redacted = $this->redactor->redactXml($xml);

        $this->assertStringContainsString('<pw>***REDACTED***</pw>', $redacted);
        $this->assertStringNotContainsString('secret_password', $redacted);
        $this->assertStringContainsString('<clID>test_client</clID>', $redacted);
    }

    public function testRedactAccountPasswordInXml(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<command>
  <create>
    <domain:create xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>test_account</domain:account>
      <domain:account_pw>secret_account_password</domain:account_pw>
      <domain:name>example.md</domain:name>
    </domain:create>
  </create>
</command>';

        $redacted = $this->redactor->redactXml($xml);

        $this->assertStringContainsString('<domain:account_pw>***REDACTED***</domain:account_pw>', $redacted);
        $this->assertStringNotContainsString('secret_account_password', $redacted);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $redacted);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $redacted);
    }

    public function testRedactTransferCodeInXml(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<command>
  <transfer op="execute">
    <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:trcode>SECRET_TRANSFER_CODE</domain:trcode>
    </domain:transfer>
  </transfer>
</command>';

        $redacted = $this->redactor->redactXml($xml);

        $this->assertStringContainsString('<domain:trcode>***REDACTED***</domain:trcode>', $redacted);
        $this->assertStringNotContainsString('SECRET_TRANSFER_CODE', $redacted);
    }

    public function testRedactMultipleSensitiveFields(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <login>
      <clID>test_client</clID>
      <pw>secret_password</pw>
    </login>
  </command>
</epp>
<command>
  <create>
    <domain:create xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>test_account</domain:account>
      <domain:account_pw>secret_account_password</domain:account_pw>
      <domain:name>example.md</domain:name>
    </domain:create>
  </create>
</command>';

        $redacted = $this->redactor->redactXml($xml);

        $this->assertStringContainsString('<pw>***REDACTED***</pw>', $redacted);
        $this->assertStringContainsString('<domain:account_pw>***REDACTED***</domain:account_pw>', $redacted);
        $this->assertStringNotContainsString('secret_password', $redacted);
        $this->assertStringNotContainsString('secret_account_password', $redacted);
        $this->assertStringContainsString('<clID>test_client</clID>', $redacted);
        $this->assertStringContainsString('<domain:account>test_account</domain:account>', $redacted);
        $this->assertStringContainsString('<domain:name>example.md</domain:name>', $redacted);
    }

    public function testRedactArrayData(): void
    {
        $data = [
            'clID' => 'test_client',
            'pw' => 'secret_password',
            'account' => 'test_account',
            'account_pw' => 'secret_account_password',
            'trcode' => 'SECRET_TRANSFER_CODE',
            'name' => 'example.md',
            'nested' => [
                'pw' => 'nested_password',
                'normal_field' => 'normal_value'
            ]
        ];

        $redacted = $this->redactor->redactArray($data);

        $this->assertEquals('***REDACTED***', $redacted['pw']);
        $this->assertEquals('***REDACTED***', $redacted['account_pw']);
        $this->assertEquals('***REDACTED***', $redacted['trcode']);
        $this->assertEquals('test_client', $redacted['clID']);
        $this->assertEquals('test_account', $redacted['account']);
        $this->assertEquals('example.md', $redacted['name']);
        $this->assertEquals('***REDACTED***', $redacted['nested']['pw']);
        $this->assertEquals('normal_value', $redacted['nested']['normal_field']);
    }

    public function testRedactEmptyXml(): void
    {
        $redacted = $this->redactor->redactXml('');
        $this->assertEquals('', $redacted);
    }

    public function testRedactInvalidXml(): void
    {
        $invalidXml = 'This is not valid XML';
        $redacted = $this->redactor->redactXml($invalidXml);
        $this->assertEquals($invalidXml, $redacted);
    }

    public function testRedactXmlWithNoSensitiveData(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.md</domain:name>
      </domain:check>
    </check>
  </command>
</epp>';

        $redacted = $this->redactor->redactXml($xml);

        $this->assertEquals($xml, $redacted);
    }
}
