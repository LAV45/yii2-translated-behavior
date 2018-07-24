<?php

use yii\db\Migration;
use lav45\translate\LocaleHelperTrait;

class m151220_112320_lang extends Migration
{
    use LocaleHelperTrait;

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            // https://stackoverflow.com/questions/30074492/what-is-the-difference-between-utf8mb4-and-utf8-charsets-in-mysql/30074553#30074553
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%lang}}', [
            'id' => $this->string(2)->notNull(),
            'locale' => $this->string(8)->notNull(),
            'name' => $this->string(32)->notNull(),
            'status' => $this->smallInteger(),
            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->createIndex('lang_name_idx', '{{%lang}}', 'name', true);
        $this->createIndex('lang_status_idx', '{{%lang}}', 'status');

        $source_language = Yii::$app->sourceLanguage;
        $source_language_id = $this->getPrimaryLanguage($source_language);

        $this->insert('{{%lang}}', [
            'id' => strtolower($source_language_id),
            'locale' => $source_language,
            'name' => strtoupper($source_language_id),
            'status' => 10,
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%lang}}');
    }
}
