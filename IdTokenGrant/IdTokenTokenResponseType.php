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

namespace OAuth2Framework\Component\OpenIdConnect\IdTokenGrant;

use OAuth2Framework\Component\AuthorizationEndpoint\Authorization;
use OAuth2Framework\Component\AuthorizationEndpoint\ResponseType;
use OAuth2Framework\Component\ImplicitGrant\TokenResponseType;

final class IdTokenTokenResponseType implements ResponseType
{
    /**
     * @var IdTokenResponseType
     */
    private $idTokenResponseType;

    /**
     * @var TokenResponseType
     */
    private $tokenResponseType;

    /**
     * IdTokenTokenResponseType constructor.
     *
     * @param IdTokenResponseType $idTokenResponseType
     * @param TokenResponseType   $tokenResponseType
     */
    public function __construct(IdTokenResponseType $idTokenResponseType, TokenResponseType $tokenResponseType)
    {
        $this->idTokenResponseType = $idTokenResponseType;
        $this->tokenResponseType = $tokenResponseType;
    }

    /**
     * {@inheritdoc}
     */
    public function associatedGrantTypes(): array
    {
        return \array_merge(
            $this->idTokenResponseType->associatedGrantTypes(),
            $this->tokenResponseType->associatedGrantTypes()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'id_token token';
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseMode(): string
    {
        return self::RESPONSE_TYPE_MODE_FRAGMENT;
    }

    /**
     * {@inheritdoc}
     */
    public function preProcess(Authorization $authorization): Authorization
    {
        $authorization = $this->tokenResponseType->preProcess($authorization);
        $authorization = $this->idTokenResponseType->preProcess($authorization);

        return $authorization;
    }

    /**
     * {@inheritdoc}
     */
    public function process(Authorization $authorization): Authorization
    {
        $authorization = $this->tokenResponseType->process($authorization);
        $authorization = $this->idTokenResponseType->process($authorization);

        return $authorization;
    }
}
