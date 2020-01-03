<?php
namespace Alps\Customerservice\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Upgrade the Catalog module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();        

        if (version_compare($context->getVersion(), '2.0.1', '<')) {

            if (!$setup->tableExists('alps_customerservice_store')) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('alps_customerservice_store'));
            $table->addColumn(
                    'customer_service_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'   => true,
                    ],
                    'Customer Service Id'
                )
                ->addColumn(
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    [
                        'unsigned'  => true,
                        'nullable'  => false,
                        'primary'   => true,
                    ],
                    'Store ID'
                )
                ->addIndex(
                    $setup->getIdxName('alps_customerservice_store', ['store_id']),
                    ['store_id']
                )
                ->addForeignKey(
                    $setup->getFkName('alps_customerservice_store', 'customer_service_id', 'alps_customerservice', 'customer_service_id'),
                    'customer_service_id',
                    $setup->getTable('alps_customerservice'),
                    'customer_service_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $setup->getFkName('alps_customerservice_store', 'store_id', 'store', 'store_id'),
                    'store_id',
                    $setup->getTable('store'),
                    'store_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('Customerservice To Store Link Table');
                $setup->getConnection()->createTable($table);
            }        
        }

        $setup->endSetup();
    }
}
