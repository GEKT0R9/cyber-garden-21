<?php

use yii\db\Migration;

/**
 * Class m211211_071256_StartDb
 */
class m211211_071256_StartDb extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            'users',
            [
                'id' => $this->primaryKey(),
                'last_name' => $this->string(50)->notNull(),
                'first_name' => $this->string(50)->notNull(),
                'middle_name' => $this->string(50),
                'username' => $this->string(50)->notNull()->unique(),
                'email' => $this->string(50)->notNull()->unique(),
                'password' => $this->string(256)
            ]
        );
        $this->insert(
            'users',
            [
                'last_name' => 'admin',
                'first_name' => 'admin',
                'middle_name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@admin.admin',
                'password' => '$2y$10$VYhgVh19t21sDBJvwlfQmuEk1mu44OfLLqnsVoWwHqHQ3DmlEcd/a',
            ]
        );
        
        $this->createTable(
            'users_emails',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->primaryKey(),
                'email' => $this->string(50)->notNull()->unique(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211211_071256_StartDb cannot be reverted.\n";

        return false;
    }
    */
}
