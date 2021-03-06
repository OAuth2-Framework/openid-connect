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

namespace OAuth2Framework\Component\OpenIdConnect\UserInfo;

use function Safe\parse_url;
use function Safe\sprintf;
use OAuth2Framework\Component\Core\Client\Client;
use OAuth2Framework\Component\Core\UserAccount\UserAccount;
use OAuth2Framework\Component\OpenIdConnect\UserInfo\Claim\ClaimManager;
use OAuth2Framework\Component\OpenIdConnect\UserInfo\Pairwise\PairwiseSubjectIdentifierAlgorithm;
use OAuth2Framework\Component\OpenIdConnect\UserInfo\ScopeSupport\UserInfoScopeSupportManager;

class UserInfo
{
    private ?PairwiseSubjectIdentifierAlgorithm $pairwiseAlgorithm = null;

    private UserInfoScopeSupportManager $userinfoScopeSupportManager;

    private ClaimManager $claimManager;

    public function __construct(UserInfoScopeSupportManager $userinfoScopeSupportManager, ClaimManager $claimManager)
    {
        $this->userinfoScopeSupportManager = $userinfoScopeSupportManager;
        $this->claimManager = $claimManager;
    }

    public function getUserinfo(Client $client, UserAccount $userAccount, string $redirectUri, array $requestedClaims, ?string $scope, ?string $claimsLocales): array
    {
        $requestedClaims = array_merge(
            $this->getClaimsFromClaimScope($scope),
            $requestedClaims
        );
        $claims = $this->getClaimValues($userAccount, $requestedClaims, $claimsLocales);
        /*$claims = array_merge(
            $claims,
            $this->claimSourceManager->getUserInfo($userAccount, $scope, [])
        );*/
        $claims['sub'] = $this->calculateSubjectIdentifier($client, $userAccount, $redirectUri);

        return $claims;
    }

    public function enablePairwiseSubject(PairwiseSubjectIdentifierAlgorithm $pairwiseAlgorithm): void
    {
        $this->pairwiseAlgorithm = $pairwiseAlgorithm;
    }

    public function isPairwiseSubjectIdentifierSupported(): bool
    {
        return null !== $this->pairwiseAlgorithm;
    }

    public function getPairwiseSubjectIdentifierAlgorithm(): ?PairwiseSubjectIdentifierAlgorithm
    {
        return $this->pairwiseAlgorithm;
    }

    private function getClaimsFromClaimScope(?string $scope): array
    {
        $result = [];
        $scope = $scope ?? '';

        foreach (explode(' ', $scope) as $scp) {
            if ($this->userinfoScopeSupportManager->has($scp)) {
                $scope_claims = $this->userinfoScopeSupportManager->get($scp)->getAssociatedClaims();
                foreach ($scope_claims as $scope_claim) {
                    $result[$scope_claim] = null;
                }
            }
        }

        return $result;
    }

    private function getClaimValues(UserAccount $userAccount, array $requestedClaims, ?string $claimsLocales): array
    {
        $result = [];
        $claimsLocales = null === $claimsLocales ? [] : array_unique(explode(' ', $claimsLocales));

        return $this->claimManager->getUserInfo($userAccount, $requestedClaims, $claimsLocales);
        /*foreach ($requestedClaims as $claim => $config) {
            foreach ($claimsLocales as $claims_locale) {
                $claim_locale = $this->computeClaimWithLocale($claim, $claims_locale);
                $claim_value = $this->getUserClaim($userAccount, $claim_locale, $config);
                if (null !== $claim_value) {
                    $result[$claim_locale] = $claim_value;

                    break;
                }
            }
        }*/
    }

    private function calculateSubjectIdentifier(Client $client, UserAccount $userAccount, string $redirectUri): string
    {
        $sub = $userAccount->getUserAccountId()->getValue();
        if (null === $this->pairwiseAlgorithm) {
            return $sub;
        }
        if ($client->has('subject_type') && ('pairwise' === $client->get('subject_type'))) {
            $sectorIdentifierHost = $this->getSectorIdentifierHost($client, $redirectUri);

            return $this->pairwiseAlgorithm->calculateSubjectIdentifier(
                $userAccount,
                $sectorIdentifierHost
            );
        }

        return $sub;
    }

    private function getSectorIdentifierHost(Client $client, string $redirectUri): string
    {
        $uri = $redirectUri;

        if (true === $client->has('sector_identifier_uri')) {
            $uri = $client->get('sector_identifier_uri');
        }

        $data = parse_url($uri);
        if (!\is_array($data) || !\array_key_exists('host', $data)) {
            throw new \InvalidArgumentException(sprintf('Invalid Sector Identifier Uri "%s".', $uri));
        }

        return $data['host'];
    }
}
