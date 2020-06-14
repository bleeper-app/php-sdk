<?php
namespace Bleeper;

use GuzzleHttp\Client as GuzzleClient;

class Bleeper
{
	public $baseUrl = 'http://localhost:3000';
	public $apiKey;

	public function __construct(string $apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function messageList(string $token, string $cellphone)
	{
		$url = $this->baseUrl . '/api/messages';
		$client = new GuzzleClient();
		$response = $client->request('GET', $url, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
			'form_params' => [
				'cellphone' => $cellphone
			]
		]);
		return $this->getBodyResponse($response);
	}

	public function sendMessage(string $token, string $telefono, string $message, string $attachment_url = null)
	{
		$url = $this->baseUrl . '/api/messages/send';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
			'form_params' => [
				'from' =>'',
				'message' => $message,
				'attachment_url'=>  $attachment,
				'to' => $telefono,
			],
		]);
		return $this->getBodyResponse($response);
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
		return $this->getBodyResponse($response);
	}

	private function getBodyResponse($response)
	{
		return json_decode($response->getBody()->getContents(), true);
	}

}
