<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity <= 0) {
            return "";
        } else {
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }


        // encode to base 64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        // remove bolcking characters
        $base64Header = str_replace(['+','/','='], ['-','_',''], $base64Header);
        $base64Payload = str_replace(['+','/','='], ['-','_',''], $base64Payload);

        // signature generation
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header.'.'.$base64Payload, $secret, true);
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+','/','='], ['-','_',''], $base64Signature);


        $jwt = $base64Header.'.'.$base64Payload.'.'.$base64Signature;



        return $jwt;
    }
}
