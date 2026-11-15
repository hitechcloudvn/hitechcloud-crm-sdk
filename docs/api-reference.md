# API reference

> **English** | [Tiếng Việt](api-reference.vi.md)

---

Every endpoint uses the `/v1/` prefix and the `Authorization: Bearer <token>`
header. Responses follow a consistent JSON envelope: `status`, `data` and
`meta` (pagination for lists).

## Response envelope

```json
{
  "status": "success",
  "data": [],
  "meta": {
    "pagination": {
      "total": 100, "per_page": 20, "current_page": 1,
      "last_page": 5, "has_more": true
    }
  }
}
```

## ID conventions

| Resource | ID prefix | Example |
|----------|-----------|---------|
| Contact | `con_` | `con_8f2a1c9d` |
| Company | `co_` | `co_4b7e2f10` |
| Deal | `deal_` | `deal_a19c330e` |
| Pipeline / Stage | `pl_` / `stg_` | `pl_sales`, `stg_qualified` |
| Activity | `act_` | `act_77de9012` |
| Note | `note_` | `note_15aa6b3c` |

## CRUD resources

Each resource below has five operations:

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/v1/{resource}` | List (paginated + filterable) |
| `GET` | `/v1/{resource}/{id}` | Detail by ID |
| `POST` | `/v1/{resource}` | Create |
| `PATCH` | `/v1/{resource}/{id}` | Partial update |
| `DELETE` | `/v1/{resource}/{id}` | Delete |

Resources and SDK accessors:

| Resource | Path | Accessor (Node/Go/Java) | Accessor (Python/PHP) |
|----------|------|-------------------------|------------------------|
| Contact | `contacts` | `contacts` | `contacts` |
| Company | `companies` | `companies` | `companies` |
| Deal | `deals` | `deals` | `deals` |
| Pipeline | `pipelines` | `pipelines` | `pipelines` |
| Stage | `stages` | `stages` | `stages` |
| Activity | `activities` | `activities` | `activities` |
| Task | `tasks` | `tasks` | `tasks` |
| Call | `calls` | `calls` | `calls` |
| Email | `emails` | `emails` | `emails` |
| Meeting | `meetings` | `meetings` | `meetings` |
| Note | `notes` | `notes` | `notes` |
| Tag | `tags` | `tags` | `tags` |
| Segment | `segments` | `segments` | `segments` |
| Custom field | `custom-fields` | `customFields` | `custom_fields` / `customFields()` |
| Lead source | `lead-sources` | `leadSources` | `lead_sources` / `leadSources()` |
| Product | `products` | `products` | `products` |
| Quote | `quotes` | `quotes` | `quotes` |
| File | `files` | `files` | `files` |
| User | `users` | `users` | `users` |
| Team | `teams` | `teams` | `teams` |

### Examples (all languages)

```python
crm.contacts.list(lifecycle_stage="customer", per_page=100)
crm.contacts.get("con_8f2a1c9d")
crm.contacts.create(first_name="Nguyen", email="vana@example.com")
crm.contacts.update("con_8f2a1c9d", lifecycle_stage="customer")
crm.contacts.delete("con_8f2a1c9d")
```

```typescript
await crm.deals.create({ title: "Goi Cloud Server", pipeline_id: "pl_sales", stage_id: "stg_qualified", amount: 24000000 });
await crm.deals.update("deal_a19c330e", { stage_id: "stg_won", status: "won" });
```

## Bulk operations

Available for `contacts` and `deals` only. Up to 100 operations per request;
returns `207 Multi-Status` with per-item results.

| Method | Endpoint |
|--------|----------|
| `POST` | `/v1/contacts/bulk` |
| `POST` | `/v1/deals/bulk` |

```python
crm.contacts.bulk([
    {"op": "create", "data": {"first_name": "A", "email": "a@example.com"}},
    {"op": "update", "data": {"id": "con_8f2a1c9d", "lifecycle_stage": "customer"}},
    {"op": "delete", "data": {"id": "con_0011aabb"}},
])
```

## Files

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/v1/files` | Upload (multipart), attach to a contact/company/deal |
| `GET` | `/v1/files` | List files |
| `GET` | `/v1/files/{id}` | File detail |
| `GET` | `/v1/files/{id}/download` | Download binary content |
| `DELETE` | `/v1/files/{id}` | Delete file |

```python
crm.files.upload("hopdong.pdf", resource_type="deal", resource_id="deal_a19c330e")
url = crm.files.download_url("file_aa11bb22")
```

Maximum file size is 25 MB.

## Data import / export (CSV, asynchronous)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/v1/imports` | Create an import job from CSV (multipart, `entity`) |
| `GET` | `/v1/imports/{id}` | Track import job progress |
| `POST` | `/v1/exports` | Create an export job (`entity`, `filter`) |
| `GET` | `/v1/exports/{id}` | Status and download link (`download_url` expires after 2 hours) |

```python
job = crm.imports.create("contacts.csv", entity="contact")
crm.imports.get(job["data"]["id"])

exp = crm.exports.create("contact", filter={"lifecycle_stage": "customer"})
crm.exports.get(exp["data"]["id"])
```

## Webhooks

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/v1/webhooks` | Register an endpoint (`url`, `events`, `secret`) |
| `GET` | `/v1/webhooks` | List registered webhooks |
| `DELETE` | `/v1/webhooks/{id}` | Delete a registration |

On every delivery, the CRM attaches an `X-CRM-Signature` header containing the
HMAC-SHA256 of the full body, signed with `secret`. Every SDK ships a signature
verification helper:

```python
from hitechcloud_crm import verify_webhook_signature
ok = verify_webhook_signature(raw_body, signature_header, "whsec_6b3c15aa")
```

### Event list

| Group | Events |
|-------|--------|
| Contact | `contact.created`, `contact.updated`, `contact.deleted` |
| Company | `company.created`, `company.updated`, `company.deleted` |
| Deal | `deal.created`, `deal.stage_changed`, `deal.won`, `deal.lost`, `deal.deleted` |
| Activity | `activity.created`, `activity.completed` |
| Task | `task.created`, `task.completed` |
| Note | `note.created` |
| Quote | `quote.sent`, `quote.accepted`, `quote.rejected` |
| Import | `import.completed` |

Events are delivered **at-least-once** — deduplicate by event `id`
(`evt_...`). Respond with `2xx` within 5 seconds; on failure or timeout, the
system retries with increasing backoff for up to 24 hours.

## Token endpoint

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/v1/oauth/token` | Exchange `client_credentials` for an access token |

See `openapi.json` (OpenAPI 3.0.3) for the full specification.
