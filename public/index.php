<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Epp\EppMdClient;
use App\Epp\Exceptions\EppException;

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Load configuration
$config = require __DIR__ . '/../config/epp.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $_GET['action'];

        // Debug logging
        error_log("EPP Command: $action");
        error_log("Input data: " . json_encode($input));

        // Use user's configuration if provided, otherwise use default config
        $userConfig = $config;
        if (isset($input['_epp_config']) && is_array($input['_epp_config'])) {
            $userConfig = array_merge($config, $input['_epp_config']);
            // Remove the config from input data
            unset($input['_epp_config']);
        }

        $client = new EppMdClient($userConfig);
        $result = executeCommand($client, $action, $input);

        echo json_encode($result);
    } catch (Exception $e) {
        error_log("EPP Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage(),
            'type' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
    exit;
}

// Render the main page
$content = renderMainPage();
include __DIR__ . '/../views/layout.php';

function executeCommand(EppMdClient $client, string $action, array $input): array
{
    $startTime = microtime(true);
    
    try {
        switch ($action) {
            case 'login':
                $result = $client->login();
                break;
                
            case 'logout':
                $result = $client->logout();
                break;
                
            case 'check':
                $domains = $input['domains'] ?? [];
                $result = $client->check($domains);
                break;
                
            case 'create':
                $result = $client->create($input);
                break;
                
            case 'update':
                $result = $client->update($input);
                break;
                
            case 'info':
                $domain = $input['name'] ?? '';
                if (empty($domain)) {
                    throw new Exception('Domain name is required for info command');
                }
                $result = $client->info($domain);
                break;
                
            case 'renew':
                $domain = $input['name'] ?? '';
                $currentExpiry = $input['curexp'] ?? '';
                $years = (int) ($input['years'] ?? 1);
                $result = $client->renew($domain, $currentExpiry, $years);
                break;
                
            case 'delete':
                $domains = $input['domains'] ?? [];
                $result = $client->delete($domains);
                break;
                
            case 'transferRequest':
                $domains = $input['domains'] ?? [];
                $result = $client->transferRequest($domains);
                break;
                
            case 'transferExecute':
                $codes = $input['codes'] ?? [];
                $result = $client->transferExecute($codes);
                break;
                
            default:
                throw new Exception("Unknown command: {$action}");
        }
        
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        return [
            'success' => true,
            'duration_ms' => $durationMs,
            'clTRID' => $result['clTRID'] ?? 'N/A',
            'result_code' => $result['code'] ?? 'N/A',
            'result_msg' => $result['message'] ?? 'N/A',
            'parsed_data' => $result['data'] ?? [],
            'request_xml' => $result['request_xml'] ?? 'N/A',
            'response_xml' => $result['raw_xml'] ?? 'N/A'
        ];
        
    } catch (EppException $e) {
        $durationMs = (int) ((microtime(true) - $startTime) * 1000);
        
        return [
            'success' => false,
            'duration_ms' => $durationMs,
            'error' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'error_type' => get_class($e)
        ];
    }
}

function renderMainPage(): string
{
    ob_start();
    include __DIR__ . '/../views/commands/dynamic.php';
    return ob_get_clean();
}
