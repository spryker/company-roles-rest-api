<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Communication\Table\ProductTable;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;
use SprykerFeature\Zed\Product\ProductConfig;
use SprykerFeature\Zed\Product\ProductDependencyProvider;
use SprykerFeature\Zed\ProductCategory\Persistence\ProductCategoryQueryContainerInterface;
use SprykerFeature\Zed\ProductOption\Business\ProductOptionFacade;
use SprykerFeature\Zed\Url\Business\UrlFacade;

/**
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductConfig getConfig()
 * @method ProductCommunication getFactory()
 */
class ProductDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return ProductFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return ProductTable
     */
    public function createProductTable()
    {
        $productQuery = $this->getQueryContainer()->queryAbstractProducts();

        $locale = $this->createLocaleFacade()->getCurrentLocale();
        $localeTransfer = (new LocaleTransfer())->fromArray($locale->toArray());

        return $this->getFactory()->createTableProductTable(
            $productQuery,
            $this->createUrlFacade(),
            $localeTransfer,
            $this->getConfig()->getHostYves()
        );
    }

    /**
     * @return LocaleFacade
     */
    public function createLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return ProductOptionFacade
     */
    public function createProductOptionsFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_OPTION);
    }

    /**
     * @return UrlFacade
     */
    public function createUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return ProductCategoryQueryContainerInterface
     */
    public function createProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

}
