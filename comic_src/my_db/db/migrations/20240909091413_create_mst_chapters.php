<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMstChapters extends AbstractMigration
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
        $table = $this->table("mst_chapters");
        $table
            ->addColumn('title_id', 'integer',['null'=> true,'signed' => false])
            ->addColumn('name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('start_date', 'datetime', ['null' => true])

            // 'created_at' カラムを追加 (デフォルトで現在の日時を設定)
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false
            ])

            // 'updated_at' カラムを追加 (デフォルトで現在の日時を設定し、更新時に自動更新)
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'null' => false
            ])
            // 外部キー制約を追加。削除時にtitle_idをNULLにする。
            ->addForeignKey('title_id', 'mst_titles', 'id', [
                'delete' => 'SET NULL',
                'update' => 'NO_ACTION',
            ])
            ->create();
            $this->table('mst_chapters', ['engine' => 'InnoDB']);
    }

}
