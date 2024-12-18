<?php

namespace App\Http\JWT;

class JWT {
    function __construct() {

    }

    public function createJwt($payload, $secretKey, $algorithm = 'HS256') {
        $header = json_encode(['typ' => 'JWT', 'alg' => $algorithm]);
        $base64Header = $this->base64UrlEncode($header);

        $base64Payload = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secretKey, true);
        $base64Signature = $this->base64UrlEncode($signature);

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }

    public function verifyJwt($jwt, $secretKey, $algorithm = 'HS256'){
        $parts = explode('.', $jwt);
        if (count($parts) != 3) {
            return false; // JWT khong hop le
        }

        list($base64Header, $base64Payload, $base64Signature) = $parts;

        $header =  json_decode($this->base64UrlDecode($base64Header), true);
        $payload =  json_decode($this->base64UrlDecode($base64Payload), true);

        if (!$header || !$payload) {
            return false;
        }

        $signature = $this->base64UrlDecode($base64Signature);
        $validSignature = hash_hmac('sha256', "$base64Header.$base64Payload", $secretKey, true);

        if (!hash_equals($signature, $validSignature)) {
            return false;
        }

        if(isset($payload['exp']) && time() >= $payload['exp']) {
            return false;
        }

        return $payload;
    }

    public function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
