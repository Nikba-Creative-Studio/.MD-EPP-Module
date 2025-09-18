<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MD EPP Sandbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#" onclick="goBack()">
                <strong>MD EPP Sandbox</strong>
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text">
                    .MD EPP Server SDK v1.4
                </span>
            </div>
        </div>
    </nav>

    <!-- Configuration Modal -->
    <div class="modal fade" id="configModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="configModalLabel">EPP Server Configuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="configForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eppBaseUrl" class="form-label">EPP Server URL *</label>
                                    <input type="url" class="form-control" id="eppBaseUrl" name="base_url" 
                                           placeholder="https://epp.example.com/epp" required>
                                    <div class="form-text">The base URL of your EPP server</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eppClientId" class="form-label">Client ID *</label>
                                    <input type="text" class="form-control" id="eppClientId" name="client_id" 
                                           placeholder="your_client_id" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eppPassword" class="form-label">Password *</label>
                                    <input type="password" class="form-control" id="eppPassword" name="password" 
                                           placeholder="your_password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eppAccount" class="form-label">Account</label>
                                    <input type="text" class="form-control" id="eppAccount" name="account" 
                                           placeholder="your_account">
                                    <div class="form-text">Optional account identifier</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="eppAccountPassword" class="form-label">Account Password</label>
                                    <input type="password" class="form-control" id="eppAccountPassword" name="account_password" 
                                           placeholder="your_account_password">
                                    <div class="form-text">Optional account password</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="verifySsl" class="form-label">Verify SSL</label>
                                    <select class="form-select" id="verifySsl" name="verify_ssl">
                                        <option value="true">Yes (Recommended)</option>
                                        <option value="false">No (Testing only)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="connectTimeout" class="form-label">Connect Timeout (seconds)</label>
                                    <input type="number" class="form-control" id="connectTimeout" name="connect_timeout" 
                                           value="30" min="1" max="300">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="readTimeout" class="form-label">Read Timeout (seconds)</label>
                                    <input type="number" class="form-control" id="readTimeout" name="read_timeout" 
                                           value="60" min="1" max="600">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" onclick="clearConfiguration()">Clear</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveConfiguration()">Save Configuration</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <?php echo $content; ?>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>
</body>
</html>
