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

use function Safe\sprintf;
class UserInfoScopeSupportManager
{
    /**
     * @var UserInfoScopeSupport[]
     */
    private array $userinfoScopeSupports;

    public function __construct()
    {
        $this->userinfoScopeSupports = [
            'openid' => new OpenIdScopeSupport()
        ];
    }

    public function add(UserInfoScopeSupport $userinfoScopeSupport): void
    {
        $this->userinfoScopeSupports[$userinfoScopeSupport->getName()] = $userinfoScopeSupport;
    }

    public function has(string $scope): bool
    {
        return \array_key_exists($scope, $this->userinfoScopeSupports);
    }

    public function get(string $scope): UserInfoScopeSupport
    {
        if (!$this->has($scope)) {
            throw new \InvalidArgumentException(sprintf('The userinfo scope "%s" is not supported.', $scope));
        }

        return $this->userinfoScopeSupports[$scope];
    }

    /**
     * @return UserInfoScopeSupport[]
     */
    public function all(): array
    {
        return $this->userinfoScopeSupports;
    }
}
