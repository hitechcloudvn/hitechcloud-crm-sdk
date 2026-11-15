# Authentication & Access Token

> **English** | [Tiếng Việt](authentication.vi.md)

---

Every **HiTechCloud CRM API** endpoint requires authentication with a Bearer
access token. Tokens are issued through the **OAuth2 Client Credentials**
flow.

## Token issuance flow

```
client_id + client_secret + scope
        │
        ▼
POST /v1/oauth/token  (grant_type=client_credentials)
        │
        ▼
access_token (Bearer, expires after expires_in seconds)
        │
        ▼
Authorization: Bearer <access_token>  ← every /v1/* request
```

## Exchange credentials for a token

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

Response:

```json
{
  "status": "success",
  "data": {
    "access_token": "crm_at_9f8e7d6c5b4a3210",
    "token_type": "Bearer",
    "expires_in": 3600,
    "scope": "contacts:read contacts:write deals:read deals:write"
  }
}
```

## Send the token in the header

```http
Authorization: Bearer crm_at_9f8e7d6c5b4a3210
Content-Type: application/json
Accept: application/json
```

## Access scopes

| Scope | Permission |
|-------|------------|
| `contacts:read` | Read contacts and companies |
| `contacts:write` | Create/update/delete contacts and companies |
| `deals:read` | Read deals, pipelines and stages |
| `deals:write` | Create/update/delete deals and move stages |
| `activities:write` | Record activities and notes |
| `webhooks:manage` | Register and manage webhooks |

## Automatic token management

Every SDK in this repository **exchanges credentials for a token, caches it and
refreshes it** automatically before expiry (60 seconds early), and retries once
if the server returns `401`. You only need to supply `client_id`,
`client_secret` and `scope`.

```python
from hitechcloud_crm import CRMClient

crm = CRMClient(
    client_id="YOUR_CLIENT_ID",
    client_secret="YOUR_CLIENT_SECRET",
    scope="contacts:read contacts:write",
)
# No need to call /v1/oauth/token yourself; to fetch it manually:
token_data = crm.fetch_token()
```

If you already have a token (e.g. from another system), pass it directly:

```python
crm = CRMClient(token="crm_at_9f8e7d6c5b4a3210")
```

## Security notes

- Access tokens expire after `expires_in` seconds (3600 by default). Cache the
  token; do not call `/v1/oauth/token` on every request.
- Never log or expose `client_secret`, `access_token` or webhook `secret`
  values.
- Use the Sandbox (`crm-gateways-sandbox.hitechcloud.vn`) during development.
