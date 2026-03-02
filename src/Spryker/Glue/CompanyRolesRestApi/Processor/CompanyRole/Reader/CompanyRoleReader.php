<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Reader;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig;
use Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder\CompanyRoleRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyRoleReader implements CompanyRoleReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientInterface
     */
    protected $companyRoleClient;

    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface
     */
    protected $companyRoleMapperInterface;

    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\RestResponseBuilder\CompanyRoleRestResponseBuilderInterface
     */
    protected $companyRoleRestResponseBuilder;

    public function __construct(
        CompanyRolesRestApiToCompanyRoleClientInterface $companyRoleClient,
        CompanyRoleMapperInterface $companyRoleMapperInterface,
        CompanyRoleRestResponseBuilderInterface $companyRoleRestResponseBuilder
    ) {
        $this->companyRoleClient = $companyRoleClient;
        $this->companyRoleMapperInterface = $companyRoleMapperInterface;
        $this->companyRoleRestResponseBuilder = $companyRoleRestResponseBuilder;
    }

    public function getCurrentUserCompanyRole(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($this->isResourceIdentifierCurrentUser($restRequest->getResource()->getId())) {
            return $this->getCurrentUserCompanyRoles($restRequest);
        }

        return $this->getCurrentUserCompanyRoleByUuid($restRequest);
    }

    protected function getCurrentUserCompanyRoles(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getRestUser()->getIdCompany()) {
            return $this->companyRoleRestResponseBuilder->createCompanyUserNotSelectedError();
        }

        $companyRoleCollectionTransfer = $this->companyRoleClient->getCompanyRoleCollection(
            (new CompanyRoleCriteriaFilterTransfer())->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser()),
        );

        if (!$companyRoleCollectionTransfer->getRoles()->count()) {
            return $this->companyRoleRestResponseBuilder->createCompanyRoleNotFoundError();
        }

        return $this->createCompanyRoleCollectionResponse($companyRoleCollectionTransfer);
    }

    protected function getCurrentUserCompanyRoleByUuid(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyRoleResponseTransfer = $this->companyRoleClient->findCompanyRoleByUuid(
            (new CompanyRoleTransfer())->setUuid($restRequest->getResource()->getId()),
        );

        if (
            !$companyRoleResponseTransfer->getIsSuccessful()
            || !$this->isCurrentCompanyUserInCompany($restRequest, $companyRoleResponseTransfer->getCompanyRoleTransfer())
        ) {
            return $this->companyRoleRestResponseBuilder->createCompanyRoleNotFoundError();
        }

        $restCompanyRoleAttributesTransfer = $this->companyRoleMapperInterface
            ->mapCompanyRoleTransferToRestCompanyRoleAttributesTransfer(
                $companyRoleResponseTransfer->getCompanyRoleTransfer(),
                new RestCompanyRoleAttributesTransfer(),
            );

        return $this->companyRoleRestResponseBuilder
            ->createCompanyRoleRestResponse(
                $companyRoleResponseTransfer->getCompanyRoleTransfer()->getUuid(),
                $restCompanyRoleAttributesTransfer,
                $companyRoleResponseTransfer->getCompanyRoleTransfer(),
            );
    }

    protected function createCompanyRoleCollectionResponse(CompanyRoleCollectionTransfer $companyRoleCollectionTransfer): RestResponseInterface
    {
        $companyRoleRestResources = [];

        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            $companyRoleRestResources[] = $this->companyRoleRestResponseBuilder->createCompanyRoleRestResource(
                $companyRoleTransfer->getUuid(),
                $this->getRestCompanyRoleAttributesTransfer($companyRoleTransfer),
                $companyRoleTransfer,
            );
        }

        return $this->companyRoleRestResponseBuilder
            ->createCompanyRoleCollectionRestResponse($companyRoleRestResources);
    }

    protected function getRestCompanyRoleAttributesTransfer(CompanyRoleTransfer $companyRoleTransfer): RestCompanyRoleAttributesTransfer
    {
        return $this->companyRoleMapperInterface
            ->mapCompanyRoleTransferToRestCompanyRoleAttributesTransfer(
                $companyRoleTransfer,
                new RestCompanyRoleAttributesTransfer(),
            );
    }

    protected function isCurrentCompanyUserInCompany(
        RestRequestInterface $restRequest,
        CompanyRoleTransfer $companyRoleTransfer
    ): bool {
        return $restRequest->getRestUser()
            && $restRequest->getRestUser()->getIdCompany()
            && $restRequest->getRestUser()->getIdCompany() === $companyRoleTransfer->getFkCompany();
    }

    protected function isResourceIdentifierCurrentUser(string $resourceIdentifier): bool
    {
        return $resourceIdentifier === CompanyRolesRestApiConfig::COLLECTION_IDENTIFIER_CURRENT_USER;
    }
}
