<?php

declare(strict_types=1);

namespace App\Epp;

use App\Epp\Logger\EppLogger;
use App\Epp\Exceptions\EppException;
use App\Epp\Exceptions\TransportException;
use DOMDocument;
use DOMElement;
use Exception;

class EppMdClient
{
    private array $config;
    private EppLogger $logger;
    private ?string $sessionId = null;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->logger = new EppLogger($config['logging']);
    }

    public function login(): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildLoginXml($clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'login',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function logout(): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildLogoutXml($clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'logout',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1500) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function check(array $domains): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildCheckXml($domains, $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'check',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function create(array $payload): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildCreateXml($payload, $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'create',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function update(array $payload): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildUpdateXml($payload, $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'update',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function info(string $domain): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildInfoXml($domain, $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'info',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function renew(string $domain, string $currentExpiry, int $years): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildRenewXml($domain, $currentExpiry, $years, $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'renew',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function delete(array $domains): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildDeleteXml($domains, $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'delete',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function transferRequest(array $domains): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildTransferXml($domains, 'request', $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'transfer_request',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    public function transferExecute(array $codes): array
    {
        $clTRID = $this->generateClTRID();
        $requestXml = $this->buildTransferXml($codes, 'execute', $clTRID);
        
        $startTime = microtime(true);
        $responseXml = $this->sendRequest($requestXml);
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        $result = $this->parseResponse($responseXml);
        $result['clTRID'] = $clTRID;
        $result['request_xml'] = $requestXml;
        
        $this->logger->logExchange(
            'transfer_execute',
            $clTRID,
            $requestXml,
            $responseXml,
            $durationMs,
            $result['code'],
            $result['message']
        );
        
        if ($result['code'] !== 1000) {
            throw new EppException($result['message'], $result['code']);
        }
        
        return $result;
    }

    private function sendRequest(string $xml): string
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->config['base_url'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $xml,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/xml; charset=UTF-8',
                'Accept: application/xml',
                'User-Agent: ' . $this->config['transport']['user_agent'],
            ],
            CURLOPT_CONNECTTIMEOUT => $this->config['transport']['connect_timeout'],
            CURLOPT_TIMEOUT => $this->config['transport']['read_timeout'],
            CURLOPT_SSL_VERIFYPEER => $this->config['transport']['verify_ssl'],
            CURLOPT_SSL_VERIFYHOST => $this->config['transport']['verify_ssl'] ? 2 : 0,
        ]);
        
        if (!empty($this->config['transport']['ca_bundle'])) {
            curl_setopt($ch, CURLOPT_CAINFO, $this->config['transport']['ca_bundle']);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($response === false) {
            throw new TransportException('cURL error: ' . $error);
        }
        
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new TransportException("HTTP error {$httpCode}: " . $response);
        }
        
        return $response;
    }

    private function generateClTRID(): string
    {
        return date('YmdHis') . substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 6);
    }

    private function buildLoginXml(string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $epp = $dom->createElementNS('urn:ietf:params:xml:ns:epp-1.0', 'epp');
        $epp->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');
        $dom->appendChild($epp);
        
        $command = $dom->createElement('command');
        $epp->appendChild($command);
        
        $login = $dom->createElement('login');
        $command->appendChild($login);
        
        $login->appendChild($dom->createElement('clID', $this->config['client_id']));
        $login->appendChild($dom->createElement('pw', $this->config['password']));
        $login->appendChild($dom->createElement('options'));
        $login->appendChild($dom->createElement('svcs'));
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildLogoutXml(string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $epp = $dom->createElementNS('urn:ietf:params:xml:ns:epp-1.0', 'epp');
        $epp->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');
        $dom->appendChild($epp);
        
        $command = $dom->createElement('command');
        $epp->appendChild($command);
        
        $command->appendChild($dom->createElement('logout'));
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildCheckXml(array $domains, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $epp = $dom->createElementNS('urn:ietf:params:xml:ns:epp-1.0', 'epp');
        $epp->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');
        $dom->appendChild($epp);
        
        $command = $dom->createElement('command');
        $epp->appendChild($command);
        
        $check = $dom->createElement('check');
        $command->appendChild($check);
        
        $domainCheck = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:check');
        $domainCheck->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'xsi:schemaLocation', 'urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd');
        $check->appendChild($domainCheck);
        
        foreach ($domains as $domain) {
            $domainCheck->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:name', $domain));
        }
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildCreateXml(array $payload, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $command = $dom->createElement('command');
        $dom->appendChild($command);
        
        $create = $dom->createElement('create');
        $command->appendChild($create);
        
        $domainCreate = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:create');
        $create->appendChild($domainCreate);
        
        // Add all payload fields
        foreach ($payload as $key => $value) {
            if (str_starts_with($key, 'domain:')) {
                $elementName = $key;
            } else {
                $elementName = 'domain:' . $key;
            }
            
            if (is_array($value)) {
                foreach ($value as $item) {
                    $element = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', $elementName, $item);
                    $domainCreate->appendChild($element);
                }
            } else {
                $element = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', $elementName, $value);
                $domainCreate->appendChild($element);
            }
        }
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildUpdateXml(array $payload, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $command = $dom->createElement('command');
        $dom->appendChild($command);
        
        $update = $dom->createElement('update');
        $command->appendChild($update);
        
        $domainUpdate = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:update');
        $update->appendChild($domainUpdate);
        
        // Add all payload fields
        foreach ($payload as $key => $value) {
            if (str_starts_with($key, 'domain:')) {
                $elementName = $key;
            } else {
                $elementName = 'domain:' . $key;
            }
            
            if (is_array($value)) {
                foreach ($value as $item) {
                    $element = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', $elementName, $item);
                    $domainUpdate->appendChild($element);
                }
            } else {
                $element = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', $elementName, $value);
                $domainUpdate->appendChild($element);
            }
        }
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildInfoXml(string $domain, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $command = $dom->createElement('command');
        $dom->appendChild($command);
        
        $info = $dom->createElement('info');
        $command->appendChild($info);
        
        $domainInfo = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:info');
        $info->appendChild($domainInfo);
        
        $domainInfo->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account', $this->config['account']));
        $domainInfo->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account_pw', $this->config['account_password']));
        $domainInfo->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:name', $domain));
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildRenewXml(string $domain, string $currentExpiry, int $years, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $command = $dom->createElement('command');
        $dom->appendChild($command);
        
        $renew = $dom->createElement('renew');
        $command->appendChild($renew);
        
        $domainRenew = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:renew');
        $renew->appendChild($domainRenew);
        
        $domainRenew->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account', $this->config['account']));
        $domainRenew->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account_pw', $this->config['account_password']));
        
        $nameElement = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:name', $domain);
        $nameElement->setAttribute('curexp', $currentExpiry);
        $nameElement->setAttribute('years', (string) $years);
        $domainRenew->appendChild($nameElement);
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildDeleteXml(array $domains, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $command = $dom->createElement('command');
        $dom->appendChild($command);
        
        $delete = $dom->createElement('delete');
        $command->appendChild($delete);
        
        $domainDelete = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:delete');
        $delete->appendChild($domainDelete);
        
        $domainDelete->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account', $this->config['account']));
        $domainDelete->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account_pw', $this->config['account_password']));
        
        foreach ($domains as $domain) {
            $domainDelete->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:name', $domain));
        }
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function buildTransferXml(array $data, string $operation, string $clTRID): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;
        
        $command = $dom->createElement('command');
        $dom->appendChild($command);
        
        $transfer = $dom->createElement('transfer');
        $transfer->setAttribute('op', $operation);
        $command->appendChild($transfer);
        
        $domainTransfer = $dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:transfer');
        $transfer->appendChild($domainTransfer);
        
        $domainTransfer->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account', $this->config['account']));
        $domainTransfer->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:account_pw', $this->config['account_password']));
        
        if ($operation === 'request') {
            foreach ($data as $domain) {
                $domainTransfer->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:name', $domain));
            }
        } else {
            foreach ($data as $code) {
                $domainTransfer->appendChild($dom->createElementNS('urn:ietf:params:xml:ns:domain-1.0', 'domain:trcode', $code));
            }
        }
        
        $command->appendChild($dom->createElement('clTRID', $clTRID));
        
        return $dom->saveXML();
    }

    private function parseResponse(string $xml): array
    {
        $dom = new DOMDocument();
        if (!$dom->loadXML($xml)) {
            throw new EppException('Invalid XML response');
        }
        
        $result = $dom->getElementsByTagName('result')->item(0);
        if (!$result) {
            throw new EppException('No result element in response');
        }
        
        $code = (int) $result->getAttribute('code');
        $message = $result->getElementsByTagName('msg')->item(0)?->textContent ?? '';
        
        $response = [
            'code' => $code,
            'message' => $message,
            'raw_xml' => $xml,
        ];
        
        // Parse response data if present
        $resData = $dom->getElementsByTagName('resData')->item(0);
        if ($resData) {
            $response['data'] = $this->parseResData($resData);
        }
        
        return $response;
    }

    private function parseResData(DOMElement $resData): array
    {
        $data = [];
        
        foreach ($resData->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                $data = array_merge($data, $this->parseElement($child));
            }
        }
        
        return $data;
    }

    private function parseElement(DOMElement $element): array
    {
        $data = [];
        $tagName = $element->localName ?? $element->nodeName;
        
        if ($element->hasChildNodes()) {
            $hasTextContent = false;
            $children = [];
            
            foreach ($element->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE && trim($child->textContent) !== '') {
                    $hasTextContent = true;
                    $data[$tagName] = $child->textContent;
                } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                    $children = array_merge($children, $this->parseElement($child));
                }
            }
            
            if (!$hasTextContent && !empty($children)) {
                $data = array_merge($data, $children);
            }
        } else {
            $data[$tagName] = $element->textContent;
        }
        
        // Handle res attribute for domain names
        if ($element->hasAttribute('res')) {
            $data[$tagName . '_res'] = $element->getAttribute('res');
        }
        
        return $data;
    }
}
