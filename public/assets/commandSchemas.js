const commandSchemas = {
    login: {
        title: 'Login',
        description: 'Establish a session with the EPP server',
        fields: [
            {
                name: 'clID',
                label: 'Client ID',
                type: 'text',
                required: true,
                demo: 'ID'
            },
            {
                name: 'pw',
                label: 'Password',
                type: 'password',
                required: true,
                demo: 'PASS'
            }
        ]
    },
    
    logout: {
        title: 'Logout',
        description: 'End a session with the EPP server',
        fields: []
    },
    
    check: {
        title: 'Check Domain Availability',
        description: 'Check if domain names are available',
        fields: [
            {
                name: 'domains',
                label: 'Domain Names',
                type: 'array',
                required: true,
                itemType: 'text',
                demo: ['domain1.md', 'nic.md']
            }
        ]
    },
    
    create: {
        title: 'Create Domain',
        description: 'Create a new domain registration',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'name',
                label: 'Domain Name',
                type: 'text',
                required: true,
                demo: 'domain1.md',
                attributes: {
                    years: {
                        label: 'Years',
                        type: 'number',
                        required: true,
                        demo: '2'
                    }
                }
            },
            {
                name: 'adm_orgname',
                label: 'Admin Organization Name',
                type: 'text',
                required: true,
                demo: 'MY SRL com'
            },
            {
                name: 'adm_firstname',
                label: 'Admin First Name',
                type: 'text',
                required: true,
                demo: 'Frunza'
            },
            {
                name: 'adm_lastname',
                label: 'Admin Last Name',
                type: 'text',
                required: true,
                demo: 'Ion'
            },
            {
                name: 'adm_email',
                label: 'Admin Email',
                type: 'email',
                required: true,
                demo: 'hm@nic.md'
            },
            {
                name: 'adm_type',
                label: 'Admin Type',
                type: 'select',
                required: true,
                options: ['organization', 'individual'],
                demo: 'organization'
            },
            {
                name: 'adm_taxid',
                label: 'Admin Tax ID',
                type: 'text',
                required: true,
                demo: '123456789764'
            },
            {
                name: 'ns1_name',
                label: 'Nameserver 1 Name',
                type: 'text',
                required: true,
                demo: 'ns1.dns.md'
            },
            {
                name: 'ns1_ip',
                label: 'Nameserver 1 IP',
                type: 'text',
                required: true,
                demo: '1.2.3.4'
            },
            {
                name: 'ns2_name',
                label: 'Nameserver 2 Name',
                type: 'text',
                required: true,
                demo: 'ns2.dns.md'
            },
            {
                name: 'ns2_ip',
                label: 'Nameserver 2 IP',
                type: 'text',
                required: true,
                demo: '1.2.3.4'
            }
        ]
    },
    
    update: {
        title: 'Update Domain',
        description: 'Update domain information',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'name',
                label: 'Domain Name',
                type: 'text',
                required: true,
                demo: 'domain1.md'
            },
            {
                name: 'bil_email',
                label: 'Billing Email',
                type: 'email',
                required: false,
                demo: 'hm@nic.md'
            },
            {
                name: 'ns1_name',
                label: 'Nameserver 1 Name',
                type: 'text',
                required: false,
                demo: 'ns1.dns.md'
            },
            {
                name: 'ns2_name',
                label: 'Nameserver 2 Name',
                type: 'text',
                required: false,
                demo: 'ns2.dns.md'
            },
            {
                name: 'ns3_name',
                label: 'Nameserver 3 Name',
                type: 'text',
                required: false,
                demo: 'ns3.dns.md'
            }
        ]
    },
    
    info: {
        title: 'Domain Info',
        description: 'Retrieve domain details',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'name',
                label: 'Domain Name',
                type: 'text',
                required: true,
                demo: 'domain1.md'
            }
        ]
    },
    
    renew: {
        title: 'Renew Domain',
        description: 'Renew a domain before expiry',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'name',
                label: 'Domain Name',
                type: 'text',
                required: true,
                demo: 'domain1.md'
            },
            {
                name: 'curexp',
                label: 'Current Expiry Date',
                type: 'date',
                required: true,
                demo: '2024-12-31'
            },
            {
                name: 'years',
                label: 'Years to Renew',
                type: 'number',
                required: true,
                demo: '2'
            }
        ]
    },
    
    delete: {
        title: 'Delete Domain',
        description: 'Delete a domain',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'domains',
                label: 'Domain Names',
                type: 'array',
                required: true,
                itemType: 'text',
                demo: ['domain1.md']
            }
        ]
    },
    
    transferRequest: {
        title: 'Transfer Request',
        description: 'Request domain transfer',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'domains',
                label: 'Domain Names',
                type: 'array',
                required: true,
                itemType: 'text',
                demo: ['domain1.md']
            }
        ]
    },
    
    transferExecute: {
        title: 'Transfer Execute',
        description: 'Execute domain transfer with codes',
        fields: [
            {
                name: 'account',
                label: 'Account',
                type: 'text',
                required: true,
                demo: 'USER_NAME'
            },
            {
                name: 'account_pw',
                label: 'Account Password',
                type: 'password',
                required: true,
                demo: 'PASSWORD'
            },
            {
                name: 'codes',
                label: 'Transfer Codes',
                type: 'array',
                required: true,
                itemType: 'text',
                demo: ['TRANSFER_CODE_123']
            }
        ]
    }
};
