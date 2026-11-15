
<?php

function uploadToGitHub($file, $folder)
{
    $token = getenv('GITHUB_TOKEN');

    if (!$token) {
        throw new Exception('GitHub API token is not configured.');
    }

    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        throw new Exception('Invalid uploaded file.');
    }

    $owner = 'ctechdocutrack';
    $repository = 'Tourcycle-Vigan';
    $branch = 'main';

    $original_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = bin2hex(random_bytes(8)) . '.' . $original_extension;

    $github_path = trim($folder, '/') . '/' . $filename;

    $file_content = base64_encode(file_get_contents($file['tmp_name']));

    $api_url = "https://api.github.com/repos/$owner/$repository/contents/" . $github_path;

    $payload = json_encode([
        'message' => 'Upload ' . $filename,
        'content' => $file_content,
        'branch' => $branch
    ]);

    $curl = curl_init($api_url);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
            'Content-Type: application/json',
            'User-Agent: TourCycle-Vigan'
        ],
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 120
    ]);

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);

    curl_close($curl);

    if ($response === false || $curl_error) {
        throw new Exception('GitHub connection failed: ' . $curl_error);
    }

    $result = json_decode($response, true);

    if ($http_status !== 201) {
        $message = $result['message'] ?? 'Unknown GitHub API error.';
        throw new Exception('GitHub upload failed: ' . $message);
    }

    return [
        'path' => $github_path,
        'url' => 'https://raw.githubusercontent.com/' .
                $owner . '/' . $repository . '/' . $branch . '/' . $github_path,
        'sha' => $result['content']['sha'] ?? null
    ];
}


function deleteFromGitHub($path, $sha)
{
    $token = getenv('GITHUB_TOKEN');

    if (!$token || !$path || !$sha) {
        throw new Exception('Missing GitHub credentials or file information.');
    }

    $owner = 'ctechdocutrack';
    $repository = 'Tourcycle-Vigan';
    $branch = 'main';

    $api_url = "https://api.github.com/repos/$owner/$repository/contents/" . $path;

    $payload = json_encode([
        'message' => 'Remove uploaded file',
        'sha' => $sha,
        'branch' => $branch
    ]);

    $curl = curl_init($api_url);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
            'Content-Type: application/json',
            'User-Agent: TourCycle-Vigan'
        ],
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 120
    ]);

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);

    curl_close($curl);

    if ($response === false || $curl_error) {
        throw new Exception('GitHub deletion connection failed: ' . $curl_error);
    }

    if ($http_status !== 200) {
        $result = json_decode($response, true);
        throw new Exception('GitHub deletion failed: ' . ($result['message'] ?? 'Unknown error.'));
    }

    return true;
}

function deleteGitHubFileByUrl($file_url)
{
    $owner = 'ctechdocutrack';
    $repository = 'Tourcycle-Vigan';
    $branch = 'main';

    $url_prefix = "https://raw.githubusercontent.com/$owner/$repository/$branch/";

    if (strpos($file_url, $url_prefix) !== 0) {
        throw new Exception('Invalid GitHub file URL.');
    }

    $github_path = substr($file_url, strlen($url_prefix));

    $api_url = "https://api.github.com/repos/$owner/$repository/contents/" . $github_path;

    $curl = curl_init($api_url);

    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . getenv('GITHUB_TOKEN'),
            'Accept: application/vnd.github+json',
            'X-GitHub-Api-Version: 2022-11-28',
            'User-Agent: TourCycle-Vigan'
        ],
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 120
    ]);

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);

    curl_close($curl);

    if ($response === false || $curl_error) {
        throw new Exception('GitHub file lookup failed: ' . $curl_error);
    }

    $result = json_decode($response, true);

    if ($http_status !== 200 || empty($result['sha'])) {
        throw new Exception('Unable to retrieve GitHub file SHA.');
    }

    return deleteFromGitHub($github_path, $result['sha']);
}
