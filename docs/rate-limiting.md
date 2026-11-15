# Pagination & rate limits

> **English** | [Tiếng Việt](rate-limiting.vi.md)

---

## Pagination

Every list endpoint supports pagination via `page` and `per_page`, with a
`meta.pagination` block.

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number to fetch |
| `per_page` | integer | 20 | Records per page, up to 100 |

The `meta.pagination` block:

| Field | Description |
|-------|-------------|
| `total` | Total records matching the filter |
| `per_page` | Records per page currently in effect |
| `current_page` | Current page |
| `last_page` | Last page |
| `has_more` | `true` if more pages remain |

### Iterate over all pages

```python
page, all_contacts = 1, []
while True:
    res = crm.contacts.list(page=page, per_page=100)
    all_contacts.extend(res["data"])
    if not res["meta"]["pagination"]["has_more"]:
        break
    page += 1
print(f"Fetched {len(all_contacts)} contacts")
```

```typescript
let page = 1;
const all: any[] = [];
for (;;) {
  const res = await crm.contacts.list({ page, per_page: 100 });
  all.push(...res.data);
  if (!res.meta?.pagination?.has_more) break;
  page++;
}
```

## Rate limits

| Account tier | Limit | Response header |
|--------------|-------|-----------------|
| Standard | 120 requests / minute | `X-RateLimit-Limit: 120` |
| Enterprise | 600 requests / minute | `X-RateLimit-Limit: 600` |
| Sandbox | 240 requests / minute | `X-RateLimit-Limit: 240` |

When the limit is exceeded, the API returns `429 Too Many Requests` with a
`Retry-After` header (seconds to wait).

### SDK automatic handling

The SDKs **wait for `Retry-After` and retry automatically** (up to
`max_retries` times, 3 by default). If still rate-limited after all retries,
the SDK raises `RateLimitError` (or `RateLimitException`) with a `retry_after`
attribute.

### Tips to reduce call volume

- Use `per_page=100` with filters when syncing large data sets.
- Use the **bulk** endpoints for `contacts`/`deals` instead of single-record
  calls.
- Use **webhooks** instead of polling for real-time change notifications.
- Cache the access token (the SDK already does) instead of calling
  `/v1/oauth/token` every time.
