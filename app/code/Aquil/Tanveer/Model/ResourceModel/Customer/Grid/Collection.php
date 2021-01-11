<?php
/**
* @author Tanveer Mohammad <akhil.tanveer@gmail.com>
* @package   Aquil\Tanveer
* @since     1.0 First time this was introduced.
* @copyright 2020 AquilTanveer
*/
namespace Aquil\Tanveer\Model\ResourceModel\Customer\Grid;

use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
   /**
    * {@inheritdoc}
    */
    // @codingStandardsIgnoreStart
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        $mainTable,
        $resourceModel
    ) {
      // @codingStandardsIgnoreEnd
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }
}
