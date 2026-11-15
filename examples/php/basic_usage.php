<?php

declare(strict_types=1);

// HiTechCloud CRM — PHP example / Ví dụ PHP
//
// Run: / Cách chạy:
//   composer require hitechcloudvn/crm-sdk
//   CRM_CLIENT_ID=... CRM_CLIENT_SECRET=... php basic_usage.php

require __DIR__ . '/vendor/autoload.php';

use HiTechCloud\CRM\Client;
use HiTechCloud\CRM\NotFoundException;
use HiTechCloud\CRM\RateLimitException;

$crm = new Client([
    'client_id' => getenv('CRM_CLIENT_ID'),
    'client_secret' => getenv('CRM_CLIENT_SECRET'),
    'scope' => 'contacts:read contacts:write deals:read deals:write',
    'sandbox' => true,
]);

// Create a contact / Tạo một liên hệ
$created = $crm->contacts()->create([
    'first_name' => 'Nguyen',
    'last_name' => 'Van A',
    'email' => 'vana@example.com',
    'phone' => '0901234567',
    'lifecycle_stage' => 'lead',
]);
$contactId = $created['data']['id'];
echo "Created contact: {$contactId}\n";

// Update + fetch / Cập nhật + lấy dữ liệu
$crm->contacts()->update($contactId, ['lifecycle_stage' => 'customer', 'tags' => ['vip']]);
print_r($crm->contacts()->get($contactId)['data']);

// List with filter / Liệt kê có bộ lọc
$customers = $crm->contacts()->list(['lifecycle_stage' => 'customer', 'per_page' => 50]);
echo 'Customers on page 1: ' . count($customers['data']) . "\n";

// Create a deal / Tạo một cơ hội
$deal = $crm->deals()->create([
    'title' => 'Goi Cloud Server',
    'pipeline_id' => 'pl_sales',
    'stage_id' => 'stg_qualified',
    'amount' => 24000000,
    'contact_id' => $contactId,
]);
echo "Created deal: {$deal['data']['id']}\n";

// Register a webhook / Đăng ký webhook
$crm->webhooks()->register(
    'https://your-app.vn/hooks/crm',
    ['contact.created', 'deal.won'],
    'whsec_6b3c15aa'
);

// Error handling / Xử lý lỗi
try {
    $crm->contacts()->get('con_missing');
} catch (NotFoundException $e) {
    echo "Contact not found (as expected)\n";
} catch (RateLimitException $e) {
    echo "Rate limited; retry after {$e->retryAfter}s\n";
}
