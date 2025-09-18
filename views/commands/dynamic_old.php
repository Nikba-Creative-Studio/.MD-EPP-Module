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
        <div id="formContainer">
            <!-- Dynamic form will be rendered here -->
        </div>
    </div>
</div>

<div id="results" style="display: none;">
    <!-- Results will be displayed here -->
</div>
