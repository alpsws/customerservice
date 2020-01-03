<?php
namespace Alps\Customerservice\Model;

/*use Alps\Popup\Api\Data\PopupInterface;*/

class Customerservice extends \Magento\Framework\Model\AbstractModel
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'alps_customerservice'; //table name

    /**
     * @var string
     */
    protected $_cacheTag = 'alps_customerservice'; 

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'alps_customerservice';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Alps\Customerservice\Model\ResourceModel\Customerservice');
    }
   
}
