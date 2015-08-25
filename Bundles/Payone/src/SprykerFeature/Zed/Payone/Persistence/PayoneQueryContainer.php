<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Payone\Persistence\Propel\Base\SpyPaymentPayoneQuery;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLog;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogOrderItem;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogOrderItemQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneTransactionStatusLogQuery;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneApiLogQuery;

class PayoneQueryContainer extends AbstractQueryContainer implements PayoneQueryContainerInterface
{

    /**
     * @param int $transactionId
     *
     * @return SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function getCurrentSequenceNumberQuery($transactionId)
    {
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create();
        $query->filterByTransactionId($transactionId)
              ->orderBySequenceNumber(Criteria::DESC);

        return $query;
    }

    /**
     * @param int $transactionId
     *
     * @return Propel\SpyPaymentPayoneQuery
     */
    public function getPaymentByTransactionIdQuery($transactionId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->filterByTransactionId($transactionId);

        return $query;
    }

    /**
     * @param int $fkPayment
     * @param string $requestName
     *
     * @return SpyPaymentPayoneApiLogQuery
     */
    public function getApiLogByPaymentAndRequestTypeQuery($fkPayment, $requestName)
    {
        $query = SpyPaymentPayoneApiLogQuery::create();
        $query->filterByFkPaymentPayone($fkPayment)
              ->filterByRequest($requestName);

        return $query;
    }

    /**
     * @param int $orderId
     *
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentByOrderId($orderId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->findByIdSalesOrder($orderId);

        return $query;
    }

    /**
     * @param int $orderId
     * @param string $request
     *
     * @return SpyPaymentPayoneApiLog
     */
    public function getApiLogByOrderIdAndRequest($orderId, $request)
    {
        $query = SpyPaymentPayoneApiLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByIdSalesOrder($orderId)
            ->endUse()
            ->filterByRequest($request)
            ->orderByCreatedAt(Criteria::DESC) //TODO: Index?
            ->orderByIdPaymentPayoneApiLog(Criteria::DESC)
            ->findOne();

        return $query;
    }

    /**
     * @param int $paymentId
     * 
     * @return SpyPaymentPayoneQuery
     */
    public function getPaymentById($paymentId)
    {
        $query = SpyPaymentPayoneQuery::create();
        $query->findByIdSalesOrder($paymentId);

        return $query;
    }

    /**
     * @param int $salesOrderId
     * @return SpyPaymentPayoneTransactionStatusLog[]
     */
    public function getTransactionStatusLogBySalesOrder($salesOrderId)
    {
        $query = SpyPaymentPayoneTransactionStatusLogQuery::create()
            ->useSpyPaymentPayoneQuery()
            ->filterByIdSalesOrder($salesOrderId)
            ->endUse()
            ->orderByCreatedAt()
        ;

        return $query
            ->find()
            ->getData();
    }

    /**
     * @param int $salesOrderItemId
     * @param array $ids
     * @return SpyPaymentPayoneTransactionStatusLogOrderItem[]
     */
    public function getTransactionStatusLogOrderItemsByLogIds($salesOrderItemId, $ids)
    {
        $relations = SpyPaymentPayoneTransactionStatusLogOrderItemQuery::create()
            ->filterByIdPaymentPayoneTransactionStatusLog($ids)
            ->filterByIdSalesOrderItem($salesOrderItemId)
            ->find()
            ->getData()
        ;
        return $relations;
    }

}
