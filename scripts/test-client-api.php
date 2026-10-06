<?php

declare(strict_types=1);

/**
 * Standalone smoke test for the PM Client API.
 *
 * Exercises the live endpoints (list, detail, categories, services) plus the
 * auth/permission failure paths, against a running application instance.
 * Requires: ext-curl. Does not boot the Laravel app - plain PHP + cURL only.
 *
 * Authenticates using a permanent API token (H2H) - see ApiToken::TYPE_PERMANENT
 * and Auth > API Users in the admin panel. No login call is made.
 *
 * Usage:
 *   php test-client-api.php <api_token> [base_url]
 *
 * Arguments:
 *   api_token  Permanent API bearer token (required) - from Auth > API Users
 *   base_url   Base URL of the app (optional, default: http://localhost:8000)
 *
 * Every request also prints the raw API response body (pretty-printed JSON) below
 * its PASS/FAIL line, so you can inspect the actual payload, not just the check
 * result. Example output (trimmed to one section for brevity):
 *
 *   == PM Client API smoke test ==
 *   Base URL: http://localhost:8000
 *
 *   -- Auth --
 *     Response (200):
 *       {
 *           "status": "2000600",
 *           "message": "Success",
 *           "data": [],
 *           "meta": {
 *               "current_page": 1,
 *               "last_page": 1,
 *               "per_page": 1,
 *               "total": 0
 *           }
 *       }
 *     PASS - API token accepted
 *
 *   -- Client List --
 *     Response (200):
 *       { ... same shape as above, up to 5 items ... }
 *     PASS - GET /client/list returns 200
 *     PASS - response has data + meta.{current_page,total}
 *     Response (422):
 *       {
 *           "status": "4220603",
 *           "message": "Validation failed.",
 *           "errors": { "sort_by": ["The selected sort by is invalid."] }
 *       }
 *     PASS - GET /client/list with invalid sort_by returns 422
 *
 *   ... (Client Detail, Client Categories, Services, Auth Failure Paths follow
 *        the same pattern: Response body, then PASS/FAIL) ...
 *
 *   == Summary ==
 *   Passed: 13
 *   Failed: 0
 *
 * Exit code is 0 when Failed is 0, and 1 otherwise (or if the API token is rejected
 * or the api_token argument is missing).
 */
$apiToken = $argv[1] ?? '';
$baseUrl = $argv[2] ?? 'http://localhost:8000';

if ($apiToken === '') {
    fwrite(STDERR, "Error: api_token argument is required.\n");
    fwrite(STDERR, "Usage: php {$argv[0]} <api_token> [base_url]\n");
    exit(1);
}

$passCount = 0;
$failCount = 0;

function pass(string $message): void
{
    global $passCount;
    echo "  PASS - {$message}\n";
    $passCount++;
}

function fail(string $message): void
{
    global $failCount;
    echo "  FAIL - {$message}\n";
    $failCount++;
}

/**
 * @param  array{status: int, body: string, json: mixed}  $response
 */
function showResponse(array $response): void
{
    echo "  Response ({$response['status']}):\n";

    $pretty = $response['json'] !== null
        ? json_encode($response['json'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        : $response['body'];

    foreach (explode("\n", (string) $pretty) as $line) {
        echo "    {$line}\n";
    }
}

/**
 * @param  array<string, string>  $headers
 * @return array{status: int, body: string, json: mixed}
 */
function apiRequest(string $method, string $url, array $headers = [], ?string $jsonBody = null): array
{
    $ch = curl_init($url);

    $headerLines = ['Accept: application/json'];
    foreach ($headers as $key => $value) {
        $headerLines[] = "{$key}: {$value}";
    }

    $options = [
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headerLines,
        CURLOPT_TIMEOUT => 10,
    ];

    if ($jsonBody !== null) {
        $options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        $options[CURLOPT_POSTFIELDS] = $jsonBody;
    }

    curl_setopt_array($ch, $options);

    $body = curl_exec($ch);
    $errorNo = curl_errno($ch);

    if ($errorNo !== 0 || $body === false) {
        $error = curl_error($ch);
        curl_close($ch);

        return ['status' => 0, 'body' => "cURL error: {$error}", 'json' => null];
    }

    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = json_decode($body, true);

    return ['status' => $status, 'body' => $body, 'json' => $json];
}

echo "== PM Client API smoke test ==\n";
echo "Base URL: {$baseUrl}\n\n";

$authHeader = ['Authorization' => "Bearer {$apiToken}"];

// ---------------------------------------------------------------------------
// 1. Auth sanity check
// ---------------------------------------------------------------------------
echo "-- Auth --\n";

$authCheck = apiRequest('GET', "{$baseUrl}/api/pm/client/list?limit=1", $authHeader);
showResponse($authCheck);

if ($authCheck['status'] === 200) {
    pass('API token accepted');
} else {
    fail("API token rejected with HTTP {$authCheck['status']}: {$authCheck['body']}");
    fwrite(STDERR, "Cannot continue with a rejected API token.\n");
    exit(1);
}

// ---------------------------------------------------------------------------
// 2. Client list
// ---------------------------------------------------------------------------
echo "\n-- Client List --\n";

$firstClientId = null;

$list = apiRequest('GET', "{$baseUrl}/api/pm/client/list?limit=5", $authHeader);
showResponse($list);

if ($list['status'] === 200) {
    pass('GET /client/list returns 200');

    $data = $list['json']['data'] ?? null;
    $meta = $list['json']['meta'] ?? null;

    if (is_array($data) && isset($meta['current_page']) && array_key_exists('total', (array) $meta)) {
        pass('response has data + meta.{current_page,total}');
        $firstClientId = $data[0]['id'] ?? null;
    } else {
        fail("response missing expected data/meta keys: {$list['body']}");
    }
} else {
    fail("GET /client/list returned HTTP {$list['status']}: {$list['body']}");
}

$invalidSort = apiRequest('GET', "{$baseUrl}/api/pm/client/list?sort_by=invalid_column", $authHeader);
showResponse($invalidSort);

if ($invalidSort['status'] === 422) {
    pass('GET /client/list with invalid sort_by returns 422');
} else {
    fail("GET /client/list with invalid sort_by expected 422, got {$invalidSort['status']}: {$invalidSort['body']}");
}

// ---------------------------------------------------------------------------
// 3. Client detail
// ---------------------------------------------------------------------------
echo "\n-- Client Detail --\n";

if ($firstClientId !== null) {
    $detail = apiRequest('GET', "{$baseUrl}/api/pm/client/{$firstClientId}", $authHeader);
    showResponse($detail);

    if ($detail['status'] === 200) {
        pass("GET /client/{id} returns 200 for id={$firstClientId}");

        $detailData = $detail['json']['data'] ?? null;

        if (isset($detailData['id'], $detailData['company']) && array_key_exists('services', (array) $detailData)) {
            pass('detail response has expected fields (id, company, services)');
        } else {
            fail("detail response missing expected fields: {$detail['body']}");
        }
    } else {
        fail("GET /client/{id} returned HTTP {$detail['status']}: {$detail['body']}");
    }
} else {
    echo "  SKIP - no clients found; skipping detail success check (seed data first for full coverage)\n";
}

$missing = apiRequest('GET', "{$baseUrl}/api/pm/client/999999999", $authHeader);
showResponse($missing);

if ($missing['status'] === 404) {
    pass('GET /client/{id} with unknown id returns 404');
} else {
    fail("GET /client/{id} with unknown id expected 404, got {$missing['status']}: {$missing['body']}");
}

// ---------------------------------------------------------------------------
// 4. Client categories
// ---------------------------------------------------------------------------
echo "\n-- Client Categories --\n";

$categories = apiRequest('GET', "{$baseUrl}/api/pm/client-category/list", $authHeader);
showResponse($categories);

if ($categories['status'] === 200) {
    pass('GET /client-category/list returns 200');

    $data = $categories['json']['data'] ?? null;
    $meta = $categories['json']['meta'] ?? null;

    if (is_array($data) && array_key_exists('total', (array) $meta)) {
        pass('response has data + meta.total');
    } else {
        fail("response missing expected data/meta keys: {$categories['body']}");
    }
} else {
    fail("GET /client-category/list returned HTTP {$categories['status']}: {$categories['body']}");
}

// ---------------------------------------------------------------------------
// 5. Services
// ---------------------------------------------------------------------------
echo "\n-- Services --\n";

$services = apiRequest('GET', "{$baseUrl}/api/pm/service/list", $authHeader);
showResponse($services);

if ($services['status'] === 200) {
    pass('GET /service/list returns 200');

    $count = count($services['json']['data'] ?? []);

    if ($count > 0) {
        pass("service list is non-empty ({$count} services)");
    } else {
        fail('service list is empty');
    }
} else {
    fail("GET /service/list returned HTTP {$services['status']}: {$services['body']}");
}

// ---------------------------------------------------------------------------
// 6. Auth failure paths
// ---------------------------------------------------------------------------
echo "\n-- Auth Failure Paths --\n";

$noAuth = apiRequest('GET', "{$baseUrl}/api/pm/client/list", []);
showResponse($noAuth);

if ($noAuth['status'] === 401) {
    pass('GET /client/list without token returns 401');
} else {
    fail("GET /client/list without token expected 401, got {$noAuth['status']}: {$noAuth['body']}");
}

$badAuth = apiRequest('GET', "{$baseUrl}/api/pm/client/list", ['Authorization' => 'Bearer invalid-token-value']);
showResponse($badAuth);

if ($badAuth['status'] === 401) {
    pass('GET /client/list with invalid token returns 401');
} else {
    fail("GET /client/list with invalid token expected 401, got {$badAuth['status']}: {$badAuth['body']}");
}

// ---------------------------------------------------------------------------
// Summary
// ---------------------------------------------------------------------------
echo "\n== Summary ==\n";
echo "Passed: {$passCount}\n";
echo "Failed: {$failCount}\n";

exit($failCount > 0 ? 1 : 0);
