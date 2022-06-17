<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CartsProductsMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('carts_products');
        $table
            ->addColumn('cart_id', 'integer', [
                'null' => false
            ])
            ->addColumn('product_id', 'integer', [
                'null' => false
            ])
            ->addColumn('product_quantity', 'integer', [
                'null' => false
            ])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP'
            ])
            ->addForeignKey('cart_id', 'carts', 'id', [
                'constraint' => 'cart_pivot',
                'delete'=> 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('product_id', 'products', 'id', [
                'constraint' => 'product_pivot',
                'delete'=> 'RESTRICT',
                'update'=> 'CASCADE'
            ]);
        $table->create();
    }
}
