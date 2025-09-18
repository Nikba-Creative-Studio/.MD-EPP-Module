# MD EPP SDK

A production-ready PHP SDK for the .MD EPP Server with Bootstrap 5 sandbox interface. This SDK implements the Extensible Provisioning Protocol (EPP) as defined in the .MD EPP Server Description v1.4.

## Features

- **Complete EPP Implementation**: All session and domain commands (login, logout, check, create, update, info, renew, delete, transfer)
- **cURL-only Transport**: Uses HTTP(S) POST requests with proper headers and SSL verification
- **Secure Logging**: Comprehensive logging system with automatic redaction of sensitive data
- **Bootstrap 5 Sandbox**: Interactive web interface with dynamic forms and demo data
- **Auto-generated Forms**: Dynamic form generation based on command schemas
- **Exception Handling**: Typed exceptions for different error scenarios
- **PHPUnit Tests**: Complete test coverage for XML builders and loggers

## Requirements

- PHP 8.2 or higher
- cURL extension
- DOM extension
- JSON extension

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd .MD-EPP-Module
```

2. Install dependencies:
```bash
composer install
```

3. Configure environment:
```bash
cp env.example .env
```

4. **IMPORTANT**: Edit `.env` with your actual EPP server credentials:
```env
EPP_BASE_URL=https://your-epp-server.com/epp
EPP_CLIENT_ID=your_actual_client_id
EPP_PASSWORD=your_actual_password
EPP_ACCOUNT=your_actual_account
EPP_ACCOUNT_PASSWORD=your_actual_account_password
```

**Note**: The default configuration points to `https://epp.nic.md/epp` which may not be accessible. You must configure your own EPP server URL and credentials for the sandbox to work properly.

## Troubleshooting

### HTTP 404 Error
If you see "HTTP error 404: Not Found" when executing commands, this means:

1. **EPP Server URL is incorrect**: Check your `EPP_BASE_URL` in the `.env` file
2. **EPP Server is not accessible**: Verify the server is running and accessible
3. **Network connectivity**: Ensure you can reach the EPP server from your environment

### Common Issues
- **Invalid credentials**: Make sure `EPP_CLIENT_ID` and `EPP_PASSWORD` are correct
- **SSL certificate issues**: Set `VERIFY_SSL=false` in `.env` for testing (not recommended for production)
- **Timeout errors**: Increase `CONNECT_TIMEOUT` and `READ_TIMEOUT` values in `.env`

## Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `EPP_BASE_URL` | EPP server URL | `https://epp.nic.md/epp` |
| `EPP_CLIENT_ID` | Client identifier | - |
| `EPP_PASSWORD` | Client password | - |
| `EPP_ACCOUNT` | Account name | - |
| `EPP_ACCOUNT_PASSWORD` | Account password | - |
| `CONNECT_TIMEOUT` | cURL connect timeout (seconds) | `30` |
| `READ_TIMEOUT` | cURL read timeout (seconds) | `60` |
| `RETRIES` | Number of retry attempts | `3` |
| `VERIFY_SSL` | Enable SSL verification | `true` |
| `CA_BUNDLE` | Path to CA bundle file | - |
| `USER_AGENT` | User agent string | `MD-EPP-SDK/1.0` |
| `LOG_EPP` | Enable EPP logging | `1` |
| `LOG_REDACT` | Enable log redaction | `1` |
| `LOG_RETAIN_DAYS` | Log retention period (days) | `14` |

### cURL Transport

The SDK uses cURL exclusively for transport with the following configuration:

- **Headers**: `Content-Type: application/xml; charset=UTF-8`, `Accept: application/xml`
- **Method**: HTTP(S) POST
- **SSL Verification**: Configurable via `VERIFY_SSL` and `CA_BUNDLE`
- **Timeouts**: Separate connect and read timeouts
- **Retries**: Automatic retry on transport failures

## Usage

### Basic Usage

```php
<?php

require_once 'vendor/autoload.php';

use App\Epp\EppMdClient;

// Load configuration
$config = require 'config/epp.php';
$client = new EppMdClient($config);

try {
    // Login
    $result = $client->login();
    echo "Login successful: " . $result['message'] . "\n";
    
    // Check domain availability
    $checkResult = $client->check(['example.md', 'test.md']);
    print_r($checkResult['data']);
    
    // Create domain
    $createData = [
        'account' => 'your_account',
        'account_pw' => 'your_password',
        'name' => 'example.md',
        'years' => 2,
        'adm_orgname' => 'Your Organization',
        'adm_firstname' => 'John',
        'adm_lastname' => 'Doe',
        'adm_email' => 'admin@example.com',
        'adm_type' => 'organization',
        'adm_taxid' => '123456789',
        'ns1_name' => 'ns1.example.com',
        'ns1_ip' => '1.2.3.4',
        'ns2_name' => 'ns2.example.com',
        'ns2_ip' => '5.6.7.8',
    ];
    $createResult = $client->create($createData);
    echo "Domain created: " . $createResult['message'] . "\n";
    
    // Logout
    $client->logout();
    
} catch (App\Epp\Exceptions\EppException $e) {
    echo "EPP Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Available Commands

#### Session Commands
- `login()` - Establish EPP session
- `logout()` - End EPP session

#### Domain Commands
- `check(array $domains)` - Check domain availability
- `create(array $payload)` - Create domain registration
- `update(array $payload)` - Update domain information
- `info(string $domain)` - Retrieve domain details
- `renew(string $domain, string $currentExpiry, int $years)` - Renew domain
- `delete(array $domains)` - Delete domain
- `transferRequest(array $domains)` - Request domain transfer
- `transferExecute(array $codes)` - Execute domain transfer

### Exception Handling

The SDK provides typed exceptions for different error scenarios:

```php
use App\Epp\Exceptions\{
    EppException,
    TransportException,
    AuthenticationException,
    SyntaxException,
    ProhibitedOperationException
};

try {
    $result = $client->login();
} catch (AuthenticationException $e) {
    // Handle authentication errors
} catch (TransportException $e) {
    // Handle network/transport errors
} catch (SyntaxException $e) {
    // Handle syntax errors
} catch (ProhibitedOperationException $e) {
    // Handle prohibited operations
} catch (EppException $e) {
    // Handle other EPP errors
}
```

## Sandbox Interface

### Running the Sandbox

Start the built-in PHP server:

```bash
php -S localhost:8080 -t public
```

Then open your browser to `http://localhost:8080`.

### Sandbox Features

- **Command Selection**: Choose from all available EPP commands
- **Dynamic Forms**: Auto-generated forms based on command schemas
- **Demo Data**: One-click demo data population using examples from documentation
- **Real-time Validation**: Client and server-side validation
- **Results Display**: Three-tab view showing parsed results, request XML, and response XML
- **Form Persistence**: Automatic saving of form data to localStorage
- **Timing Information**: Request duration, clTRID, and result codes

### Command Schemas

The sandbox uses `public/assets/commandSchemas.js` to define form structures for each command. Each schema includes:

- Field definitions with types, labels, and validation rules
- Demo data for quick testing
- Required field indicators
- Array field support for multiple values

## Logging System

### Log Structure

The logging system creates organized log files:

```
storage/logs/epp/
├── 2024-01-15/                    # Daily directories
│   ├── 2024-01-15T10:30:00+00:00-login.request.xml
│   ├── 2024-01-15T10:30:00+00:00-login.response.xml
│   └── ...
├── 2024-01-15.ndjson              # Daily summary file
└── 2024-01-16.ndjson
```

### Log Files

1. **Individual XML Files**: Separate files for each request/response pair
2. **Daily NDJSON**: One JSON object per line with exchange metadata
3. **Automatic Redaction**: Sensitive fields (`pw`, `account_pw`, `trcode`) are redacted

### Log Entry Format

```json
{
  "timestamp": "2024-01-15T10:30:00+00:00",
  "command": "login",
  "clTRID": "20240115103000123456",
  "duration_ms": 150,
  "result_code": 1000,
  "result_msg": "Command completed successfully",
  "request_path": "/path/to/request.xml",
  "response_path": "/path/to/response.xml",
  "error": null
}
```

### Log Management

- **Retention**: Configurable retention period (default 14 days)
- **Cleanup**: Automatic cleanup of old logs on startup
- **Redaction**: Automatic redaction of sensitive data in both XML and JSON logs

## Testing

Run the test suite:

```bash
composer test
```

Or run PHPUnit directly:

```bash
./vendor/bin/phpunit
```

### Test Coverage

- **XML Builder Tests**: Verify correct XML generation for all commands
- **XML Parser Tests**: Test response parsing and error handling
- **Logger Redaction Tests**: Ensure sensitive data is properly redacted
- **Exception Tests**: Verify proper exception handling

## Examples

The `examples/` directory contains sample XML files for all commands:

- `login.request.xml` / `login.response.xml`
- `logout.request.xml` / `logout.response.xml`
- `check.request.xml` / `check.response.xml`
- `create.request.xml` / `create.response.xml`
- `update.request.xml`
- `info.request.xml`
- `renew.request.xml`
- `delete.request.xml`

These examples match the documentation exactly and can be used for testing and reference.

## Security Considerations

1. **Credential Protection**: Never log raw passwords or sensitive data
2. **SSL Verification**: Always verify SSL certificates in production
3. **Log Redaction**: Sensitive fields are automatically redacted
4. **Input Validation**: All inputs are validated before processing
5. **Exception Handling**: Proper error handling prevents information leakage

## Troubleshooting

### Common Issues

1. **SSL Certificate Errors**: Set `VERIFY_SSL=false` for testing or provide proper `CA_BUNDLE`
2. **Timeout Errors**: Increase `CONNECT_TIMEOUT` and `READ_TIMEOUT` values
3. **Authentication Errors**: Verify `EPP_CLIENT_ID` and `EPP_PASSWORD` are correct
4. **XML Parsing Errors**: Check that the EPP server returns valid XML

### Debug Mode

Enable detailed logging by setting `LOG_EPP=1` in your `.env` file. Check the log files in `storage/logs/epp/` for detailed request/response information.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For issues and questions:
1. Check the troubleshooting section
2. Review the log files for error details
3. Consult the .MD EPP Server documentation
4. Open an issue on the project repository
