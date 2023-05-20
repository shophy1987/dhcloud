<?php

namespace DahuaCloud;

use DahuaCloud\Helper\Utils;
use DahuaCloud\Exception\ApiException;

abstract class Api
{
    use Traits\OrgTrait;
    use Traits\UserTrait;

    const TIME_OUT = 12;
    const BASE_URL = 'https://www.cloud-dahua.com/gateway/';
    const CP_ACCESS_TOKEN = 'DAC-AT-';

    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct($client_id, $client_secret)
    {
        Utils::checkEmptyStr($client_id, 'client_id');
        Utils::checkEmptyStr($client_secret, 'client_secret');

        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
    }

    protected function get($uri, $data = [], $headers = [])
    {
        return $this->request('GET', $uri, ['headers' => $headers, 'query' => $data]);
    }

    protected function post($uri, $data = [], $headers = [])
    {
        return $this->request('POST', $uri, ['headers' => $headers, 'json' => $data]);
    }

    protected function put($uri, $data = [], $headers = [])
    {
        return $this->request('PUT', $uri, ['headers' => $headers, 'json' => $data]);
    }

    protected function patch($uri, $data = [], $headers = [])
    {
        return $this->request('PATCH', $uri, ['headers' => $headers, 'json' => $data]);
    }

    protected function delete($uri, $data = [], $headers = [])
    {
        return $this->request('DELETE', $uri, ['headers' => $headers, 'query' => $data]);
    }

    protected function request($method, $uri, $options)
    {
        $options['headers']['Content-Type'] = 'application/json;charset=utf-8';
        $options['headers']['Authorization'] = 'Bearer '.$this->getAccessToken();
        $options['headers']['Accept-Language'] = 'zh-CN';

        $client = new \GuzzleHttp\Client(['timeout' => self::TIME_OUT, 'base_uri' => self::BASE_URL]);
        $response = $client->request($method, $uri, $options);
        if ($response->getStatusCode() == 204) {
            return [];
        } else {
            $response = json_decode($response->getBody()->getContents(), true);
            if (isset($response['code']) && $response['code'] != 0) {
                throw new ApiException($response['errMsg'] ?? 'unknown error', $response['code']);
            }

            return $response;
        }
    }

    public function getAccessToken($bflush = false)
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $cacheKey = self::CP_ACCESS_TOKEN . $this->clientId;
        $this->accessToken = $bflush ? '' : $this->getCache($cacheKey);
        if ($this->accessToken) {
            return $this->accessToken;
        }

        $client = new \GuzzleHttp\Client(['timeout' => self::TIME_OUT, 'base_uri' => self::BASE_URL]);
        $options = [
            'json' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
                'scope' => 'server'
            ],
            'headers' => ['Content-Type' => 'application/json']
        ];
        $response = $client->request('POST', 'auth/api/oauth/token', $options);
        $response = json_decode($response->getBody()->getContents(), true);
        if (isset($response['error'])) {
            throw new ApiException($response['error_description'] ?? 'unknown error', $response['error']);
        }
        if (isset($response['code']) && $response['code'] != 0) {
            throw new ApiException($response['errMsg'] ?? 'unknown error', $response['code']);
        }

        $this->accessToken = $response['access_token'];
        $this->setCache($cacheKey, $this->accessToken, $response['expires_in']);
        return $this->accessToken;
    }

    abstract protected function getCache($key);

    abstract protected function setCache($key, $value, $expire);
}
