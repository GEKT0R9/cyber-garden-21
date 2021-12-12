<?php

use yii\db\Expression;
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
            'account',
            [
                'id' => $this->primaryKey(),
                'last_name' => $this->string(50)->notNull(),
                'first_name' => $this->string(50)->notNull(),
                'middle_name' => $this->string(50),
                'username' => $this->string(50)->notNull()->unique(),
                'email' => $this->string(50)->notNull()->unique(),
                'password' => $this->string(256),
                'avatar_id' => $this->integer()
            ]
        );
        $this->insert(
            'account',
            [
                'last_name' => 'admin',
                'first_name' => 'admin',
                'middle_name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@admin.admin',
                'password' => '21232f297a57a5a743894a0e4a801fc3',
            ]
        );

        $this->createTable(
            'users',
            [
                'id' => $this->primaryKey(),
                'last_name' => $this->string(50)->notNull(),
                'first_name' => $this->string(50)->notNull(),
                'middle_name' => $this->string(50),
            ]
        );
        
        $this->createTable(
            'users_emails',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'email' => $this->string(50)->notNull()->unique(),
            ]
        );
        $this->addForeignKey(
            'user_id_fk_users_emails',
            'users_emails',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );


        $this->createTable(
            'users_phones',
            [
                'id' => $this->primaryKey(),
                'user_id' => $this->integer()->notNull(),
                'phone' => $this->string(50)->notNull()->unique(),
            ]
        );
        $this->addForeignKey(
            'user_id_fk_users_phones',
            'users_phones',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            'requests',
            [
                'id' => $this->primaryKey(),
                'doc_id' => $this->string(100)->notNull(),
                'title' => $this->string(100)->notNull(),
                'description' => $this->string(500)->notNull(),
                'date' => $this->dateTime()->defaultValue(new Expression('NOW()')),
                'status_id' => $this->integer()->defaultValue(1),
                'user_id' => $this->integer(),
                'last_account_id' => $this->integer()
            ]
        );
        $this->addForeignKey(
            'last_account_id_fk_requests',
            'requests',
            'last_account_id',
            'account',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createTable(
            'request_addresses',
            [
                'id' => $this->primaryKey(),
                'request_id' => $this->integer()->notNull(),
                'address' => $this->string(100)->notNull(),
                'okato_id' => $this->string(100)->notNull(),
            ]
        );
        $this->addForeignKey(
            'request_id_fk_request_addresses',
            'request_addresses',
            'request_id',
            'requests',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            'files',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'file_content' => 'bytea',
                'size' => $this->integer(),
                'permission' => $this->string(50)->notNull()
            ]
        );

        $this->addForeignKey(
            'avatar_id_fk_account',
            'account',
            'avatar_id',
            'files',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            'request_to_files',
            [
                'id' => $this->primaryKey(),
                'request_id' => $this->integer()->notNull(),
                'file_id' => $this->integer()->notNull(),
            ]
        );

        $this->addForeignKey(
            'request_id_fk_request_to_files',
            'request_to_files',
            'request_id',
            'requests',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'file_id_fk_request_to_files',
            'request_to_files',
            'file_id',
            'files',
            'id',
            'SET NULL',
            'CASCADE'
        );


        $this->createTable(
            'access',
            [
                'id' => $this->primaryKey(),
                'access' => $this->string(50)->notNull(),
                'description' => $this->string(100)->notNull(),
            ]
        );
        $this->createTable(
            'roles',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'description' => $this->string(100),
            ]
        );
        $this->insert(
            'roles',
            ['name' => 'Администратор']
        );

        $this->createTable(
            'accounts_to_role',
            [
                'id' => $this->primaryKey(),
                'account_id' => $this->integer()->notNull(),
                'role_id' => $this->integer()->notNull(),
            ]
        );
        $this->addForeignKey(
            'account_id_fk_accounts_to_role',
            'accounts_to_role',
            'account_id',
            'account',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'role_id_fk_accounts_to_role',
            'accounts_to_role',
            'role_id',
            'roles',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->insert(
            'accounts_to_role',
            ['account_id' => 1, 'role_id' => 1]
        );

        $this->createTable(
            'role_to_access',
            [
                'id' => $this->primaryKey(),
                'role_id' => $this->integer()->notNull(),
                'access_id' => $this->integer()->notNull(),
            ]
        );
        $this->addForeignKey(
            'role_id_fk_role_to_access',
            'role_to_access',
            'role_id',
            'roles',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'access_id_fk_role_to_access',
            'role_to_access',
            'access_id',
            'access',
            'id',
            'CASCADE',
            'CASCADE'
        );


        $this->createTable(
            'dir_tags',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'description' => $this->string(500),
            ]
        );

        $this->createTable(
            'request_to_tag',
            [
                'id' => $this->primaryKey(),
                'request_id' => $this->integer()->notNull(),
                'tag_id' => $this->integer()->notNull(),
            ]
        );
        $this->addForeignKey(
            'request_id_fk_request_to_tag',
            'request_to_tag',
            'request_id',
            'requests',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'tag_id_fk_request_to_tag',
            'request_to_tag',
            'tag_id',
            'dir_tags',
            'id',
            'CASCADE',
            'CASCADE'
        );


        $this->createTable(
            'dir_status',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'description' => $this->string(500),
            ]
        );

        $this->createTable(
            'status_order',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(50)->notNull(),
                'description' => $this->string(250),
                'type_id' => $this->integer()->notNull(),
                'order' => $this->integer()->notNull(),
            ]
        );

        $this->addForeignKey(
            'status_id_fk_requests',
            'requests',
            'status_id',
            'status_order',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'type_id_fk_status_order',
            'status_order',
            'type_id',
            'dir_status',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable(
            'dir_okato',
            [
                'id' => $this->primaryKey(),
                'okato_id' => $this->string(100)->notNull()->unique(),
                'region' => $this->string(250),
            ]
        );
        // request_addresses

        $this->addForeignKey(
            'okato_id_fk_request_addresses',
            'request_addresses',
            'okato_id',
            'dir_okato',
            'okato_id',
            'CASCADE',
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('account');
        $this->dropTable('users');
        $this->dropTable('users_emails');
        $this->dropTable('users_phones');
        $this->dropTable('requests');
        $this->dropTable('files');
        $this->dropTable('request_to_files');
        $this->dropTable('access');
        $this->dropTable('roles');
        $this->dropTable('accounts_to_role');
        $this->dropTable('role_to_access');
        $this->dropTable('dir_status');
        $this->dropTable('status_order');
        $this->dropTable('dir_okato');
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
