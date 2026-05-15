<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\CompanyRolesStorefrontResource;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\ApiPlatform\Relationship\PerItemRelationshipResolverInterface;
use Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Mapper\CompanyRolesResourceMapperInterface;
use Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Reader\CompanyRoleReaderInterface;
use Spryker\Service\Serializer\SerializerServiceInterface;

class CompanyRolesRelationshipResolver implements PerItemRelationshipResolverInterface
{
    public function __construct(
        protected CompanyRoleReaderInterface $companyRoleReader,
        protected CompanyRolesResourceMapperInterface $companyRolesResourceMapper,
        protected SerializerServiceInterface $serializer,
    ) {
    }

    /**
     * @param array<object> $parentResources
     * @param array<string, mixed> $context
     *
     * @return array<object>
     */
    public function resolve(array $parentResources, array $context): array
    {
        $allResources = [];

        foreach ($this->resolvePerItem($parentResources, $context) as $resources) {
            $allResources = array_merge($allResources, $resources);
        }

        return $allResources;
    }

    /**
     * @param array<object> $parentResources
     * @param array<string, mixed> $context
     *
     * @return array<string, array<object>>
     */
    public function resolvePerItem(array $parentResources, array $context): array
    {
        $idCompanyUsersIndexedByParentResourceUuid = $this->getIdCompanyUsersIndexedByParentResourceUuid($parentResources);
        $companyRolesGroupedByIdCompanyUser = $this->companyRoleReader->getCompanyRolesGroupedByIdCompanyUser(
            $this->extractUniqueIdCompanyUsers($idCompanyUsersIndexedByParentResourceUuid),
        );

        $result = [];

        foreach ($idCompanyUsersIndexedByParentResourceUuid as $uuid => $idCompanyUser) {
            $companyRoleTransfers = $companyRolesGroupedByIdCompanyUser[$idCompanyUser] ?? [];
            $result[$uuid] = $this->denormalizeCompanyRoleTransfers($companyRoleTransfers);
        }

        return $result;
    }

    /**
     * @param array<string, int|null> $idCompanyUsersByUuid
     *
     * @return list<int>
     */
    protected function extractUniqueIdCompanyUsers(array $idCompanyUsersByUuid): array
    {
        $idCompanyUsers = [];

        foreach ($idCompanyUsersByUuid as $idCompanyUser) {
            if ($idCompanyUser === null) {
                continue;
            }

            $idCompanyUsers[$idCompanyUser] = true;
        }

        return array_keys($idCompanyUsers);
    }

    /**
     * @param array<object> $parentResources
     *
     * @return array<string, int|null>
     */
    protected function getIdCompanyUsersIndexedByParentResourceUuid(array $parentResources): array
    {
        $idCompanyUsersIndexedByParentResourceUuid = [];

        foreach ($parentResources as $parentResource) {
            $uuid = $parentResource->uuid ?? null;
            $idCompanyUser = $parentResource->idCompanyUser ?? null;

            if ($uuid === null || $idCompanyUser === null) {
                continue;
            }

            $idCompanyUsersIndexedByParentResourceUuid[$uuid] = $idCompanyUser;
        }

        return $idCompanyUsersIndexedByParentResourceUuid;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CompanyRoleTransfer> $companyRoleTransfers
     *
     * @return array<\Generated\Api\Storefront\CompanyRolesStorefrontResource>
     */
    protected function denormalizeCompanyRoleTransfers(array $companyRoleTransfers): array
    {
        $resources = [];

        foreach ($companyRoleTransfers as $companyRoleTransfer) {
            $resources[] = $this->denormalizeToCompanyRoleResource($companyRoleTransfer);
        }

        return $resources;
    }

    protected function denormalizeToCompanyRoleResource(
        CompanyRoleTransfer $companyRoleTransfer,
    ): CompanyRolesStorefrontResource {
        return $this->serializer->denormalize(
            $this->companyRolesResourceMapper->mapCompanyRoleTransferToResourceData($companyRoleTransfer),
            CompanyRolesStorefrontResource::class,
        );
    }
}
