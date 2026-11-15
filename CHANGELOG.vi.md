# Nhật ký thay đổi

> [English](CHANGELOG.md) | **Tiếng Việt**

---

Mọi thay đổi đáng chú ý của HiTechCloud CRM SDK đều được ghi lại trong tệp này.

Định dạng dựa trên [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
và dự án tuân theo [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2026-11-16

### Đã sửa
- Các kiểu lỗi của SDK Go giờ tuân thủ đúng giao diện `error`. Type gốc được
  đổi tên thành `APIError` (`Error` giữ lại làm type alias) để trường nhúng
  không còn che khuất method `Error()` của các type bọc (`ValidationError`,
  `AuthenticationError`, `AuthorizationError`, `NotFoundError`,
  `ConflictError`, `RateLimitError`, `ServerError`).

## [1.0.0] - 2026-11-01

### Thêm
- Phát hành công khai đầu tiên của HiTechCloud CRM SDK với năm ngôn ngữ:
  Node.js/TypeScript, Python, PHP, Go và Java.
- Luồng token OAuth2 `client_credentials` với cache token và tự làm mới trong
  mọi client.
- CRUD đầy đủ cho 20 tài nguyên CRM: contacts, companies, deals, pipelines,
  stages, activities, tasks, calls, emails, meetings, notes, tags, segments,
  custom fields, lead sources, products, quotes, files, users và teams.
- Thao tác hàng loạt cho contacts và deals (`POST /v1/{resource}/bulk`).
- Tác vụ nhập và xuất CSV bất đồng bộ.
- Hàm tải lên (multipart) và tải xuống tệp.
- Hàm đăng ký webhook và hàm kiểm chứng `X-CRM-Signature` (HMAC-SHA256) trong
  mọi ngôn ngữ.
- Hệ thống ngoại lệ có kiểu, ánh xạ từ mã trạng thái HTTP, gồm `RateLimitError`
  với `Retry-After`.
- Tự động thử lại với exponential backoff cho phản hồi `429` và `5xx`.
- `openapi.json` (OpenAPI 3.0.3) mô tả mọi endpoint.
- Máy chủ Model Context Protocol (MCP) bằng Node.js, cung cấp các công cụ CRM
  cốt lõi.
- Tài liệu tiếng Việt dưới `docs/`.

[1.0.1]: https://github.com/hitechcloudvn/hitechcloud-crm-sdk/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/hitechcloudvn/hitechcloud-crm-sdk/releases/tag/v1.0.0
