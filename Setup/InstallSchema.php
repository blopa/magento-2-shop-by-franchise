<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2016-01-05 10:40:51
 * @@Modify Date: 2016-03-23 22:57:08
 * @@Function:
 */

namespace Magiccart\Shopfranchise\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('magiccart_shopfranchise'))
            ->addColumn(
                'shopfranchise_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Franchise ID'
            )
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false], 'Title')
            ->addColumn('urlkey', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('meta_key', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('meta_description', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('option_id', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('image', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('product_ids', Table::TYPE_TEXT, '1M', [], 'product_ids')
            ->addColumn('stores', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('description', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('order', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '0'], 'Order')
            ->addColumn('status', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Status')
            ->addIndex($installer->getIdxName('shopfranchise_id', ['shopfranchise_id']), ['shopfranchise_id'])
            ->setComment('Magiccart Shopfranchise');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
