<?php
namespace Bleeper;

use GuzzleHttp\Client as GuzzleClient;

class Bleeper
{
	public $baseUrl = 'http://localhost:3000';
	public $apiKey;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function messageList($cellphone, $token, $currentPage, $pageSize)
	{
		$url = $this->baseUrl . '/api/message/' . $currentPage . '/' . $pageSize;
		$client = new GuzzleClient();
		$response = $client->request('GET', $url, [
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			],
			'form_params' => [
				'cellphone' => $cellphone,
			],
		]);
		return $this->getBodyResponse($response);
	}

	public function sendMessage($token, $from, $to, $message, $attachment_url = null, $saveLocal = false)
	{
		$url = $this->baseUrl . '/api/send_message';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer ' . $token,
			],
			'form_params' => [
				'from' => $from,
				'message' => $message,
				'attachment_url' => $attachment,
				'to' => $to,
				'save_local' => $saveLocal,
			],
		]);
		return $this->getBodyResponse($response);
	}

	public function getToken()
	{
		$url = $this->baseUrl . '/api/user/token';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Authorization' => 'Beader ' . $this->apiKey,
			],
		]);
		return $this->getBodyResponse($response);
	}

	public function regenerateTokenOnExpiration($token)
	{
		$tokenParts = explode('.', $token);
		$payloadEncoded = $tokenParts[1];
		$payload = json_decode(base64_decode($payloadEncoded), true);
		$expirationDate = $payload['expiration_date'];
		$now = strtotime(date()) * 1000;
		if ($now >= $expirationDate)
		{
			$this->getToken();
		}
	}

	private function getBodyResponse($response)
	{
		return json_decode($response->getBody()->getContents(), true);
	}
}
