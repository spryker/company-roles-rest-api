<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;

interface CompanyRoleMapperInterface
{
    public function mapCompanyRoleTransferToRestCompanyRoleAttributesTransfer(
        CompanyRoleTransfer $companyRoleTransfer,
        RestCompanyRoleAttributesTransfer $restCompanyRoleAttributesTransfer
    ): RestCompanyRoleAttributesTransfer;
}
