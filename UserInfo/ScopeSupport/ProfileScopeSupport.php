<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2018 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace OAuth2Framework\Component\OpenIdConnect\UserInfo\ScopeSupport;

class ProfileScopeSupport implements UserInfoScopeSupport
{
    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->name();
    }

    /**
     * {@inheritdoc}
     */
    public function getClaims(): array
    {
        return [
            'name',
            'given_name',
            'middle_name',
            'family_name',
            'nickname',
            'preferred_username',
            'profile',
            'picture',
            'website',
            'gender',
            'birthdate',
            'zoneinfo',
            'locale',
            'updated_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->name();
    }
}
