<?php
namespace Bleeper;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Bleeper
{
	public $endPoint = null;
	public $jwt = null;

	public function __construct(string $endPoint, string $jwt)
	{
		$this->endPoint = $endPoint;
		$this->jwt = $jwt;
	}

	public function createUser($user)
	{
		$body = [
			'user_name' => $user->name,
			'password' => $user->secret,
			'email' => $user->email,
			'role' => $user->role->name,
			'status' => '1',
		];
		$url = $this->endPoint . '/api/users';
		$client = new GuzzleClient();
		$response = $client->request('POST', $url, [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'body' => $body,
		]);
		return $response;
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
