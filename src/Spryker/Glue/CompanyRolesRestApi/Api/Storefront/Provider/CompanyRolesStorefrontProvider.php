<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Provider;

use Generated\Api\Storefront\CompanyRolesStorefrontResource;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Exception\CompanyRolesExceptionFactory;
use Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Mapper\CompanyRolesResourceMapperInterface;
use Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Reader\CompanyRoleReaderInterface;
use Spryker\Service\Serializer\SerializerServiceInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CompanyRolesStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string KEY_UUID = 'uuid';

    protected const string OPERATION_NAME_GET_COMPANY_ROLES_MINE = 'getCompanyRolesMine';

    public function __construct(
        protected CompanyRoleReaderInterface $companyRoleReader,
        protected CompanyRolesExceptionFactory $exceptionFactory,
        protected CompanyRolesResourceMapperInterface $companyRolesResourceMapper,
        protected SerializerServiceInterface $serializer,
    ) {
    }

    /**
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    protected function provideItem(): ?object
    {
        if (!$this->hasCustomer()) {
            throw new AccessDeniedException();
        }

        return $this->provideCompanyRoleByCompanyUuid((string)$this->getUriVariable(static::KEY_UUID));
    }

    /**
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return array<\Generated\Api\Storefront\CompanyRolesStorefrontResource>
     */
    protected function provideCollection(): array
    {
        if ($this->getOperation()->getName() === static::OPERATION_NAME_GET_COMPANY_ROLES_MINE) {
            if (!$this->hasCustomer()) {
                throw new AccessDeniedException();
            }

            return $this->provideCurrentUserCompanyRoles();
        }

        throw $this->exceptionFactory->createResourceNotImplementedException();
    }

    /**
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     */
    protected function provideCompanyRoleByCompanyUuid(string $uuid): CompanyRolesStorefrontResource
    {
        $companyRoleTransfer = $this->companyRoleReader->findCompanyRoleByUuid($uuid);

        if ($companyRoleTransfer === null) {
            throw $this->exceptionFactory->createCompanyRoleNotFoundException();
        }

        $idCurrentCompany = $this->getCustomer()->getCompanyUserTransfer()?->getFkCompany();

        if ($idCurrentCompany === null || $idCurrentCompany !== $companyRoleTransfer->getFkCompany()) {
            throw $this->exceptionFactory->createCompanyRoleNotFoundException();
        }

        return $this->denormalizeToResource($companyRoleTransfer);
    }

    /**
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return array<\Generated\Api\Storefront\CompanyRolesStorefrontResource>
     */
    protected function provideCurrentUserCompanyRoles(): array
    {
        $idCompanyUser = $this->getCustomer()->getCompanyUserTransfer()?->getIdCompanyUser();

        if ($idCompanyUser === null) {
            throw $this->exceptionFactory->createCompanyUserNotSelectedException();
        }

        $companyRoleCollectionTransfer = $this->companyRoleReader->getCompanyRoleCollectionByIdCompanyUser($idCompanyUser);

        if ($companyRoleCollectionTransfer->getRoles()->count() === 0) {
            throw $this->exceptionFactory->createCompanyRoleNotFoundException();
        }

        $resources = [];

        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            $resources[] = $this->denormalizeToResource($companyRoleTransfer);
        }

        return $resources;
    }

    protected function denormalizeToResource(CompanyRoleTransfer $companyRoleTransfer): CompanyRolesStorefrontResource
    {
        return $this->serializer->denormalize(
            $this->companyRolesResourceMapper->mapCompanyRoleTransferToResourceData($companyRoleTransfer),
            CompanyRolesStorefrontResource::class,
        );
    }
}
