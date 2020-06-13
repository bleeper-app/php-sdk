<?php
namespace Bleeper;

use GuzzleHttp\Client as GuzzleClient;

class Bleeper
{
	public $baseUrl = 'http://localhost:3000';
	public $jwt;
	public $apiKey;

	public function __construct(string $apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function messageList(string $jwt)
	{
		$url = $this->baseUrl . '/api/messages';
		$client = new GuzzleClient();
		$response = $client->request('GET', $url, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $jwt,
			]
		]);
		$jsonDecodeResponse = json_decode($response->getBody()->getContents(), true);
		return $jsonDecodeResponse;
	}

	public function sendMessage(string $jwt, string $telefono, string $message)
	{
		$url = $this->baseUrl . '/api/messages/send';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $jwt,
			],
			'json' => [
				'text' => $message,
				'contact_phone_number' => $telefono,
			],
		]);
		$jsonDecodeResponse = json_decode($response->getBody()->getContents(), true);
		return $jsonDecodeResponse;
	}

	public function getToken()
	{
		$url = $this->baseUrl . '/login';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'body' => [
				'api_key' => $this->apiKey,
			],
		]);
		$jsonDecodeResponse = json_decode($response->getBody()->getContents(), true);
		return $jsonDecodeResponse;
	}

}
