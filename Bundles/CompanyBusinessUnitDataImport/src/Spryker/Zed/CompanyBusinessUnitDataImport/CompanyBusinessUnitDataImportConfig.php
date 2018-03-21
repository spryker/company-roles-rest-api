<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\DataImportConfig;

class CompanyBusinessUnitDataImportConfig extends DataImportConfig
{
    const IMPORT_TYPE_COMPANY_BUSINESS_UNIT = 'company-business-unit';

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCompanyBusinessUnitDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $moduleDataImportDirectory = $this->getModuleRoot() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'import';

        return $this->buildImporterConfiguration($moduleDataImportDirectory . DIRECTORY_SEPARATOR . 'company_business_unit.csv', static::IMPORT_TYPE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = realpath(
            __DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
        );

        return $moduleRoot;
    }
}
