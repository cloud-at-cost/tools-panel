<?php

namespace App\Services\CloudAtCost;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;

class PanelClient
{
    private string $username;
    private string $password;
    private Client $client;
    private CookieJar $cookieJar;

    private array $headers = [
        "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36",
    ];

    public function __construct(string $username, string $password, Client $client = null, CookieJar $cookieJar = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->client = $client ?? app(Client::class);
        $this->cookieJar = $cookieJar ?? app(CookieJar::class);

        $this->login();
    }

    public function post(string $url, array $data): string
    {
        if(empty($this->cookieJar->toArray())) {
            $this->login();
        }

        return $this->client->post(
            $this->buildUrl($url),
            [
                'cookies' => $this->cookieJar,
                'headers' => $this->headers,
                'form_params' => $data,
            ]
        )->getBody()->getContents();
    }

    public function get(string $url): string
    {
        if(!$this->cookieJar || empty($this->cookieJar->toArray())) {
            $this->login();
        }

        return $this->client->get(
            $this->buildUrl($url),
            [
                'cookies' => $this->cookieJar,
                'headers' => $this->headers,
            ]
        )->getBody()->getContents();
    }

    private function login()
    {
        $cookies = cache()->remember("credentials:" . md5($this->username . $this->password), now()->addMinutes(10),
            function() {
            $cookies = new CookieJar();

            $this->client
                ->post(
                    $this->buildUrl('manage-check2.php'), [
                        'form_params' => [
                            'username' => $this->username,
                            'password' => $this->password,
                            'submit' => 'Login',
                        ],
                        'headers' => $this->headers,
                        'cookies' => $cookies,
                    ]
                );

            return $cookies->toArray();
        });

        foreach($cookies as $cookie) {
            $this->cookieJar->setCookie(new SetCookie($cookie));
        }
    }

    private function buildUrl(string $url): string
    {
        return "https://panel.cloudatcost.com/$url";
    }
}
