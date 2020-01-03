<?php

namespace Alps\Customerservice\Model\ResourceModel\Customerservice;

use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'customer_service_id';
    

    protected function _construct()
    {
        $this->_init(
            'Alps\Customerservice\Model\Customerservice',
            'Alps\Customerservice\Model\ResourceModel\Customerservice'
        );
        $this->_map['fields']['customer_service_id'] = 'main_table.customer_service_id';
        $this->_map['fields']['store_id'] = 'store_table.store_id';
    }


    protected function _afterLoad()
    {             
        return parent::_afterLoad();
    }

    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(\Zend_Db_Select::GROUP);
        return $countSelect;
    }
    

    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'store_id') {
            return $this->addStoreFilter($condition, false);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Store) {
                $store = [$store->getId()];
            }

            if (!is_array($store)) {
                $store = [$store];
            }

            if ($withAdmin) {
                $store[] = Store::DEFAULT_STORE_ID;
            }

            $this->addFilter('store_id', ['in' => $store], 'public');
        }
        return $this;
    }


    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable('alps_customerservice_store')],
                'main_table.customer_service_id = store_table.customer_service_id',
                []
            )
            // @codingStandardsIgnoreStart
            ->group('main_table.customer_service_id');
            // @codingStandardsIgnoreEnd
        }
        parent::_renderFiltersBefore();
    }
}
