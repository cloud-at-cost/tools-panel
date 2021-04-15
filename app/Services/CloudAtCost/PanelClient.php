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

    private array $customer;

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

    public function post(string $url, array $data, bool $isSecure = false): string
    {
        if(empty($this->cookieJar->toArray())) {
            $this->login();
        }

        if($isSecure) {
            $data['cid'] = $this->customer['id'];
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

    public function get(string $url, array $parameters = [], bool $isSecure = false): string
    {
        if(!$this->cookieJar || empty($this->cookieJar->toArray())) {
            $this->login();
        }

        if($isSecure) {
            $parameters['cid'] = $this->customer['id'];
        }

        return $this->client->get(
            $this->buildUrl($url) . "?" . http_build_query($parameters),
            [
                'cookies' => $this->cookieJar,
                'headers' => $this->headers,
            ]
        )->getBody()->getContents();
    }

    private function login()
    {
        [$cookies, $this->customer] = cache()->remember(
            "credentials:" . md5($this->username . $this->password),
            now()->addMinutes(60),
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

            $script = $this->client
                ->get($this->buildUrl('/script'), [
                    'headers' => $this->headers,
                    'cookies' => $cookies,
                ])
                ->getBody()->getContents();

            $matches = [];
            preg_match(
                '/<input[^>]+name=\'cid\'[^>]+value=\'(\w+)\'>\s+<input[^>]+name=\'u\'[^>]+value=\'([\w@.+]+)\'>/i',
                $script,
                $matches
            );

            [,$customerId, $email] = $matches;

            return [
                $cookies->toArray(),
                [
                    'id' => $customerId,
                    'email' => $email,
                ]
            ];
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
