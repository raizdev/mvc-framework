<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service;

use App\Entity\User;
use Firebase\JWT\JWT;
use League\Container\Container;

/**
 * Class GenerateTokenService
 */
class GenerateTokenService
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * GenerateTokenService constructor.
     *
     * @param   Container  $container
     */
    public function __construct(
        Container $container
    ) {
        $this->container = $container;
    }

    /**
     * Generates a JWT Token from user_id
     *
     * @param   User  $user
     *
     * @return string
     * @throws \Exception
     */
    public function execute(User $user): string
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
                'userId' => $user->getId(),
            ]
        ];

        $settings = $this->container->get('settings')['jwt'];

        return JWT::encode(
            $data,
            $settings['secret'],
            $settings['algorithm']
        );
    }
}