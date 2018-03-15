<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Zed\CompanyGui\Communication\Form\CompanyForm;
use Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyFormDataProvider;
use Spryker\Zed\CompanyGui\Communication\Table\CompanyTable;
use Spryker\Zed\CompanyGui\CompanyGuiDependencyProvider;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyGui\Communication\Table\CompanyTable
     */
    public function createCompanyTable(): CompanyTable
    {
        $companyQuery = $this->getPropelCompanyQuery();

        return new CompanyTable($companyQuery);
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCompanyForm($data = null, array $options = [])
    {
        return $this->getFormFactory()->create(CompanyForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CompanyGui\Communication\Form\DataProvider\CompanyFormDataProvider
     */
    public function createCompanyFormDataProvider(): CompanyFormDataProvider
    {
        return new CompanyFormDataProvider(
            $this->getCompanyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyGuiToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(CompanyGuiDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Orm\Zed\Company\Persistence\SpyCompanyQuery
     */
    protected function getPropelCompanyQuery(): SpyCompanyQuery
    {
        return $this->getProvidedDependency(CompanyGuiDependencyProvider::PROPEL_COMPANY_QUERY);
    }
}
