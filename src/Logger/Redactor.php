<?php

declare(strict_types=1);

namespace App\Epp\Logger;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;

class Redactor
{
    private const REDACTED_VALUE = '***REDACTED***';
    
    private const SENSITIVE_TAGS = [
        'pw',
        'domain:account_pw',
        'domain:trcode',
    ];

    public function redactXml(string $xml): string
    {
        if (empty($xml)) {
            return $xml;
        }

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        
        // Suppress errors for malformed XML
        $oldErrorReporting = libxml_use_internal_errors(true);
        libxml_clear_errors();
        
        if (!$dom->loadXML($xml)) {
            libxml_use_internal_errors($oldErrorReporting);
            return $xml; // Return original if parsing fails
        }
        
        libxml_use_internal_errors($oldErrorReporting);
        
        $this->redactNode($dom->documentElement);
        
        return $dom->saveXML();
    }

    public function redactArray(array $data): array
    {
        $redacted = $data;
        
        foreach ($redacted as $key => $value) {
            if (in_array($key, self::SENSITIVE_TAGS, true)) {
                $redacted[$key] = self::REDACTED_VALUE;
            } elseif (is_array($value)) {
                $redacted[$key] = $this->redactArray($value);
            }
        }
        
        return $redacted;
    }

    private function redactNode(DOMNode $node): void
    {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            /** @var DOMElement $node */
            $tagName = $node->localName ?? $node->nodeName;
            
            if (in_array($tagName, self::SENSITIVE_TAGS, true)) {
                // Remove all child nodes and set text content to redacted value
                while ($node->firstChild) {
                    $node->removeChild($node->firstChild);
                }
                $node->appendChild($node->ownerDocument->createTextNode(self::REDACTED_VALUE));
            } else {
                // Recursively process child nodes
                $children = $node->childNodes;
                for ($i = 0; $i < $children->length; $i++) {
                    $this->redactNode($children->item($i));
                }
            }
        }
    }
}
