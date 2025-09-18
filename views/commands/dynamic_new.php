<div id="commandCards" class="row">
    <!-- Login Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('login')">
            <div class="card-body">
                <h5 class="card-title">Login</h5>
                <p class="card-text">Establish a session with the EPP server</p>
            </div>
        </div>
    </div>

    <!-- Logout Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('logout')">
            <div class="card-body">
                <h5 class="card-title">Logout</h5>
                <p class="card-text">End a session with the EPP server</p>
            </div>
        </div>
    </div>

    <!-- Check Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('check')">
            <div class="card-body">
                <h5 class="card-title">Check Domain Availability</h5>
                <p class="card-text">Check if domain names are available</p>
            </div>
        </div>
    </div>

    <!-- Create Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('create')">
            <div class="card-body">
                <h5 class="card-title">Create Domain</h5>
                <p class="card-text">Create a new domain registration</p>
            </div>
        </div>
    </div>

    <!-- Update Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('update')">
            <div class="card-body">
                <h5 class="card-title">Update Domain</h5>
                <p class="card-text">Update domain information</p>
            </div>
        </div>
    </div>

    <!-- Info Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('info')">
            <div class="card-body">
                <h5 class="card-title">Domain Info</h5>
                <p class="card-text">Retrieve domain details</p>
            </div>
        </div>
    </div>

    <!-- Renew Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('renew')">
            <div class="card-body">
                <h5 class="card-title">Renew Domain</h5>
                <p class="card-text">Renew a domain before expiry</p>
            </div>
        </div>
    </div>

    <!-- Delete Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('delete')">
            <div class="card-body">
                <h5 class="card-title">Delete Domain</h5>
                <p class="card-text">Delete a domain</p>
            </div>
        </div>
    </div>

    <!-- Transfer Request Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('transferRequest')">
            <div class="card-body">
                <h5 class="card-title">Transfer Request</h5>
                <p class="card-text">Request domain transfer</p>
            </div>
        </div>
    </div>

    <!-- Transfer Execute Command -->
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card command-card h-100" onclick="selectCommand('transferExecute')">
            <div class="card-body">
                <h5 class="card-title">Transfer Execute</h5>
                <p class="card-text">Execute domain transfer with codes</p>
            </div>
        </div>
    </div>
</div>

<div id="commandForm" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 id="commandTitle">Command</h2>
            <p id="commandDescription" class="command-description"></p>
        </div>
        <button type="button" class="btn btn-outline-secondary" onclick="goBack()">
            ← Back to Commands
        </button>
    </div>
    
    <div class="form-section">
        <form id="eppForm">
            <!-- Login Form -->
            <div id="loginForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="login_clID" class="form-label">Client ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="login_clID" name="clID" required>
                </div>
                <div class="form-group mb-3">
                    <label for="login_pw" class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="login_pw" name="pw" required>
                </div>
            </div>

            <!-- Check Form -->
            <div id="checkForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label class="form-label">Domain Names <span class="text-danger">*</span></label>
                    <div class="array-field" data-field="domains">
                        <div class="array-items"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addArrayItem('domains')">
                            Add Domain
                        </button>
                    </div>
                </div>
            </div>

            <!-- Create Form -->
            <div id="createForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="create_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="create_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_name" class="form-label">Domain Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_name" name="name" required>
                    <div class="field-attributes">
                        <label for="create_years" class="form-label">Years</label>
                        <input type="number" class="form-control" id="create_years" name="years" required>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="create_adm_orgname" class="form-label">Admin Organization Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_adm_orgname" name="adm_orgname" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_adm_firstname" class="form-label">Admin First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_adm_firstname" name="adm_firstname" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_adm_lastname" class="form-label">Admin Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_adm_lastname" name="adm_lastname" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_adm_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="create_adm_email" name="adm_email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_adm_type" class="form-label">Admin Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="create_adm_type" name="adm_type" required>
                        <option value="">Select...</option>
                        <option value="organization">Organization</option>
                        <option value="individual">Individual</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="create_adm_taxid" class="form-label">Admin Tax ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_adm_taxid" name="adm_taxid" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_ns1_name" class="form-label">Nameserver 1 Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_ns1_name" name="ns1_name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_ns1_ip" class="form-label">Nameserver 1 IP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_ns1_ip" name="ns1_ip" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_ns2_name" class="form-label">Nameserver 2 Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_ns2_name" name="ns2_name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="create_ns2_ip" class="form-label">Nameserver 2 IP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_ns2_ip" name="ns2_ip" required>
                </div>
            </div>

            <!-- Update Form -->
            <div id="updateForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="update_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="update_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="update_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="update_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label for="update_name" class="form-label">Domain Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="update_name" name="name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="update_bil_email" class="form-label">Billing Email</label>
                    <input type="email" class="form-control" id="update_bil_email" name="bil_email">
                </div>
                <div class="form-group mb-3">
                    <label for="update_ns1_name" class="form-label">Nameserver 1 Name</label>
                    <input type="text" class="form-control" id="update_ns1_name" name="ns1_name">
                </div>
                <div class="form-group mb-3">
                    <label for="update_ns2_name" class="form-label">Nameserver 2 Name</label>
                    <input type="text" class="form-control" id="update_ns2_name" name="ns2_name">
                </div>
                <div class="form-group mb-3">
                    <label for="update_ns3_name" class="form-label">Nameserver 3 Name</label>
                    <input type="text" class="form-control" id="update_ns3_name" name="ns3_name">
                </div>
            </div>

            <!-- Info Form -->
            <div id="infoForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="info_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="info_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="info_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="info_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label for="info_name" class="form-label">Domain Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="info_name" name="name" required>
                </div>
            </div>

            <!-- Renew Form -->
            <div id="renewForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="renew_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="renew_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="renew_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="renew_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label for="renew_name" class="form-label">Domain Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="renew_name" name="name" required>
                </div>
                <div class="form-group mb-3">
                    <label for="renew_curexp" class="form-label">Current Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="renew_curexp" name="curexp" required>
                </div>
                <div class="form-group mb-3">
                    <label for="renew_years" class="form-label">Years to Renew <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="renew_years" name="years" required>
                </div>
            </div>

            <!-- Delete Form -->
            <div id="deleteForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="delete_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="delete_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="delete_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="delete_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Domain Names <span class="text-danger">*</span></label>
                    <div class="array-field" data-field="domains">
                        <div class="array-items"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addArrayItem('domains')">
                            Add Domain
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transfer Request Form -->
            <div id="transferRequestForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="transferRequest_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="transferRequest_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="transferRequest_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="transferRequest_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Domain Names <span class="text-danger">*</span></label>
                    <div class="array-field" data-field="domains">
                        <div class="array-items"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addArrayItem('domains')">
                            Add Domain
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transfer Execute Form -->
            <div id="transferExecuteForm" class="command-form" style="display: none;">
                <div class="form-group mb-3">
                    <label for="transferExecute_account" class="form-label">Account <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="transferExecute_account" name="account" required>
                </div>
                <div class="form-group mb-3">
                    <label for="transferExecute_account_pw" class="form-label">Account Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="transferExecute_account_pw" name="account_pw" required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Transfer Codes <span class="text-danger">*</span></label>
                    <div class="array-field" data-field="codes">
                        <div class="array-items"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addArrayItem('codes')">
                            Add Transfer Code
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-primary" onclick="submitCommand()">
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    Execute Command
                </button>
                <button type="button" class="btn btn-demo" onclick="fillDemoData()">Fill Demo</button>
                <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
            </div>
        </form>
    </div>
</div>

<div id="results" style="display: none;">
    <!-- Results will be displayed here -->
</div>
