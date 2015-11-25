<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * @var string additional mysql CONCAT field Author fullname
     */
    public $author_fullname;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname', 'author_fullname'], 'string', 'max' => 64],
            [['author_fullname'],'safe'],
            [['lastname'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'firstname' => Yii::t('app', 'Author firstname'),
            'lastname' => Yii::t('app', 'Author lastname'),
            'author_fullname' => Yii::t('app', 'Author full name'),
        ];
    }
}
