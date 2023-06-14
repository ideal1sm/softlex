<?php

interface SecretKeyStorage {
    public function getSecretKey(): string;
}

class FileSecretKeyStorage implements SecretKeyStorage {
    public function getSecretKey(): string {
        // Логика получения ключа из файла
    }
}

class DBSecretKeyStorage implements SecretKeyStorage {
    public function getSecretKey(): string {
        // Логика получения ключа из базы данных
    }
}
class Concept {
    private $client;
    private SecretKeyStorage $secretStorage;

    public function __construct(SecretKeyStorage $secretStorage) {
        $this->client = new \GuzzleHttp\Client();
        $this->secretStorage = $secretStorage;
    }

    public function getUserData() {
        $params = [
            'auth' => ['user', 'pass'],
            'token' => $this->secretStorage->getSecretKey()
        ];

        $request = new \Request('GET', 'https://api.method', $params);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody();
        });

        $promise->wait();
    }
}

// Пример использования с FileSecretKeyStorage
$secretStorage = new FileSecretKeyStorage();
$concept = new Concept($secretStorage);
$concept->getUserData();

// Пример использования с DBSecretKeyStorage
$secretStorage = new DBSecretKeyStorage();
$concept = new Concept($secretStorage);
$concept->getUserData();