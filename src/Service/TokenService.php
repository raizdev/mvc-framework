<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service;

use Firebase\JWT\JWT;

/**
 * Class TokenService
 *
 * @package App\Service
 */
class TokenService
{
    /**
     * @param $id
     *
     * @return string
     * @throws \Exception
     */
    public function execute($id): string
    {
        $durationInSec = $_ENV['TOKEN_DURATION'];
        $tokenId = base64_encode(random_bytes(32));
        $issuedAt = time();
        $notBefore = $issuedAt + 2;
        $expire = $notBefore + $durationInSec;

        $data = [
            'iat' => $issuedAt,
            'jti' => $tokenId,
            'iss' => $_ENV['TOKEN_ISSUER'],
            'nbf' => $notBefore,
            'exp' => $expire,
            'ares_uid' => $id,
        ];

        return JWT::encode(
            $data,
            $_ENV['TOKEN_SECRET'],
            $_ENV['TOKEN_ALGORITHM']
        );
    }
}
