<?php

declare(strict_types=1);

namespace App\Epp\Logger;

use DateTime;
use DateTimeInterface;
use Exception;

class EppLogger
{
    private string $storagePath;
    private bool $enabled;
    private bool $redact;
    private int $retainDays;
    private Redactor $redactor;

    public function __construct(array $config)
    {
        $this->storagePath = $config['storage_path'];
        $this->enabled = $config['enabled'];
        $this->redact = $config['redact'];
        $this->retainDays = $config['retain_days'];
        $this->redactor = new Redactor();
        
        $this->ensureStorageDirectory();
        $this->cleanupOldLogs();
    }

    public function logExchange(
        string $command,
        string $clTRID,
        string $requestXml,
        string $responseXml,
        int $durationMs,
        ?int $resultCode = null,
        ?string $resultMsg = null,
        ?string $error = null
    ): void {
        if (!$this->enabled) {
            return;
        }

        $timestamp = new DateTime();
        $dateStr = $timestamp->format('Y-m-d');
        $iso8601 = $timestamp->format('c');
        
        // Create daily directory
        $dailyDir = $this->storagePath . '/' . $dateStr;
        if (!is_dir($dailyDir)) {
            mkdir($dailyDir, 0755, true);
        }
        
        // Log individual XML files
        $requestPath = $dailyDir . '/' . $iso8601 . '-' . $command . '.request.xml';
        $responsePath = $dailyDir . '/' . $iso8601 . '-' . $command . '.response.xml';
        
        // Write request (redacted if enabled)
        $requestContent = $this->redact ? $this->redactor->redactXml($requestXml) : $requestXml;
        file_put_contents($requestPath, $requestContent);
        
        // Write response (not redacted)
        file_put_contents($responsePath, $responseXml);
        
        // Log to daily NDJSON file
        $this->logToDailyFile($timestamp, $command, $clTRID, $durationMs, $resultCode, $resultMsg, $requestPath, $responsePath, $error);
    }

    private function logToDailyFile(
        DateTimeInterface $timestamp,
        string $command,
        string $clTRID,
        int $durationMs,
        ?int $resultCode,
        ?string $resultMsg,
        string $requestPath,
        string $responsePath,
        ?string $error
    ): void {
        $dateStr = $timestamp->format('Y-m-d');
        $dailyFile = $this->storagePath . '/' . $dateStr . '.ndjson';
        
        $logEntry = [
            'timestamp' => $timestamp->format('c'),
            'command' => $command,
            'clTRID' => $clTRID,
            'duration_ms' => $durationMs,
            'result_code' => $resultCode,
            'result_msg' => $resultMsg,
            'request_path' => $requestPath,
            'response_path' => $responsePath,
            'error' => $error,
        ];
        
        // Redact sensitive data in log entry
        if ($this->redact) {
            $logEntry = $this->redactor->redactArray($logEntry);
        }
        
        file_put_contents($dailyFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }

    private function ensureStorageDirectory(): void
    {
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    private function cleanupOldLogs(): void
    {
        if ($this->retainDays <= 0) {
            return;
        }

        $cutoffDate = (new DateTime())->modify("-{$this->retainDays} days");
        
        if (!is_dir($this->storagePath)) {
            return;
        }
        
        $entries = scandir($this->storagePath);
        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            
            $entryPath = $this->storagePath . '/' . $entry;
            
            // Handle daily directories (YYYY-MM-DD)
            if (is_dir($entryPath) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $entry)) {
                try {
                    $entryDate = new DateTime($entry);
                    if ($entryDate < $cutoffDate) {
                        $this->removeDirectory($entryPath);
                    }
                } catch (Exception $e) {
                    // Skip invalid date directories
                    continue;
                }
            }
            
            // Handle daily NDJSON files (YYYY-MM-DD.ndjson)
            if (is_file($entryPath) && preg_match('/^\d{4}-\d{2}-\d{2}\.ndjson$/', $entry)) {
                try {
                    $entryDate = new DateTime(substr($entry, 0, 10));
                    if ($entryDate < $cutoffDate) {
                        unlink($entryPath);
                    }
                } catch (Exception $e) {
                    // Skip invalid date files
                    continue;
                }
            }
        }
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dir . '/' . $file;
            if (is_dir($filePath)) {
                $this->removeDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }
        
        rmdir($dir);
    }
}
