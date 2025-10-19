<?php
namespace TinyApi\core\http;

class HttpClient
{
    private array $defaultHeaders = [];

    public function __construct(array $defaultHeaders = [])
    {
        $this->defaultHeaders = $defaultHeaders;
    }

    private function request(string $method, string $url, array $data = [], array $headers = [])
    {
        $ch = curl_init();

        $allHeaders = array_merge($this->defaultHeaders, $headers);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if (!empty($data)) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $allHeaders[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \Exception("CURL Error: {$error}");
        }

        curl_close($ch);
        return [
            'status' => $httpCode,
            'body' => json_decode($response, true)
        ];
    }

    public function get(string $url, array $headers = [])
    {
        return $this->request('GET', $url, [], $headers);
    }

    public function post(string $url, array $data = [], array $headers = [])
    {
        return $this->request('POST', $url, $data, $headers);
    }

    public function put(string $url, array $data = [], array $headers = [])
    {
        return $this->request('PUT', $url, $data, $headers);
    }

    public function delete(string $url, array $data = [], array $headers = [])
    {
        return $this->request('DELETE', $url, $data, $headers);
    }
}
