<?php
namespace Bleeper;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Bleeper
{
	public $endPoint = 'http://localhost:3000';
	public $jwt;
	public $user;

	public function createUser($user)
	{
		$body = [
			'user_name' => $user['name'],
			'password' => $user['password'],
			'email' => $user['email'],
			'role' => $user['role'],
			'status' => $user['status'],
		];
		$url = $this->endPoint . '/api/users';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'form_params' => $body,
		]);
		$reason = $response->getReasonPhrase();
		$jsonDecodeResponse = json_decode($response->getBody()->getContents(), true);
		return $jsonDecodeResponse;
	}

	public function sendMessage($jwt, $telefono, $message)
	{
		$url = $this->endPoint . '/api/messages/send';
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
	}

	public function login($email, $pass)
	{
		try {
			$url = $this->endPoint . '/login';
			$client = new GuzzleClient();
			$response = $client->request('POST', $url, [
				'headers' => [
					'Content-Type' => 'application/x-www-form-urlencoded',
				],
				'body' => [
					'password' => $email,
					'email' => $email,
				],
			]);
		}
		catch (ConnectException | ClientException $e)
		{
			return ["status" => false, "messages" => "Problemas con el WS al obtener token"];
		}
	}

}
