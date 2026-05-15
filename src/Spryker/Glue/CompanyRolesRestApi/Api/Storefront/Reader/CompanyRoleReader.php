<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Reader;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\Client\CompanyRole\CompanyRoleClientInterface;

class CompanyRoleReader implements CompanyRoleReaderInterface
{
    public function __construct(protected CompanyRoleClientInterface $companyRoleClient)
    {
    }

    public function findCompanyRoleByUuid(string $uuid): ?CompanyRoleTransfer
    {
        $companyRoleResponseTransfer = $this->companyRoleClient->findCompanyRoleByUuid(
            (new CompanyRoleTransfer())->setUuid($uuid),
        );

        if (!$companyRoleResponseTransfer->getIsSuccessful()) {
            return null;
        }

        return $companyRoleResponseTransfer->getCompanyRoleTransfer();
    }

    public function getCompanyRoleCollectionByIdCompanyUser(int $idCompanyUser): CompanyRoleCollectionTransfer
    {
        return $this->companyRoleClient->getCompanyRoleCollection(
            (new CompanyRoleCriteriaFilterTransfer())->setIdCompanyUser($idCompanyUser),
        );
    }

    /**
     * @param list<int> $idCompanyUsers
     *
     * @return array<int, array<\Generated\Shared\Transfer\CompanyRoleTransfer>>
     */
    public function getCompanyRolesGroupedByIdCompanyUser(array $idCompanyUsers): array
    {
        if ($idCompanyUsers === []) {
            return [];
        }

        $companyRoleCriteriaFilterTransfer = (new CompanyRoleCriteriaFilterTransfer())
            ->setIdCompanyUsers($idCompanyUsers);

        $companyRoleCollectionTransfer = $this->companyRoleClient->getCompanyRoleCollection(
            $companyRoleCriteriaFilterTransfer,
        );

        return $this->groupCompanyRolesGroupedByIdCompanyUser($companyRoleCollectionTransfer, $idCompanyUsers);
    }

    /**
     * @param list<int> $idCompanyUsers
     *
     * @return array<int, array<\Generated\Shared\Transfer\CompanyRoleTransfer>>
     */
    protected function groupCompanyRolesGroupedByIdCompanyUser(
        CompanyRoleCollectionTransfer $companyRoleCollectionTransfer,
        array $idCompanyUsers,
    ): array {
        $requestedIdCompanyUsers = array_flip($idCompanyUsers);
        $companyRolesGroupedByIdCompanyUser = [];

        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            $companyUserCollectionTransfer = $companyRoleTransfer->getCompanyUserCollection();

            if ($companyUserCollectionTransfer === null) {
                continue;
            }

            foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
                $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

                if ($idCompanyUser === null || !isset($requestedIdCompanyUsers[$idCompanyUser])) {
                    continue;
                }

                $companyRolesGroupedByIdCompanyUser[$idCompanyUser] ??= [];
                $companyRolesGroupedByIdCompanyUser[$idCompanyUser][] = $companyRoleTransfer;
            }
        }

        return $companyRolesGroupedByIdCompanyUser;
    }
}
