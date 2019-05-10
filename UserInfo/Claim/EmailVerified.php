<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2019 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace OAuth2Framework\Component\OpenIdConnect\UserInfo\Claim;

use OAuth2Framework\Component\Core\UserAccount\UserAccount;

final class EmailVerified implements Claim
{
    private const CLAIM_NAME = 'email_verified';

    public function name(): string
    {
        return self::CLAIM_NAME;
    }

    public function isAvailableForUserAccount(UserAccount $userAccount, ?string $claimLocale): bool
    {
        return $userAccount->has(self::CLAIM_NAME);
    }

    public function getForUserAccount(UserAccount $userAccount, ?string $claimLocale)
    {
        return $userAccount->get(self::CLAIM_NAME);
    }
}
