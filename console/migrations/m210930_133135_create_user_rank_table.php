<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_rank}}`.
 */
class m210930_133135_create_user_rank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_rank}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'position' => $this->integer(5),
            'fund' => $this->decimal(50, 2),
            'dividends' => $this->decimal(50, 2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_rank}}');
    }
}
