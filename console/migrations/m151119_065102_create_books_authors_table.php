<?php

use yii\db\Schema;
use yii\db\Migration;

class m151119_065102_create_books_authors_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * additional - delete table with old data
         */
        if( $this->db->schema->getTableSchema('books') ) $this->dropTable('{{%books}}');

        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'date_create' => $this->integer()->notNull().' COMMENT "дата создания записи"',
            'date_update' => $this->integer()->notNull().' COMMENT "дата обновления записи"',
            'preview' => $this->string().' COMMENT "путь к картинке превью книги"',
            'date' => $this->integer().' COMMENT "дата выхода книги"',
            'author_id' => $this->integer()->notNull().' COMMENT "ид автора в таблице author"',
        ], $tableOptions);
        $this->createIndex('idx_books_name', '{{%books}}', 'name');
        $this->createIndex('idx_books_date', '{{%books}}', 'date');
        $this->createIndex('idx_books_author_id', '{{%books}}', 'author_id');


        /**
         * additional - delete table with old data
         */
        if( $this->db->schema->getTableSchema('authors') ) $this->dropTable('{{%authors}}');

        $this->createTable('{{%authors}}', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(64)->notNull().' COMMENT "имя автора"',
            'lastname' => $this->string(64)->notNull()->unique().' COMMENT "фамилия автора"',
        ], $tableOptions);
        $this->createIndex('idx_authors_firstname', '{{%authors}}', 'firstname');
        $this->createIndex('idx_authors_lastname', '{{%authors}}', 'lastname');



        /**
         *  insert test data - START
         */
        $this->insert('{{%authors}}', array(
            'firstname' => 'Jeffrey',
            'lastname' => 'Winesett'
        ));
        $id_authors = Yii::$app->db->getLastInsertID();
        $this->insert('{{%books}}', array(
            'name' => 'Web Application Development with Yii and PHP',
            'date_create' => time(),
            'date_update' => time(),
            'preview' => 'yii-book.jpg',
            'date' => 1353283200,
            'author_id' => $id_authors
        ));

        $this->insert('{{%authors}}', array(
            'firstname' => 'Alexander',
            'lastname' => 'Makarov'
        ));
        $id_authors = Yii::$app->db->getLastInsertID();
        $this->insert('{{%books}}', array(
            'name' => 'Yii Application Development Cookbook',
            'date_create' => time(),
            'date_update' => time(),
            'preview' => 'yii-cookbook-2nd.jpg',
            'date' => 1366761600,
            'author_id' => $id_authors
        ));

        $this->insert('{{%authors}}', array(
            'firstname' => 'Larry',
            'lastname' => 'Ullman'
        ));
        $id_authors = Yii::$app->db->getLastInsertID();
        $this->insert('{{%books}}', array(
            'name' => 'THE YII BOOK',
            'date_create' => time(),
            'date_update' => time(),
            'preview' => 'yii-book-larry.jpg',
            'date' => 1447000000,
            'author_id' => $id_authors
        ));

        $this->insert('{{%books}}', array(
            'name' => 'Web Application Development with Yii 2 and PHP',
            'date_create' => time(),
            'date_update' => time(),
            'preview' => 'yii-book-2nd.jpg',
            'date' => 1447100000,
            'author_id' => 0
        ));


        $this->insert('{{%authors}}', array(
            'firstname' => 'Uday',
            'lastname' => 'Sawant'
        ));
        $this->insert('{{%authors}}', array(
            'firstname' => 'Lauren',
            'lastname' => 'O\'Meara'
        ));
        $this->insert('{{%authors}}', array(
            'firstname' => 'Charles',
            'lastname' => 'Portwood II'
        ));
        /**
         *  insert test data - END
         */
    }

    public function down()
    {
        $this->dropTable('{{%books}}');
        $this->dropTable('{{%authors}}');
    }
}
