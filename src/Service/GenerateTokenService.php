<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service;

use Firebase\JWT\JWT;

/**
 * Class GenerateTokenService
 *
 * @package App\Service
 */
class GenerateTokenService
{
    /**
     * @param $data
     *
     * @return array
     * @throws \Exception
     */
    public function execute($data): array
    {
        $durationInSec = getenv('WEB_TOKEN_DURATION');
        $tokenId       = base64_encode(random_bytes(32));
        $issuedAt      = time();
        $notBefore     = $issuedAt + 2;
        $expire        = $notBefore + $durationInSec;

        $data = [
            'iat'  => $issuedAt,
            'jti'  => $tokenId,
            'iss'  => 'Ares',
            'nbf'  => $notBefore,
            'exp'  => $expire,
            'data' => [
                'userId' => $data,
            ]
        ];

        return [
            'token'  => JWT::encode(
                $data,
                getenv('WEB_SECRET'),
                getenv('WEB_ALGORITHM')
            ),
            'expire' => $expire
        ];
    }
}