<?php
namespace Bleeper;

use GuzzleHttp\Client as GuzzleClient;

class Bleeper
{
	public $baseUrl = 'http://localhost/api/v1';
	public $apiKey;
	public $token;

	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;
	}

	public function messageList($cellphone, $currentPage = 1, $pageSize = 10)
	{
		$token = $this->getToken();
		$url = $this->baseUrl . '/message/' . $currentPage . '/' . $pageSize;
		$client = new GuzzleClient();
		$response = $client->request('GET', $url, [
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
			],
			'form_params' => [
				'cell_phone' => $cellphone,
			],
		]);
		return $this->getBodyResponse($response);
	}

	public function sendMessage($from, $to, $message, $attachment_url = null)
	{
		$token = $this->getToken();
		$url = $this->baseUrl . '/message/sendMessage';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer ' . $token,
			],
			'form_params' => [
				'from' => $from,
				'to' => $to,
				'message' => $message,
				'attachment_url' => $attachment_url,
			],
		]);
		return $this->getBodyResponse($response);
	}

	public function getToken()
	{
		if ($this->token && !empty($this->token))
		{
			$tokenSlices = explode('.', $this->token);
			$payload = json_decode(base64_decode($tokenSlices[1]), true);
			$now = strtotime(date('Y-m-d H:i:s', strtotime('+1 minute'))) * 1000;
			if ($now >= $payload['expiration_date'])
			{
				$this->generateToken();
			}
			return $this->token;
		}
		$this->generateToken();
		return $this->token;
	}

	private function generateToken()
	{
		$url = $this->baseUrl . '/user/token';
		$client = new GuzzleClient();
		$response = $client->request('GET', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
				'Authorization' => 'Bearer ' . $this->apiKey,
			],
		]);
		$response = $this->getBodyResponse($response);
		return $this->token = $response['data']['token'];
	}

	private function getBodyResponse($response)
	{
		return json_decode($response->getBody()->getContents(), true);
	}
}
