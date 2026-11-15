# Quick start

> **English** | [Tiếng Việt](getting-started.vi.md)

---

Guide to installing the SDK, creating a client and making the first request to
the **HiTechCloud CRM API**.

## 1. Get application credentials

1. Sign in at `https://my.hitechcloud.vn`.
2. Go to **Company Settings → API Access** and create a new CRM application.
3. Copy the `client_id` and `client_secret`.
4. Save them to environment variables, for example:

```bash
export CRM_CLIENT_ID="YOUR_CLIENT_ID"
export CRM_CLIENT_SECRET="YOUR_CLIENT_SECRET"
```

> The access token grants access to customer data. Do not embed tokens in
> frontend code and do not commit them to public source repositories.

## 2. Install the SDK

```bash
# Node.js / TypeScript
npm install @hitechcloudvn/crm-sdk

# Python
pip install hitechcloud-crm

# PHP
composer require hitechcloudvn/crm-sdk

# Go
go get github.com/hitechcloudvn/hitechcloud-crm-sdk/go
```

```xml
<!-- Java (Maven) -->
<dependency>
  <groupId>vn.hitechcloud</groupId>
  <artifactId>crm-sdk</artifactId>
  <version>1.0.0</version>
</dependency>
```

## 3. Create a client and make the first request

The SDK automatically exchanges `client_id`/`client_secret` for an access
token, caches it and refreshes it when it expires.

### Node.js / TypeScript

```typescript
import { CRMClient } from "@hitechcloudvn/crm-sdk";

const crm = new CRMClient({
  clientId: process.env.CRM_CLIENT_ID!,
  clientSecret: process.env.CRM_CLIENT_SECRET!,
  scope: "contacts:read contacts:write",
});

const customers = await crm.contacts.list({ lifecycle_stage: "customer" });
console.log(customers.data);
```

### Python

```python
from hitechcloud_crm import CRMClient

crm = CRMClient(
    client_id="YOUR_CLIENT_ID",
    client_secret="YOUR_CLIENT_SECRET",
    scope="contacts:read contacts:write",
)
print(crm.contacts.list(lifecycle_stage="customer")["data"])
```

### PHP

```php
use HiTechCloud\CRM\Client;

$crm = new Client([
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'scope' => 'contacts:read contacts:write',
]);
print_r($crm->contacts()->list(['lifecycle_stage' => 'customer']));
```

### Go

```go
client, _ := crm.NewClient(
    crm.WithClientCredentials("YOUR_CLIENT_ID", "YOUR_CLIENT_SECRET", "contacts:read"),
)
res, _ := client.Contacts.List(map[string]string{"lifecycle_stage": "customer"})
fmt.Println(res["data"])
```

### Java

```java
CRMClient crm = new CRMClient("YOUR_CLIENT_ID", "YOUR_CLIENT_SECRET", "contacts:read");
System.out.println(crm.contacts().list(Map.of("lifecycle_stage", "customer")));
```

## 4. Create a new contact

```python
contact = crm.contacts.create(
    first_name="Nguyen",
    last_name="Van A",
    email="vana@example.com",
    phone="0901234567",
)
contact_id = contact["data"]["id"]  # e.g. con_8f2a1c9d
```

## 5. Use the Sandbox environment

Experiment safely with isolated data using `sandbox`:

```python
crm = CRMClient(client_id="...", client_secret="...", sandbox=True)
```

Next: see [Authentication](authentication.md) and [API reference](api-reference.md).
