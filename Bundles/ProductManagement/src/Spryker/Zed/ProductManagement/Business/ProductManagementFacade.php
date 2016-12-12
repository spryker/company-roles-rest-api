<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Business;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementBusinessFactory getFactory()
 */
class ProductManagementFacade extends AbstractFacade implements ProductManagementFacadeInterface
{

    /**
     * Specification:
     * - Returns list of ALL product management attributes
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductAttributeCollection()
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getProductAttributeCollection();
    }

    /**
     * Specification:
     * - Searches for an existing product attribute key entity by the provided key in database or create it if does not exist
     * - Creates a new product management attribute entity with the given data and the found/created attribute key entity
     * - Creates a glossary key for the product attribute key with the configured prefix if does not exist already
     * - Saves predefined product attribute values if provided
     * - Returns a transfer that also contains the ids of the created entities
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function createProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        return $this->getFactory()
            ->createAttributeWriter()
            ->createProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * Specification:
     * - Searches for an existing product attribute key entity in database by the provided key or create it if does not exist
     * - Updates an existing product management attribute entity by id with the given data and the found/created attribute key entity
     * - Creates a glossary key for the product attribute key with the configured prefix if does not exist already
     * - Saves predefined product attribute values if provided
     * - Removes old predefined product attribute values which were persisted earlier but are not used any more
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    public function updateProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        return $this->getFactory()
            ->createAttributeWriter()
            ->updateProductManagementAttribute($productManagementAttributeTransfer);
    }

    /**
     * Specification:
     * - Saves product attribute key translation to the glossary
     * - Saves predefined attribute value translations if provided
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return void
     */
    public function translateProductManagementAttribute(ProductManagementAttributeTransfer $productManagementAttributeTransfer)
    {
        $this->getFactory()
            ->createAttributeTranslator()
            ->saveProductManagementAttributeTranslation($productManagementAttributeTransfer);
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText = '', $offset = 0, $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getAttributeValueSuggestions($idProductManagementAttribute, $idLocale, $searchText, $offset, $limit);
    }

    /**
     * @api
     *
     * @param int $idProductManagementAttribute
     * @param int $idLocale
     * @param string $searchText
     *
     * @return int
     */
    public function getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText = '')
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getAttributeValueSuggestionsCount($idProductManagementAttribute, $idLocale, $searchText);
    }

    /**
     * Specification:
     * - Reads a product management attribute entity from the database and returns a fully hydrated transfer representation
     * - Return null if the entity is not found by id
     *
     * @api
     *
     * @param int $idProductManagementAttribute
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function getProductManagementAttribute($idProductManagementAttribute)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->getAttribute($idProductManagementAttribute);
    }

    /**
     * Specification:
     * - Returns a filtered list of keys that exists in the persisted product attribute key list but not in the persisted
     * product management attribute list
     *
     * @api
     *
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestUnusedAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->getFactory()
            ->createAttributeReader()
            ->suggestUnusedKeys($searchText, $limit);
    }

}