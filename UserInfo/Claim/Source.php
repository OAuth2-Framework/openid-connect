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

namespace OAuth2Framework\Component\OpenIdConnect\UserInfo\Claim;

class Source
{
    /**
     * @var string[]
     */
    private array $availableClaims;

    private array $source;

    /**
     * @param string[] $availableClaims
     */
    public function __construct(array $availableClaims, array $source)
    {
        $this->availableClaims = $availableClaims;
        $this->source = $source;
    }

    /**
     * @return string[]
     */
    public function getAvailableClaims(): array
    {
        return $this->availableClaims;
    }

    public function getSource(): array
    {
        return $this->source;
    }
}
