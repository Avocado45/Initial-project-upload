<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'error' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data) || empty($data['url'])) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Missing URL'
    ]);
    exit;
}

$url = trim($data['url']);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Invalid URL'
    ]);
    exit;
}

$scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
if (!in_array($scheme, ['http', 'https'], true)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'Only http and https URLs are allowed'
    ]);
    exit;
}

$host = parse_url($url, PHP_URL_HOST);

if (!$host) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'URL must contain a valid host'
    ]);
    exit;
}

$host = strtolower($host);

// Block obvious local names first
$blockedHosts = [
    'localhost',
    '127.0.0.1',
    '0.0.0.0',
    '::1',
];

if (in_array($host, $blockedHosts, true)) {
    http_response_code(403);
    echo json_encode([
        'ok' => false,
        'error' => 'Access to local or internal hosts is not allowed'
    ]);
    exit;
}

$resolvedIps = [];

// If the host itself is an IP, validate it directly
if (filter_var($host, FILTER_VALIDATE_IP)) {
    $resolvedIps[] = $host;
} else {
    $dnsRecords = @dns_get_record($host, DNS_A + DNS_AAAA);

    if ($dnsRecords === false || empty($dnsRecords)) {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'error' => 'Could not resolve host'
        ]);
        exit;
    }

    foreach ($dnsRecords as $record) {
        if (!empty($record['ip'])) {
            $resolvedIps[] = $record['ip'];
        }
        if (!empty($record['ipv6'])) {
            $resolvedIps[] = $record['ipv6'];
        }
    }
}

if (empty($resolvedIps)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'No valid IP addresses found for host'
    ]);
    exit;
}

// Reject private, reserved, loopback, and internal IP ranges
foreach ($resolvedIps as $ip) {
    $isPublic = filter_var(
        $ip,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    );

    if ($isPublic === false) {
        http_response_code(403);
        echo json_encode([
            'ok' => false,
            'error' => 'Access to private or reserved IP ranges is not allowed'
        ]);
        exit;
    }
}

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 5,
        'follow_location' => 0,
        'header' => "User-Agent: CitationFetcher/1.0\r\n",
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
    ]
]);

$html = @file_get_contents($url, false, $context);

if ($html === false) {
    http_response_code(502);
    echo json_encode([
        'ok' => false,
        'error' => 'Failed to fetch remote page'
    ]);
    exit;
}

// Simple size limit
if (strlen($html) > 2 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode([
        'ok' => false,
        'error' => 'Remote page too large'
    ]);
    exit;
}

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$loaded = $dom->loadHTML($html);

if (!$loaded) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Failed to parse HTML'
    ]);
    exit;
}

$xpath = new DOMXPath($dom);

$title = null;
$description = null;
$siteName = null;

$titleNodes = $xpath->query('//title');
if ($titleNodes && $titleNodes->length > 0) {
    $title = trim($titleNodes->item(0)->textContent);
}

$metaTags = $xpath->query('//meta');
foreach ($metaTags as $meta) {
    $name = strtolower($meta->getAttribute('name'));
    $property = strtolower($meta->getAttribute('property'));
    $content = trim($meta->getAttribute('content'));

    if ($content === '') {
        continue;
    }

    if ($property === 'og:title') {
        $title = $content;
    }

    if (($name === 'description' || $property === 'og:description') && !$description) {
        $description = $content;
    }

    if ($property === 'og:site_name' && !$siteName) {
        $siteName = $content;
    }
}

echo json_encode([
    'ok' => true,
    'data' => [
        'url' => $url,
        'title' => $title,
        'description' => $description,
        'site_name' => $siteName,
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);