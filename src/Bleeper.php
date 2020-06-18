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

	public function messageList(string $cellphone, string $token, int $currentPage, int $pageSize)
	{
		$url = $this->baseUrl . '/api/message/'.$currentPage.'/'.$pageSize;
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

	public function sendMessage(string $token, string $from, string $to, string $message, string $attachment_url = null, $saveLocal = false)
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
				'attachment_url'=>  $attachment,
				'to' => $to,
				'save_local'=> $saveLocal
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
				'Authorization' => 'Beader '.$this->apiKey
			]
		]);
		return $this->getBodyResponse($response);
	}

	private function getBodyResponse($response)
	{
		return json_decode($response->getBody()->getContents(), true);
	}

}
