
<?php

/**
 * Retrieve subsystem records from the MediLink Node.js API.
 */
function getSubsystemsFromAPI(): array
{
    $config = require __DIR__ . '/../config/api.php';

    $baseUrl = rtrim(
        $config['base_url'] ?? '',
        '/'
    );

    // Validate the configured API address.
    if (
        !filter_var($baseUrl, FILTER_VALIDATE_URL) ||
        !in_array(parse_url($baseUrl, PHP_URL_SCHEME), ['http', 'https'], true)
    ) {
        return [
            'success' => false,
            'message' => 'Invalid API address configuration.',
            'data' => []
        ];
    }

    $apiUrl = $baseUrl . '/api/subsystems';

    $curl = curl_init($apiUrl);

    if ($curl === false) {
        return [
            'success' => false,
            'message' => 'Unable to initialize the API connection.',
            'data' => []
        ];
    }

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json'
        ]
    ]);

    $response = curl_exec($curl);
    $curlError = curl_error($curl);
    $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($response === false) {
        return [
            'success' => false,
            'message' => 'Could not connect to the MediLink API: ' . $curlError,
            'data' => []
        ];
    }

    $result = json_decode($response, true);

    if ($httpCode !== 200) {
        return [
            'success' => false,
            'message' => 'The MediLink API returned HTTP ' . $httpCode,
            'data' => []
        ];
    }

    if (
        !is_array($result) ||
        !isset($result['success']) ||
        $result['success'] !== true ||
        !isset($result['data']) ||
        !is_array($result['data'])
    ) {
        return [
            'success' => false,
            'message' => 'The API returned an invalid response.',
            'data' => []
        ];
    }

    return [
        'success' => true,
        'message' => 'Subsystems retrieved successfully.',
        'data' => $result['data']
    ];
}
