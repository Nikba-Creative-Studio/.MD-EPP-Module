// EPP Sandbox JavaScript
let currentCommand = null;

// Command titles and descriptions
const commandInfo = {
    login: { title: 'Login', description: 'Establish a session with the EPP server' },
    logout: { title: 'Logout', description: 'End a session with the EPP server' },
    check: { title: 'Check Domain Availability', description: 'Check if domain names are available' },
    create: { title: 'Create Domain', description: 'Create a new domain registration' },
    update: { title: 'Update Domain', description: 'Update domain information' },
    info: { title: 'Domain Info', description: 'Retrieve domain details' },
    renew: { title: 'Renew Domain', description: 'Renew a domain before expiry' },
    delete: { title: 'Delete Domain', description: 'Delete a domain' },
    transferRequest: { title: 'Transfer Request', description: 'Request domain transfer' },
    transferExecute: { title: 'Transfer Execute', description: 'Execute domain transfer with codes' }
};

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    // Load saved form data
    loadSavedFormData();
    
    // Load saved configuration
    loadSavedConfiguration();
});

// Toast Notification System
function showToast(message, type = 'info', duration = 5000) {
    const toastContainer = document.getElementById('toastContainer');
    const toastId = 'toast-' + Date.now();
    
    const toastHtml = `
        <div class="toast toast-${type}" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">
                    ${getToastIcon(type)} ${getToastTitle(type)}
                </strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: duration
    });
    
    toast.show();
    
    // Remove toast element after it's hidden
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

function getToastIcon(type) {
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };
    return icons[type] || icons.info;
}

function getToastTitle(type) {
    const titles = {
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Information'
    };
    return titles[type] || titles.info;
}

// Form Validation with Better Feedback
function validateForm(formData) {
    let isValid = true;
    const errors = [];
    
    console.log('Validating form for current command:', currentCommand);
    
    // Clear previous validation states
    clearAllValidationStates();
    
    // Only validate the currently selected command's form
    const currentCommandForm = document.getElementById(currentCommand + 'Form');
    console.log('Found command form:', currentCommandForm);
    
    if (!currentCommandForm) {
        console.error('No command form found for:', currentCommand);
        showToast('No command selected. Please select a command first.', 'warning');
        return false;
    }
    
    // Check required fields only in the current command's form
    const requiredInputs = currentCommandForm.querySelectorAll('input[required], select[required], textarea[required]');
    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            const fieldName = getFieldDisplayName(input);
            errors.push(`${fieldName} is required`);
            setFieldInvalid(input, `${fieldName} is required`);
            isValid = false;
        } else {
            setFieldValid(input);
        }
    });
    
    // Check array fields only in the current command's form
    const arrayFields = currentCommandForm.querySelectorAll('.array-field');
    arrayFields.forEach(arrayField => {
        const fieldName = arrayField.getAttribute('data-field');
        const inputs = arrayField.querySelectorAll('input[name*="[]"]');
        const values = Array.from(inputs).map(input => input.value).filter(value => value.trim() !== '');
        
        if (values.length === 0) {
            const displayName = getFieldDisplayName(arrayField);
            errors.push(`${displayName} is required`);
            setArrayFieldInvalid(arrayField, `${displayName} is required`);
            isValid = false;
        } else {
            setArrayFieldValid(arrayField);
        }
    });
    
    // Show validation errors
    if (!isValid) {
        showToast(`Form validation failed:<br>• ${errors.join('<br>• ')}`, 'error', 8000);
    }
    
    return isValid;
}

function getFieldDisplayName(element) {
    const label = element.previousElementSibling;
    if (label && label.tagName === 'LABEL') {
        return label.textContent.replace('*', '').trim();
    }
    return element.name || element.getAttribute('data-field') || 'Field';
}

function setFieldInvalid(input, message) {
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    
    // Remove existing feedback
    const existingFeedback = input.parentElement.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Add new feedback
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.textContent = message;
    input.parentElement.appendChild(feedback);
}

function setFieldValid(input) {
    input.classList.add('is-valid');
    input.classList.remove('is-invalid');
    
    // Remove existing feedback
    const existingFeedback = input.parentElement.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
}

function setArrayFieldInvalid(arrayField, message) {
    arrayField.classList.add('is-invalid');
    arrayField.classList.remove('is-valid');
    
    // Remove existing feedback
    const existingFeedback = arrayField.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
    
    // Add new feedback
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.textContent = message;
    arrayField.appendChild(feedback);
}

function setArrayFieldValid(arrayField) {
    arrayField.classList.add('is-valid');
    arrayField.classList.remove('is-invalid');
    
    // Remove existing feedback
    const existingFeedback = arrayField.querySelector('.invalid-feedback');
    if (existingFeedback) {
        existingFeedback.remove();
    }
}

function clearAllValidationStates() {
    // Only clear validation states for the current command
    const currentCommandForm = document.getElementById(currentCommand + 'Form');
    if (!currentCommandForm) return;
    
    // Clear input validation states
    currentCommandForm.querySelectorAll('.is-invalid, .is-valid').forEach(element => {
        element.classList.remove('is-invalid', 'is-valid');
    });
    
    // Remove all feedback messages
    currentCommandForm.querySelectorAll('.invalid-feedback, .valid-feedback').forEach(feedback => {
        feedback.remove();
    });
}

// Select a command and show its form
function selectCommand(commandKey) {
    console.log('Selecting command:', commandKey);
    currentCommand = commandKey;
    console.log('Current command set to:', currentCommand);
    const info = commandInfo[commandKey];
    
    // Update UI
    document.getElementById('commandTitle').textContent = info.title;
    document.getElementById('commandDescription').textContent = info.description;
    document.getElementById('commandForm').style.display = 'block';
    document.getElementById('commandCards').style.display = 'none';
    
    // Hide all forms
    document.querySelectorAll('.command-form').forEach(form => {
        form.style.display = 'none';
    });
    
    // Show the selected form
    const selectedForm = document.getElementById(commandKey + 'Form');
    if (selectedForm) {
        selectedForm.style.display = 'block';
    }
    
    // Load saved data for this command
    loadSavedFormData(commandKey);
}

// Go back to command selection
function goBack() {
    currentCommand = null;
    document.getElementById('commandForm').style.display = 'none';
    document.getElementById('commandCards').style.display = 'block';
    document.getElementById('results').style.display = 'none';
}


// Add array item
function addArrayItem(fieldName) {
    const container = document.querySelector(`[data-field="${fieldName}"] .array-items`);
    if (!container) return;
    
    const itemDiv = document.createElement('div');
    itemDiv.className = 'array-item';
    itemDiv.innerHTML = `
        <input type="text" class="form-control" name="${fieldName}[]" placeholder="Enter ${fieldName.slice(0, -1)}">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeArrayItem(this)">
            Remove
        </button>
    `;
    
    container.appendChild(itemDiv);
}

// Remove array item
function removeArrayItem(button) {
    button.parentElement.remove();
}

// Fill demo data
function fillDemoData() {
    if (!currentCommand) return;
    
    const demoData = {
        login: {
            'login_clID': 'ID',
            'login_pw': 'PASS'
        },
        check: {
            domains: ['domain1.md', 'nic.md']
        },
        create: {
            'create_account': 'USER_NAME',
            'create_account_pw': 'PASSWORD',
            'create_name': 'domain1.md',
            'create_years': '2',
            'create_adm_orgname': 'MY SRL com',
            'create_adm_firstname': 'Frunza',
            'create_adm_lastname': 'Ion',
            'create_adm_email': 'hm@nic.md',
            'create_adm_type': 'organization',
            'create_adm_taxid': '123456789764',
            'create_ns1_name': 'ns1.dns.md',
            'create_ns1_ip': '1.2.3.4',
            'create_ns2_name': 'ns2.dns.md',
            'create_ns2_ip': '1.2.3.4'
        },
        update: {
            'update_account': 'USER_NAME',
            'update_account_pw': 'PASSWORD',
            'update_name': 'domain1.md',
            'update_bil_email': 'hm@nic.md',
            'update_ns1_name': 'ns1.dns.md',
            'update_ns2_name': 'ns2.dns.md',
            'update_ns3_name': 'ns3.dns.md'
        },
        info: {
            'info_account': 'USER_NAME',
            'info_account_pw': 'PASSWORD',
            'info_name': 'domain1.md'
        },
        renew: {
            'renew_account': 'USER_NAME',
            'renew_account_pw': 'PASSWORD',
            'renew_name': 'domain1.md',
            'renew_curexp': '2024-12-31',
            'renew_years': '2'
        },
        delete: {
            'delete_account': 'USER_NAME',
            'delete_account_pw': 'PASSWORD',
            domains: ['domain1.md']
        },
        transferRequest: {
            'transferRequest_account': 'USER_NAME',
            'transferRequest_account_pw': 'PASSWORD',
            domains: ['domain1.md']
        },
        transferExecute: {
            'transferExecute_account': 'USER_NAME',
            'transferExecute_account_pw': 'PASSWORD',
            codes: ['TRANSFER_CODE_123']
        }
    };
    
    const data = demoData[currentCommand];
    if (!data) return;
    
    Object.keys(data).forEach(key => {
        if (Array.isArray(data[key])) {
            // Handle array fields
            fillArrayDemo(key, data[key]);
        } else {
            // Handle single fields
            const element = document.getElementById(key);
            if (element) {
                element.value = data[key];
            }
        }
    });
    
    saveFormData();
    showToast('Demo data filled successfully!', 'success', 3000);
}

// Fill array demo data
function fillArrayDemo(fieldName, demoValues) {
    const container = document.querySelector(`[data-field="${fieldName}"] .array-items`);
    if (!container) return;
    
    container.innerHTML = '';
    
    demoValues.forEach(demoValue => {
        addArrayItem(fieldName);
        const inputs = container.querySelectorAll(`input[name="${fieldName}[]"]`);
        const lastInput = inputs[inputs.length - 1];
        if (lastInput) {
            lastInput.value = demoValue;
        }
    });
}

// Reset form
function resetForm() {
    const form = document.getElementById('eppForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.value = '';
    });
    
    // Clear array fields
    const arrayFields = form.querySelectorAll('.array-field .array-items');
    arrayFields.forEach(container => {
        container.innerHTML = '';
    });
    
    // Clear validation states
    clearAllValidationStates();
    
    saveFormData();
    showToast('Form reset successfully!', 'info', 3000);
}

// Configuration Management
let eppConfiguration = {};

function loadSavedConfiguration() {
    const saved = localStorage.getItem('eppConfiguration');
    if (saved) {
        try {
            eppConfiguration = JSON.parse(saved);
            updateConfigurationDisplay();
            populateConfigurationForm();
        } catch (e) {
            console.error('Error loading saved configuration:', e);
        }
    }
}

function saveConfiguration() {
    const form = document.getElementById('configForm');
    const formData = new FormData(form);
    
    // Validate required fields
    const baseUrl = formData.get('base_url');
    const clientId = formData.get('client_id');
    const password = formData.get('password');
    
    if (!baseUrl || !clientId || !password) {
        showToast('Please fill in all required fields (URL, Client ID, Password)', 'error');
        return;
    }
    
    // Build configuration object
    eppConfiguration = {
        base_url: baseUrl,
        client_id: clientId,
        password: password,
        account: formData.get('account') || '',
        account_password: formData.get('account_password') || '',
        verify_ssl: formData.get('verify_ssl') === 'true',
        connect_timeout: parseInt(formData.get('connect_timeout')) || 30,
        read_timeout: parseInt(formData.get('read_timeout')) || 60
    };
    
    // Save to localStorage
    localStorage.setItem('eppConfiguration', JSON.stringify(eppConfiguration));
    
    // Update display
    updateConfigurationDisplay();
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('configModal'));
    modal.hide();
    
    showToast('Configuration saved successfully!', 'success');
}

function updateConfigurationDisplay() {
    const serverUrlElement = document.getElementById('currentServerUrl');
    if (eppConfiguration.base_url) {
        serverUrlElement.textContent = eppConfiguration.base_url;
        serverUrlElement.className = 'text-success';
    } else {
        serverUrlElement.textContent = 'Not configured';
        serverUrlElement.className = 'text-muted';
    }
}

function populateConfigurationForm() {
    if (!eppConfiguration.base_url) return;
    
    document.getElementById('eppBaseUrl').value = eppConfiguration.base_url || '';
    document.getElementById('eppClientId').value = eppConfiguration.client_id || '';
    document.getElementById('eppPassword').value = eppConfiguration.password || '';
    document.getElementById('eppAccount').value = eppConfiguration.account || '';
    document.getElementById('eppAccountPassword').value = eppConfiguration.account_password || '';
    document.getElementById('verifySsl').value = eppConfiguration.verify_ssl ? 'true' : 'false';
    document.getElementById('connectTimeout').value = eppConfiguration.connect_timeout || 30;
    document.getElementById('readTimeout').value = eppConfiguration.read_timeout || 60;
}

function clearConfiguration() {
    eppConfiguration = {};
    localStorage.removeItem('eppConfiguration');
    updateConfigurationDisplay();
    document.getElementById('configForm').reset();
    showToast('Configuration cleared!', 'info');
}

// Submit command
async function submitCommand() {
    if (!currentCommand) {
        showToast('No command selected. Please select a command first.', 'warning');
        return;
    }
    
    // Check if EPP server is configured
    if (!eppConfiguration.base_url) {
        showToast('Please configure your EPP server first!', 'error', 5000);
        const modal = new bootstrap.Modal(document.getElementById('configModal'));
        modal.show();
        return;
    }
    
    console.log('Submitting command:', currentCommand);
    
    const formData = collectFormData();
    console.log('Form data after collection:', formData);
    
    if (!validateForm(formData)) {
        console.error('Form validation failed');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[onclick="submitCommand()"]');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    submitBtn.disabled = true;
    spinner.classList.remove('d-none');
    
    // Show loading toast
    showToast('Executing EPP command...', 'info', 2000);
    
    try {
        const url = `index.php?action=${currentCommand}`;
        console.log('Making request to:', url);
        console.log('Request body:', JSON.stringify(formData));
        
        // Include configuration in the request
        const requestData = {
            ...formData,
            _epp_config: eppConfiguration
        };
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response data:', result);
        
        if (result.error) {
            showToast(`Command failed: ${result.error}`, 'error', 8000);
            displayError(result.error);
        } else if (result.success === false) {
            showToast(`Command failed: ${result.error || 'Unknown error'}`, 'error', 8000);
            displayError(result.error || 'Unknown error');
        } else {
            showToast(`Command executed successfully! Duration: ${result.duration_ms}ms`, 'success', 4000);
            displayResults(result);
        }
        
    } catch (error) {
        console.error('Request failed:', error);
        showToast(`Network error: ${error.message}`, 'error', 8000);
        displayError('Network error: ' + error.message);
    } finally {
        // Hide loading state
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
    }
}

// Collect form data
function collectFormData() {
    const formData = {};
    
    // Only collect data from the current command's form
    const currentCommandForm = document.getElementById(currentCommand + 'Form');
    if (!currentCommandForm) {
        console.error('No current command form found for:', currentCommand);
        return formData;
    }

    // Collect regular fields
    const inputs = currentCommandForm.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.name && !input.name.includes('[]')) {
            // Remove command prefix from field names (e.g., 'login_clID' -> 'clID')
            const fieldName = input.name.replace(/^(login_|create_|update_|info_|renew_|delete_|transferRequest_|transferExecute_)/, '');
            formData[fieldName] = input.value;
        }
    });

    // Collect array fields
    const arrayFields = currentCommandForm.querySelectorAll('.array-field');
    arrayFields.forEach(arrayField => {
        const fieldName = arrayField.getAttribute('data-field');
        const inputs = arrayField.querySelectorAll('input[name*="[]"]');
        formData[fieldName] = Array.from(inputs).map(input => input.value).filter(value => value.trim() !== '');
    });

    console.log('Collected form data:', formData); // Debug log
    return formData;
}



// Display results
function displayResults(result) {
    const resultsContainer = document.getElementById('results');
    resultsContainer.style.display = 'block';
    
    // Timing info
    const timingHtml = `
        <div class="timing-info">
            <strong>Duration:</strong> ${result.duration_ms}ms | 
            <strong>clTRID:</strong> ${result.clTRID} | 
            <strong>Result Code:</strong> ${result.result_code} | 
            <strong>Message:</strong> ${result.result_msg || 'N/A'}
        </div>
    `;
    
    // Results tabs
    const resultsHtml = `
        <div class="result-section">
            ${timingHtml}
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#parsed-tab" type="button">
                        Parsed Result
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#request-tab" type="button">
                        Request XML
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#response-tab" type="button">
                        Response XML
                    </button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="parsed-tab">
                    <div class="xml-content">${formatJson(result.parsed_data || {})}</div>
                </div>
                <div class="tab-pane fade" id="request-tab">
                    <div class="xml-content">${escapeHtml(result.request_xml || '')}</div>
                </div>
                <div class="tab-pane fade" id="response-tab">
                    <div class="xml-content">${escapeHtml(result.response_xml || '')}</div>
                </div>
            </div>
        </div>
    `;
    
    resultsContainer.innerHTML = resultsHtml;
    
    // Scroll to results
    resultsContainer.scrollIntoView({ behavior: 'smooth' });
}

// Display error
function displayError(message) {
    const resultsContainer = document.getElementById('results');
    resultsContainer.style.display = 'block';
    resultsContainer.innerHTML = `
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Error</h4>
            <p>${escapeHtml(message)}</p>
        </div>
    `;
    
    resultsContainer.scrollIntoView({ behavior: 'smooth' });
}

// Format JSON for display
function formatJson(obj) {
    return JSON.stringify(obj, null, 2);
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Save form data to localStorage
function saveFormData() {
    if (!currentCommand) return;
    
    const formData = collectFormData();
    localStorage.setItem(`epp_form_${currentCommand}`, JSON.stringify(formData));
}

// Load saved form data
function loadSavedFormData(commandKey = null) {
    const targetCommand = commandKey || currentCommand;
    if (!targetCommand) return;
    
    const saved = localStorage.getItem(`epp_form_${targetCommand}`);
    if (!saved) return;
    
    try {
        const formData = JSON.parse(saved);
        populateForm(formData);
    } catch (error) {
        console.error('Failed to load saved form data:', error);
    }
}

// Populate form with data
function populateForm(formData) {
    Object.keys(formData).forEach(key => {
        const value = formData[key];
        
        if (Array.isArray(value)) {
            // Handle array fields
            const container = document.querySelector(`[data-field="${key}"] .array-items`);
            if (container) {
                container.innerHTML = '';
                value.forEach(itemValue => {
                    addArrayItem(key);
                    const inputs = container.querySelectorAll(`input[name="${key}[]"]`);
                    const lastInput = inputs[inputs.length - 1];
                    if (lastInput) {
                        lastInput.value = itemValue;
                    }
                });
            }
        } else {
            // Handle single fields
            const element = document.querySelector(`[name="${key}"]`);
            if (element) {
                element.value = value;
            }
        }
    });
}

// Auto-save form data on input change
document.addEventListener('input', function(e) {
    if (e.target.matches('input, select, textarea')) {
        saveFormData();
    }
});
