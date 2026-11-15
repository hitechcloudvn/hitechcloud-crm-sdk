# Error codes & handling

> **English** | [Tiếng Việt](error-handling.vi.md)

---

When a request fails, the API returns an appropriate HTTP status code along
with an **error envelope** carrying `status: "error"`, `code` and `message`.
Base your logic on `code` (stable) rather than `message`.

## Error envelope

```json
{
  "status": "error",
  "code": "VALIDATION_ERROR",
  "message": "Email field format is invalid.",
  "errors": {
    "email": ["Invalid email format"]
  }
}
```

## HTTP status codes

| HTTP | Meaning |
|------|---------|
| `200 OK` | Success |
| `201 Created` | Created successfully |
| `207 Multi-Status` | Partial results (bulk) |
| `400 Bad Request` | Invalid parameters |
| `401 Unauthorized` | Missing or invalid access token |
| `403 Forbidden` | Token lacks the scope for this operation |
| `404 Not Found` | Resource not found |
| `409 Conflict` | Conflict (e.g. duplicate email) |
| `422 Unprocessable Entity` | Data failed business validation |
| `429 Too Many Requests` | Rate limit exceeded |
| `500 Internal Server Error` | Server-side error |

## Error code strings

| code | HTTP | Description |
|------|------|-------------|
| `UNAUTHORIZED` | 401 | Token invalid or expired |
| `FORBIDDEN` | 403 | Token lacks the required scope |
| `NOT_FOUND` | 404 | Resource ID does not exist |
| `VALIDATION_ERROR` | 400/422 | Malformed parameters or payload |
| `DUPLICATE_RESOURCE` | 409 | Resource already exists |
| `TOO_MANY_REQUESTS` | 429 | Rate limit exceeded, see `Retry-After` |
| `INTERNAL_ERROR` | 500 | Unexpected server-side error |

## SDK exceptions

Each SDK maps HTTP status codes to an exception class:

| HTTP | Python / Node / Go / Java |
|------|---------------------------|
| 401 | `AuthenticationError` / `AuthenticationException` |
| 403 | `AuthorizationError` / `AuthorizationException` |
| 404 | `NotFoundError` / `NotFoundException` |
| 409 | `ConflictError` / `ConflictException` |
| 400, 422 | `ValidationError` / `ValidationException` |
| 429 | `RateLimitError` / `RateLimitException` (has `retry_after`) |
| 5xx | `ServerError` / `ServerException` |
| other | `CRMError` / `CRMException` |

### Python

```python
from hitechcloud_crm import NotFoundError, RateLimitError, ValidationError

try:
    crm.contacts.get("con_missing")
except NotFoundError:
    ...
except RateLimitError as e:
    print("retry after", e.retry_after, "seconds")
except ValidationError as e:
    print(e.code, e.errors)
```

### Node.js / TypeScript

```typescript
import { NotFoundError, RateLimitError } from "@hitechcloudvn/crm-sdk";

try {
  await crm.contacts.get("con_missing");
} catch (e) {
  if (e instanceof NotFoundError) { /* ... */ }
  else if (e instanceof RateLimitError) console.log("retry after", e.retryAfter);
}
```

### Go

```go
_, err := client.Contacts.Get("con_missing")
var nf *crm.NotFoundError
var rl *crm.RateLimitError
switch {
case errors.As(err, &nf):
    // not found
case errors.As(err, &rl):
    fmt.Println("retry after", rl.RetryAfter)
}
```

### Java

```java
try {
    crm.contacts().get("con_missing");
} catch (NotFoundException e) {
    // ...
} catch (RateLimitException e) {
    System.out.println("retry after " + e.getRetryAfter());
} catch (CRMException e) {
    System.out.println(e.getCode() + ": " + e.getMessage());
}
```

## Retries

The SDKs **retry automatically** with exponential backoff for `429` errors
(honoring `Retry-After`) and `5xx` errors. For `400/401/403/404`, retrying is
usually pointless — the request itself must be fixed. The SDKs also refresh the
token and retry once on a `401` caused by an expired token.
