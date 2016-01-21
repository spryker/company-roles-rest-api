<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Category\Business\Tree\Exception\NodeNotFoundException;
use Spryker\Zed\Category\Persistence\CategoryQueryContainer;
use Orm\Zed\Category\Persistence\SpyCategoryNode;

class NodeWriter implements NodeWriterInterface
{

    const CATEGORY_URL_IDENTIFIER_LENGTH = 4;

    /**
     * @var CategoryQueryContainer
     */
    protected $queryContainer;

    /**
     * @param CategoryQueryContainer $queryContainer
     */
    public function __construct(CategoryQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @throws PropelException
     *
     * @return int
     */
    public function create(NodeTransfer $categoryNode)
    {
        $nodeEntity = new SpyCategoryNode();
        $nodeEntity->fromArray($categoryNode->toArray());
        $nodeEntity->save();

        $nodeId = $nodeEntity->getIdCategoryNode();
        $categoryNode->setIdCategoryNode($nodeId);

        return $nodeId;
    }

    /**
     * @param int $nodeId
     *
     * @throws NodeNotFoundException
     * @throws PropelException
     *
     * @return int
     */
    public function delete($nodeId)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($nodeId)
            ->findOne();
        if (!$nodeEntity) {
            throw new NodeNotFoundException();
        }
        $categoryId = $nodeEntity->getFkCategory();
        $nodeEntity->delete();

        return $categoryId;
    }

    /**
     * @param NodeTransfer $categoryNode
     *
     * @throws PropelException
     *
     * @return void
     */
    public function update(NodeTransfer $categoryNode)
    {
        $nodeEntity = $this->queryContainer
            ->queryNodeById($categoryNode->getIdCategoryNode())
            ->findOne();
        if ($nodeEntity) {
            $nodeEntity->fromArray($categoryNode->toArray());
            $nodeEntity->save();
        }
    }

}