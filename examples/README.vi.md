# Ví dụ

> [English](README.md) | **Tiếng Việt**

---

Ví dụ chạy được cho từng SDK ngôn ngữ. Tất cả nhắm tới môi trường **sandbox** và
đọc thông tin xác thực từ `CRM_CLIENT_ID` / `CRM_CLIENT_SECRET`.

| Ngôn ngữ | Tệp | Chạy |
|----------|-----|------|
| Python | [`python/basic_usage.py`](python/basic_usage.py) | `python basic_usage.py` |
| Node.js | [`nodejs/basic_usage.mjs`](nodejs/basic_usage.mjs) | `node basic_usage.mjs` |
| PHP | [`php/basic_usage.php`](php/basic_usage.php) | `php basic_usage.php` |
| Go | [`go/basic_usage.go`](go/basic_usage.go) | `go run basic_usage.go` |
| Java | [`java/BasicUsage.java`](java/BasicUsage.java) | `java BasicUsage.java` |

```bash
export CRM_CLIENT_ID="YOUR_CLIENT_ID"
export CRM_CLIENT_SECRET="YOUR_CLIENT_SECRET"
```

Mỗi ví dụ: tạo một liên hệ, cập nhật và lấy lại nó, liệt kê khách hàng, tạo một
cơ hội, đăng ký webhook và minh hoạ xử lý lỗi.
