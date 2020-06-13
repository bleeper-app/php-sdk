<?php
namespace Bleeper;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;

class Authorization
{
    public $endpoint             = NULL;
    public $jwt                  = NULL;

    public function init(string $endPoint, string $jwt)
    {
        $this->endPoint = $endPoint;
        $this->jwt      = $jwt;
    }

    public function login()
    {
        try {
            $url = $this->endpoint.'/login';
            $client =  new GuzzleClient();
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => [
                    'password' => '',
                    'email'=>''
                ]
            ]);
        } catch (ConnectException | ClientException $e) {
            return ["status"=>false, "messages"=>"Problemas con el WS al obtener token"];
        }
    }

    
}
?>
