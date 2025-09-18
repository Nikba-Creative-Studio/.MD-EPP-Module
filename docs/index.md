# .MD EPP Server Description v1.4

## Contents

* Short Glossary
* 1. Introduction
* 2. Session Commands

  * 2.1. Login
  * 2.2. Logout
* 3. Object Commands (Query Commands)

  * 3.1. Domain Objects
  * 3.2. EPP `<check>` Command
  * 3.3. EPP `<create>` Command
  * 3.4. EPP `<update>` Command
  * 3.5. EPP `<info>` Command
  * 3.6. EPP `<renew>` Command
  * 3.7. EPP `<delete>` Command
  * 3.8. EPP `<transfer>` Command
* 4. Result Codes
* Appendix A. Tag Name Description

---

## Short Glossary

**Registry**: A domain name registry is part of the DNS which converts domain names to IP addresses. It manages domain registration, policies, and operations for its TLD.

**Registrar**: An organization accredited by a ccTLD registry to register domain names.

**Registrant**: The person/entity owning the domain according to the registry records. They can transfer their domain to another registrar.

**EPP**: Extensible Provisioning Protocol, an XML-based protocol for communication between registries and registrars.

---

## 1. Introduction

The `.MD` EPP server supports the Extensible Provisioning Protocol (EPP), as defined in RFCs:

* RFC 3730: EPP
* RFC 3731: Domain Name Mapping
* RFC 3732: Host Mapping
* RFC 3733: Contact Mapping
* RFC 3734: Transport
* RFC 3735: Extension Guidelines

EPP commands are categorized into **session commands** and **object commands**.

* **Session Commands**: Login, logout.
* **Object Commands**: Operations on domains, contacts, and hosts.

---

## 2. Session Commands

### 2.1 Login

Used to establish a session with the EPP server.

**Client `<login>` command:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
  <command>
    <login>
      <clID>ID</clID>
      <pw>PASS</pw>
      <options/>
      <svcs/>
    </login>
    <clTRID/>
  </command>
</epp>
```

**Server `<login>` response:**

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
  <response>
    <result code="1000">
      <msg>User ID was authenticated. Welcome.</msg>
    </result>
    <trID>
      <clTRID/>
    </trID>
  </response>
</epp>
```

### 2.2 Logout

Ends a session with the EPP server.

**Client `<logout>` command:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
  <command>
    <logout/>
    <clTRID/>
  </command>
</epp>
```

**Server `<logout>` response:**

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
  <response>
    <result code="1500">
      <msg>User logged out. Closing Connection.</msg>
    </result>
    <trID>
      <clTRID/>
    </trID>
  </response>
</epp>
```

---

## 3. Object Commands

### 3.1 Domain Objects

Contain all necessary data for domain names.

### 3.2 `<check>` Command

Checks if a domain name is available.

**Client `<check>` command:**

```xml
<?xml version="1.0" encoding="UTF-8"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
  <command>
    <check>
      <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xsi:schemaLocation="urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
        <domain:name>domain1.md</domain:name>
        <domain:name>nic.md</domain:name>
      </domain:check>
    </check>
    <clTRID>20231126093102</clTRID>
  </command>
</epp>
```

**Server `<check>` response:**

```xml
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
  <response>
    <result code="1000">
      <msg>Command completed successfully</msg>
    </result>
    <resData>
      <domain:chkData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0" xsi:schemaLocation="urn:ietf:params:xml:ns:domain-1.0 domain-1.0.xsd">
        <domain:name res="1">domain1.md</domain:name>
        <domain:name res="0">nic.md</domain:name>
      </domain:chkData>
    </resData>
    <clTRID>20231126093102</clTRID>
  </response>
</epp>
```

**Variants of response:**

* `<domain:name res="0">` → domain already exists
* `<domain:name res="1">` → available
* `<domain:name res="2">` → syntax error

### 3.3 `<create>` Command

Creates a domain object if available.

**Client `<create>` command:**

```xml
<command>
  <create>
    <domain:create xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>USER_NAME</domain:account>
      <domain:account_pw>PASSWORD</domain:account_pw>
      <domain:name years="2">domain1.md</domain:name>
      <domain:adm_orgname>MY SRL com</domain:adm_orgname>
      <domain:adm_firstname>Frunza</domain:adm_firstname>
      <domain:adm_lastname>Ion</domain:adm_lastname>
      <domain:adm_email>hm@nic.md</domain:adm_email>
      <domain:adm_type>organization</domain:adm_type>
      <domain:adm_taxid>123456789764</domain:adm_taxid>
      <domain:ns1_name>ns1.dns.md</domain:ns1_name>
      <domain:ns1_ip>1.2.3.4</domain:ns1_ip>
      <domain:ns2_name>ns2.dns.md</domain:ns2_name>
      <domain:ns2_ip>1.2.3.4</domain:ns2_ip>
    </domain:create>
  </create>
  <clTRID>20230201150751</clTRID>
</command>
```

**Server `<create>` response:**

```xml
<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:creData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md=created expiration date yyyy-mm-dd</domain:name>
      <domain:name res="0">nic.md=busy</domain:name>
    </domain:creData>
  </resData>
  <clTRID>20230201150751</clTRID>
</response>
```

**Variants of response:**

* `res="0"` → busy
* `res="1"` → created
* `res="2"` → syntax error
* `res="3"` → invalid data

### 3.4 `<update>` Command

Updates domain information.

**Client `<update>` command:**

```xml
<command>
  <update>
    <domain:update xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>USER_NAME</domain:account>
      <domain:account_pw>PASSWORD</domain:account_pw>
      <domain:name>domain1.md</domain:name>
      <domain:bil_email>hm@nic.md</domain:bil_email>
      <domain:ns1_name>ns1.dns.md</domain:ns1_name>
      <domain:ns2_name>ns2.dns.md</domain:ns2_name>
      <domain:ns3_name>ns3.dns.md</domain:ns3_name>
    </domain:update>
  </update>
  <clTRID>20230201150751</clTRID>
</command>
```

**Server `<update>` response:**

```xml
<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:creData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md=updated</domain:name>
      <domain:name res="0">nic.md=not in account</domain:name>
    </domain:creData>
  </resData>
  <clTRID>20230201150751</clTRID>
</response>
```

**Variants of response:**

* `res="0"` → not in account
* `res="1"` → updated
* `res="2"` → syntax error
* `res="3"` → invalid data
* `res="4"` → prohibited

### 3.5 `<info>` Command

Retrieves domain details.

**Client `<info>` command:**

```xml
<command>
  <info>
    <domain:info xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>USER_NAME</domain:account>
      <domain:account_pw>PASSWORD</domain:account_pw>
      <domain:name>domain1.md</domain:name>
    </domain:info>
  </info>
  <clTRID>20230201150755</clTRID>
</command>
```

**Server `<info>` response (in account):**

```xml
<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:infData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md</domain:name>
      <domain:adm_orgname>MY SRL com</domain:adm_orgname>
      <domain:adm_email>hm@nic.md</domain:adm_email>
      <domain:ns1_name>ns1.dns.md</domain:ns1_name>
      <domain:ns2_name>ns2.dns.md</domain:ns2_name>
      <domain:reg_date>YYYY-MM-DD</domain:reg_date>
      <domain:exp_date>YYYY-MM-DD</domain:exp_date>
    </domain:infData>
  </resData>
  <clTRID>20230201150755</clTRID>
</response>
```

**Server `<info>` response (not in account):**

```xml
<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:infData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="2">nic.md</domain:name>
      <domain:adm_orgname>NIC md</domain:adm_orgname>
      <domain:ns1_name>ns1.dns.md</domain:ns1_name>
      <domain:ns2_name>ns2.dns.md</domain:ns2_name>
      <domain:reg_date>YYYY-MM-DD</domain:reg_date>
      <domain:exp_date>YYYY-MM-DD</domain:exp_date>
    </domain:infData>
  </resData>
  <clTRID>20230201150755</clTRID>
</response>
```

**Variants of response:**

* `res="0"` → not exists
* `res="1"` → info
* `res="2"` → syntax error
* `res="3"` → not in account

### 3.6 `<renew>` Command

Renews a domain before expiry.

**Client `<renew>` command:**

```xml
<command>
  <renew>
    <domain:renew xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>USER_NAME</domain:account>
      <domain:account_pw>PASSWORD</domain:account_pw>
      <domain:name curexp="YYYY-MM-DD" years="2">domain1.md</domain:name>
    </domain:renew>
  </renew>
  <clTRID>20230106081712</clTRID>
</command>
```

**Server `<renew>` response:**

```xml
<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:creData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md=new expiration date YYYY-MM-DD</domain:name>
      <domain:name res="0">nic.md=not in account</domain:name>
      <domain:name res="3">domainn.md=renew must not exceed 5 years</domain:name>
    </domain:creData>
  </resData>
  <clTRID>20230106081712</clTRID>
</response>
```

### 3.7 `<delete>` Command

Deletes a domain.

**Client `<delete>` command:**

```xml
<command>
  <delete>
    <domain:delete xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:account>USER_NAME</domain:account>
      <domain:account_pw>PASSWORD</domain:account_pw>
      <domain:name>domain1.md</domain:name>
    </domain:delete>
  </delete>
  <clTRID>20231126093102</clTRID>
</command>
```

**Server `<delete>` response:**

```xml
<response>
  <result code="1000">
    <msg>Command completed successfully</msg>
  </result>
  <resData>
    <domain:chkData xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
      <domain:name res="1">domain1.md=deleted</domain:name>
      <domain:name res="0">nic.md=not in account</domain:name>
      <domain:name res="2">domainn.md=locked</domain:name>
    </domain:chkData>
  </resData>
  <clTRID>20231126093102</clTRID>
</response>
```

### 3.8 `<transfer>` Command

Transfers a
