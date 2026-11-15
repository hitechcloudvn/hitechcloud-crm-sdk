# HiTechCloud CRM MCP Server

> [English](README.md) | **Tiếng Việt**

---

Một máy chủ [Model Context Protocol](https://modelcontextprotocol.io) cung cấp
các thao tác HiTechCloud CRM cốt lõi dưới dạng công cụ mà trợ lý AI có thể gọi.

## Cài đặt

```bash
cd mcp
npm install
```

## Cấu hình

Đặt thông tin xác thực OAuth2 (máy chủ tự lấy và cache access token):

```bash
export CRM_CLIENT_ID="YOUR_CLIENT_ID"
export CRM_CLIENT_SECRET="YOUR_CLIENT_SECRET"
export CRM_SCOPE="contacts:read contacts:write deals:read deals:write"
# Optional:
# export CRM_BASE_URL="https://crm-gateways-sandbox.hitechcloud.vn"
# export CRM_TOKEN="crm_at_..."   # use a pre-issued token instead
```

## Chạy

```bash
npm start
```

### Cấu hình Claude Desktop / MCP client

```json
{
  "mcpServers": {
    "hitechcloud-crm": {
      "command": "node",
      "args": ["/absolute/path/to/mcp/server.js"],
      "env": {
        "CRM_CLIENT_ID": "YOUR_CLIENT_ID",
        "CRM_CLIENT_SECRET": "YOUR_CLIENT_SECRET",
        "CRM_SCOPE": "contacts:read contacts:write deals:read deals:write"
      }
    }
  }
}
```

## Công cụ

| Công cụ | Mô tả |
|---------|-------|
| `list_contacts` | Liệt kê liên hệ có bộ lọc |
| `get_contact` | Lấy một liên hệ theo ID |
| `create_contact` | Tạo một liên hệ |
| `list_deals` | Liệt kê cơ hội có bộ lọc |
| `create_deal` | Tạo một cơ hội |
| `list_companies` | Liệt kê công ty |
| `list_pipelines` | Liệt kê pipeline bán hàng |
| `create_note` | Gắn một ghi chú vào liên hệ/công ty/cơ hội |

## Giấy phép

MIT
