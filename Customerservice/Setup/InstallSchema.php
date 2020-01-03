<?php
namespace Alps\Customerservice\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('alps_customerservice')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('alps_customerservice')
			)
				->addColumn(
					'customer_service_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					1,
					[],
					'Status'
				)
				->addColumn(
					'title',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable' => false],
					'Title'
				)
				->addColumn(
	                'icon',
	                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
	                255,
	                [],
	                'Icon Upload File'
            	)				
				->addColumn(
					'content',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'2M',
					[],
					'Content'
				)
				->addColumn(
					'hover_icon',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Hover Icon'
				)
				->addColumn(
					'sort_number',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					3,
					[],
					'Sort Oumber'
				)			
				->addColumn(
					'url',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'url'
				)
				->addColumn(
					'custom_css',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'2M',
					[],
					'Custom CSS'
				)				
				->setComment('Customer Service Table');
			$installer->getConnection()->createTable($table);
			
		}
		$installer->endSetup();
	}
}
