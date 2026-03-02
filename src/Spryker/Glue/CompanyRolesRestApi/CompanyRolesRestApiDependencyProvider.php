<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\CompanyRolesRestApi\Dependency\Client\CompanyRolesRestApiToCompanyRoleClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig getConfig()
 */
class CompanyRolesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_COMPANY_ROLE = 'CLIENT_COMPANY_ROLE';

    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyRoleClient($container);

        return $container;
    }

    protected function addCompanyRoleClient(Container $container): Container
    {
        $container->set(static::CLIENT_COMPANY_ROLE, function (Container $container) {
            return new CompanyRolesRestApiToCompanyRoleClientBridge(
                $container->getLocator()->companyRole()->client(),
            );
        });

        return $container;
    }
}
