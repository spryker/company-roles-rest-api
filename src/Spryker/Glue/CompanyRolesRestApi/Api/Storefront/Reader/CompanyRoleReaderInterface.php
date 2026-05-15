<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CompanyRolesRestApi\Api\Storefront\Reader;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;

interface CompanyRoleReaderInterface
{
    public function findCompanyRoleByUuid(string $uuid): ?CompanyRoleTransfer;

    public function getCompanyRoleCollectionByIdCompanyUser(int $idCompanyUser): CompanyRoleCollectionTransfer;

    /**
     * @param list<int> $idCompanyUsers
     *
     * @return array<int, array<\Generated\Shared\Transfer\CompanyRoleTransfer>>
     */
    public function getCompanyRolesGroupedByIdCompanyUser(array $idCompanyUsers): array;
}
