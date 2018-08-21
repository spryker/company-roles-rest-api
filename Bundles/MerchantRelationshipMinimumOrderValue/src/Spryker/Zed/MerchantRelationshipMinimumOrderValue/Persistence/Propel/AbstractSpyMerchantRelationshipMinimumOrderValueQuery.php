<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Base\SpyMerchantRelationshipMinimumOrderValueQuery as BaseSpyMerchantRelationshipMinimumOrderValueQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_merchant_relationship_min_order_value' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
abstract class AbstractSpyMerchantRelationshipMinimumOrderValueQuery extends BaseSpyMerchantRelationshipMinimumOrderValueQuery
{
    /**
     * @module Store
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return $this|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function filterByStoreTransfer(StoreTransfer $storeTransfer)
    {
        return $this->_if($storeTransfer->getIdStore() !== null)
                ->filterByFkStore($storeTransfer->getIdStore())
            ->_else()
                ->useStoreQuery()
                    ->filterByName($storeTransfer->getName())
                ->endUse()
            ->_endif();
    }

    /**
     * @module Currency
     *
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return $this|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery|\Propel\Runtime\ActiveQuery\Criteria
     */
    public function filterByCurrencyTransfer(CurrencyTransfer $currencyTransfer)
    {
        return $this->_if($currencyTransfer->getIdCurrency() !== null)
                ->filterByFkCurrency($currencyTransfer->getIdCurrency())
            ->_else()
                ->useCurrencyQuery()
                    ->filterByCode($currencyTransfer->getCode())
                ->endUse()
            ->_endif();
    }
}
