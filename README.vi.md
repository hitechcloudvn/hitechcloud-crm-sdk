# HiTechCloud CRM SDK

> [English](README.md) | **Tiếng Việt**

---

[![CI](https://github.com/hitechcloudvn/hitechcloud-crm-sdk/actions/workflows/ci.yml/badge.svg)](https://github.com/hitechcloudvn/hitechcloud-crm-sdk/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> Bộ SDK đa ngôn ngữ chính thức cho **HiTechCloud CRM API** — quản lý liên hệ,
> công ty, cơ hội bán hàng, pipeline, hoạt động, báo giá và webhook thời gian
> thực từ ứng dụng của riêng bạn.

Host: `https://crm-gateways.hitechcloud.vn` · Tiền tố gốc: `/v1` ·
Xác thực: OAuth2 `client_credentials` → Bearer token ·
Tài liệu: [developers.hitechcloud.vn](https://developers.hitechcloud.vn)

## Ngôn ngữ hỗ trợ

| Ngôn ngữ | Gói | Manifest |
|----------|-----|----------|
| Node.js / TypeScript | `@hitechcloudvn/crm-sdk` (npm) | `sdk/nodejs/package.json` |
| Python | `hitechcloud-crm` (PyPI) | `sdk/python/pyproject.toml` |
| PHP | `hitechcloudvn/crm-sdk` (Composer) | `sdk/php/composer.json` |
| Go | `github.com/hitechcloudvn/hitechcloud-crm-sdk/go` | `sdk/go/go.mod` |
| Java | `vn.hitechcloud:crm-sdk` (Maven) | `sdk/java/pom.xml` |

## Tài nguyên được hỗ trợ

Liên hệ, công ty, cơ hội, pipeline, giai đoạn, hoạt động, công việc, cuộc gọi,
email, cuộc hẹn, ghi chú, nhãn, phân khúc, trường tùy chỉnh, nguồn khách hàng,
sản phẩm, báo giá, tệp, người dùng và nhóm — mỗi loại đều có `list`, `get`,
`create`, `update` và `delete`. Ngoài ra: thao tác hàng loạt (contacts, deals),
nhập/xuất CSV, tải lên/tải xuống tệp và đăng ký webhook + kiểm chứng
`X-CRM-Signature`.

## Môi trường

| Môi trường | Base URL |
|------------|----------|
| Production | `https://crm-gateways.hitechcloud.vn` |
| Sandbox | `https://crm-gateways-sandbox.hitechcloud.vn` |

## Xác thực

Mọi yêu cầu đều dùng access token OAuth2 `client_credentials`. Các SDK tự lấy,
cache và làm mới token — bạn chỉ cần cung cấp `client_id`, `client_secret` và
các scope cần thiết.

```bash
curl -X POST "https://crm-gateways.hitechcloud.vn/v1/oauth/token" \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "client_credentials",
    "client_id": "YOUR_CLIENT_ID",
    "client_secret": "YOUR_CLIENT_SECRET",
    "scope": "contacts:read contacts:write deals:read deals:write"
  }'
```

Các scope khả dụng: `contacts:read`, `contacts:write`, `deals:read`,
`deals:write`, `activities:write`, `webhooks:manage`.

## Bắt đầu nhanh

### Node.js / TypeScript

```bash
npm install @hitechcloudvn/crm-sdk
```

```typescript
import { CRMClient } from "@hitechcloudvn/crm-sdk";

const crm = new CRMClient({
  clientId: process.env.CRM_CLIENT_ID!,
  clientSecret: process.env.CRM_CLIENT_SECRET!,
  scope: "contacts:read contacts:write deals:read deals:write",
});

const contacts = await crm.contacts.list({ lifecycle_stage: "customer" });
const deal = await crm.deals.create({
  title: "Goi Cloud Server",
  pipeline_id: "pl_sales",
  stage_id: "stg_qualified",
  amount: 24000000,
});
```

### Python

```bash
pip install hitechcloud-crm
```

```python
from hitechcloud_crm import CRMClient

crm = CRMClient(
    client_id="YOUR_CLIENT_ID",
    client_secret="YOUR_CLIENT_SECRET",
    scope="contacts:read contacts:write",
)

contacts = crm.contacts.list(lifecycle_stage="customer")
contact = crm.contacts.create(first_name="Nguyen", email="vana@example.com")
```

### PHP

```bash
composer require hitechcloudvn/crm-sdk
```

```php
use HiTechCloud\CRM\Client;

$crm = new Client([
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'scope' => 'contacts:read contacts:write',
]);

$contacts = $crm->contacts()->list(['lifecycle_stage' => 'customer']);
```

### Go

```bash
go get github.com/hitechcloudvn/hitechcloud-crm-sdk/go
```

```go
import crm "github.com/hitechcloudvn/hitechcloud-crm-sdk/go"

client, _ := crm.NewClient(
    crm.WithClientCredentials("YOUR_CLIENT_ID", "YOUR_CLIENT_SECRET", "contacts:read contacts:write"),
)
contacts, _ := client.Contacts.List(map[string]string{"lifecycle_stage": "customer"})
```

### Java

```xml
<dependency>
  <groupId>vn.hitechcloud</groupId>
  <artifactId>crm-sdk</artifactId>
  <version>1.0.0</version>
</dependency>
```

```java
import vn.hitechcloud.crm.CRMClient;

CRMClient crm = new CRMClient("YOUR_CLIENT_ID", "YOUR_CLIENT_SECRET",
        "contacts:read contacts:write");
Map<String, Object> contacts = crm.contacts().list(Map.of("lifecycle_stage", "customer"));
```

## Webhook

CRM ký mọi body webhook bằng HMAC-SHA256 và gửi trong header
`X-CRM-Signature`. Mỗi SDK đều có hàm kiểm chứng chữ ký:

```typescript
import { verifyWebhookSignature } from "@hitechcloudvn/crm-sdk";

if (!verifyWebhookSignature(rawBody, req.headers["x-crm-signature"], secret)) {
  return res.status(400).end();
}
```

Xem [`docs/`](docs/README.vi.md) để biết tài liệu đầy đủ, và
[`openapi.json`](openapi.json) để lấy đặc tả API ở định dạng máy đọc được.

## Giới hạn tần suất

`120 req/phút` (tiêu chuẩn), `600 req/phút` (doanh nghiệp), `240 req/phút`
(sandbox). Khi nhận `429`, API trả header `Retry-After`; các SDK tự thử lại.

## Giấy phép

MIT — xem [LICENSE](LICENSE).

## Hỗ trợ

- Email: support@hitechcloud.vn
- Tài liệu: https://developers.hitechcloud.vn
- Issues: https://github.com/hitechcloudvn/hitechcloud-crm-sdk/issues
