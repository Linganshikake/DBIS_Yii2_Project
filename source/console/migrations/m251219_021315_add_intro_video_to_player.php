<?php

use yii\db\Migration;

/**
 * Class m251219_021315_add_intro_video_to_player
 */
class m251219_021315_add_intro_video_to_player extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('player', 'intro_video', $this->string(255)->defaultValue(null));
    }
    
    public function safeDown()
    {
        $this->dropColumn('player', 'intro_video');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251219_021315_add_intro_video_to_player cannot be reverted.\n";

        return false;
    }
    */
}
