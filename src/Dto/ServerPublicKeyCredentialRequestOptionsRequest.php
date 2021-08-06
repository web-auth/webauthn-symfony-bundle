<?php

declare(strict_types=1);

namespace Webauthn\Bundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Webauthn\PublicKeyCredentialRequestOptions;

final class ServerPublicKeyCredentialRequestOptionsRequest
{
    /**
     * @Assert\NotBlank(allowNull=true)
     */
    public ?string $username = null;

    /**
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Choice({PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_PREFERRED, PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_REQUIRED, PublicKeyCredentialRequestOptions::USER_VERIFICATION_REQUIREMENT_DISCOURAGED})
     */
    public ?string $userVerification = null;

    public ?array $extensions = null;
}
