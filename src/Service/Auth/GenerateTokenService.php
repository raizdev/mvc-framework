<?php declare(strict_types=1);

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

namespace App\Service\Auth;

use App\Entity\User;
use App\Service\BaseService;
use App\Service\ContainerSettingsService;
use Firebase\JWT\JWT;

/**
 * Class GenerateTokenService
 */
class GenerateTokenService extends BaseService
{
    /**
     * @var ContainerSettingsService
     */
    private ContainerSettingsService $jwtSettings;

    /**
     * GenerateTokenService constructor.
     *
     * @param   ContainerSettingsService  $jwtSettings
     */
    public function __construct(
        ContainerSettingsService $jwtSettings
    ) {
        $this->jwtSettings = $jwtSettings;
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

        $settings = $this->jwtSettings->get('jwt');

        return JWT::encode(
            $data,
            $settings['secret'],
            $settings['algorithm']
        );
    }
}