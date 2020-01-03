<?php
/**
 * Grid Grid ResourceModel.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2016 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Alps\Customerservice\Model\ResourceModel;


use Alps\Customerservice\Model\Customerservice as CustomerserviceModel;
use Magento\Framework\Model\AbstractModel;
/**
 * Grid Grid mysql resource.
 */
class Customerservice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'customer_service_id';
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Construct.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime       $date
     * @param string|null                                       $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('alps_customerservice', 'customer_service_id');
    }

    protected function _afterSave(AbstractModel $object)
    {
        $this->saveStoreRelation($object);
        return parent::_afterSave($object);
    }

    protected function _beforeDelete(AbstractModel $object)
    {
        $condition = ['customer_service_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('alps_customerservice_store'), $condition);
        return parent::_beforeDelete($object);
    }

   /* protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId()
            ];
            $select->join(
                [
                    'alps_customerservice_store' => $this->getTable('alps_customerservice_store')
                ],
                $this->getMainTable() . '.customer_service_id = alps_customerservice_store.customer_service_id',
                []
            )
                ->where(
                    'alps_customerservice_store.store_id IN (?)',
                    $storeIds
                )
                ->order('alps_customerservice_store.store_id DESC')
                ;
        }
        return $select;
    }*/

    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }        
        return parent::_afterLoad($object);
    }

    protected function saveStoreRelation(CustomerserviceModel $customerservice)
    {
        $oldStores = $this->lookupStoreIds($customerservice->getId());
        $newStores = (array)$customerservice->getStoreId();
        if (empty($newStores)) {
            $newStores = (array)$customerservice->getStoreId();
        }
        $table = $this->getTable('alps_customerservice_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = [
                'customer_service_id = ?' => (int)$customerservice->getId(),
                'store_id IN (?)' => $delete
            ];
            $this->getConnection()->delete($table, $where);
        }
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    'customer_service_id' => (int)$customerservice->getId(),
                    'store_id' => (int)$storeId
                ];
            }
            $this->getConnection()->insertMultiple($table, $data);
        }
        return $this;
    }

    public function lookupStoreIds($customerserviceid)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()->from(
            $this->getTable('alps_customerservice_store'),
            'store_id'
        )->where(
            'customer_service_id = ?',
            (int)$customerserviceid
        );
        return $adapter->fetchCol($select);
    }
}
