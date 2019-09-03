<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2019 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace OAuth2Framework\Component\OpenIdConnect\UserInfo\ScopeSupport;

class AddressScopeSupport implements UserInfoScopeSupport
{
    public function __toString(): string
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return 'address';
    }

    public function getParent(): ?string
    {
        return 'openid';
    }

    public function isParentMandatory(): bool
    {
        return true;
    }

    public function getAssociatedClaims(): array
    {
        return [
            'address',
        ];
    }

    public function jsonSerialize(): string
    {
        return $this->getName();
    }
}
