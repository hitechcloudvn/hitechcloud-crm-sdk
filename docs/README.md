# HiTechCloud CRM SDK Documentation

> **English** | [Tiếng Việt](README.vi.md)

---

The official SDK suite for the **HiTechCloud CRM API** — manage contacts,
companies, deals, pipelines, activities, quotes and real-time webhooks from
your own applications.

- Host: `https://crm-gateways.hitechcloud.vn`
- Version prefix: `/v1`
- Authentication: OAuth2 `client_credentials` → Bearer token

## Table of contents

| Page | Content |
|------|---------|
| [Quick start](getting-started.md) | Install the SDK, create a client and make the first request |
| [Authentication](authentication.md) | OAuth2 flow, scopes and token management |
| [API reference](api-reference.md) | Resources, endpoints and SDK methods |
| [Error handling](error-handling.md) | Error codes, exceptions and how to handle them |
| [Pagination & rate limits](rate-limiting.md) | Pagination and rate limiting |

## Supported languages

| Language | Package |
|----------|---------|
| Node.js / TypeScript | `@hitechcloudvn/crm-sdk` (npm) |
| Python | `hitechcloud-crm` (PyPI) |
| PHP | `hitechcloudvn/crm-sdk` (Composer) |
| Go | `github.com/hitechcloudvn/hitechcloud-crm-sdk/go` |
| Java | `vn.hitechcloud:crm-sdk` (Maven) |

## Resources

Contacts, companies, deals, pipelines, stages, activities, tasks, calls,
emails, meetings, notes, tags, segments, custom fields, lead sources,
products, quotes, files, users and teams — each with full `list`, `get`,
`create`, `update` and `delete`. Plus: bulk operations (contacts, deals),
CSV import/export, file uploads and webhook registration.

## Environments

| Environment | Base URL |
|-------------|----------|
| Production | `https://crm-gateways.hitechcloud.vn` |
| Sandbox | `https://crm-gateways-sandbox.hitechcloud.vn` |

See `openapi.json` in the repository root for the machine-readable API
specification.
