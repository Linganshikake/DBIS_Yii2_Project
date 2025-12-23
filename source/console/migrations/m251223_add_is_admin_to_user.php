<?php

use yii\db\Migration;

/**
 * 添加 is_admin 字段到 user 表
 */
class m251223_add_is_admin_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'is_admin', $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('是否为管理员 0:否 1:是'));
        
        // 将 ID=1 的用户设为管理员（通常是第一个注册的用户）
        $this->update('{{%user}}', ['is_admin' => 1], ['id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'is_admin');
    }
}
