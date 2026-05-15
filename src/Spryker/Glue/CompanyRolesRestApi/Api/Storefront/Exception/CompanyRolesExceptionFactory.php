<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Exception;

use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class CompanyRolesExceptionFactory
{
    // BC: legacy 501 response for GET /company-roles (no id) has no JSON:API `code` field.
    protected const string RESPONSE_CODE_RESOURCE_NOT_IMPLEMENTED = '';

    public function createCompanyRoleNotFoundException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_NOT_FOUND,
            CompanyRolesRestApiConfig::RESPONSE_CODE_COMPANY_ROLE_NOT_FOUND,
            CompanyRolesRestApiConfig::RESPONSE_DETAIL_COMPANY_ROLE_NOT_FOUND,
        );
    }

    public function createCompanyUserNotSelectedException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_FORBIDDEN,
            CompanyRolesRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_SELECTED,
            CompanyRolesRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_SELECTED,
        );
    }

    public function createResourceNotImplementedException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_NOT_IMPLEMENTED,
            static::RESPONSE_CODE_RESOURCE_NOT_IMPLEMENTED,
            CompanyRolesRestApiConfig::RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED,
        );
    }
}
