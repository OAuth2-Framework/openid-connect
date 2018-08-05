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

namespace OAuth2Framework\Component\OpenIdConnect\ParameterChecker;

use OAuth2Framework\Component\AuthorizationEndpoint\Authorization;
use OAuth2Framework\Component\AuthorizationEndpoint\Exception\OAuth2AuthorizationException;
use OAuth2Framework\Component\AuthorizationEndpoint\ParameterChecker\ParameterChecker;
use OAuth2Framework\Component\Core\Message\OAuth2Message;

final class NonceParameterChecker implements ParameterChecker
{
    public function check(Authorization $authorization): Authorization
    {
        try {
            if (false !== \mb_strpos($authorization->getQueryParam('response_type'), 'id_token') && !$authorization->hasQueryParam('nonce')) {
                throw new \InvalidArgumentException('The parameter "nonce" is mandatory when the response type "id_token" is used.');
            }

            return $authorization;
        } catch (\InvalidArgumentException $e) {
            throw new OAuth2AuthorizationException(400, OAuth2Message::ERROR_INVALID_REQUEST, $e->getMessage(), $authorization, $e);
        }
    }
}
