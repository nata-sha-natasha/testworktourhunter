<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181002_021135_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'balance' => $this->double()->notNull()->defaultValue(0),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string(32)->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
