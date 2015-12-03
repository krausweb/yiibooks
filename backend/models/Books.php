<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%books}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $date_create
 * @property integer $date_update
 * @property string $preview
 * @property integer $date
 * @property integer $author_id
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * @var string additional mysql CONCAT field Author fullname
     */
    public $author_fullname;
    /**
     * @var string for upload image
     */
    public $upload_preview;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%books}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date'], 'required'],
            [['author_id', 'date', 'date_update', 'date_create', 'author_fullname'],'safe'],
            [['name', 'preview', 'author_fullname'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['upload_preview'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Book name'),
            'preview' => Yii::t('app', 'Book preview'),
            'author_id' => Yii::t('app', 'Book author id'),
            'date' => Yii::t('app', 'Book date'),
            'date_create' => Yii::t('app', 'Book date create'),
            'date_update' => Yii::t('app', 'Book date update'),
            'author' => Yii::t('app', 'Book author name'),
            'author_fullname'=> Yii::t('app', 'Author full name'),
            'book_date_from'=> Yii::t('app','Book date create from'),
            'book_date_to'=> Yii::t('app','Book date create to'),
            'upload_preview'=> Yii::t('app', 'Upload preview'),
        ];
    }

    /**
     * @inheritdoc
     * @return BooksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BooksQuery(get_called_class());
    }

    /**
     * @param $date string
     * @return string
     */
    public function getBooksRelativeDate($date){
        $date_create = Yii::$app->formatter->asDate($date);
        if($date_create == Yii::$app->formatter->asDate('now')){
            $date_relative =  Yii::t('app', 'Today');
        }elseif($date_create == Yii::$app->formatter->asDate('now - 1day')){
            $date_relative = Yii::t('app', 'Yesterday');
        }else{
            $date_relative = Yii::$app->formatter->asRelativeTime($date);
        }
        return $date_relative;
    }

    /**
     * @return \yii\db\ActiveQuery get one Author
     */
    public function getAuthor(){
        return $this->hasOne(Authors::className(), ['id'=>'author_id']);
    }

    /**
     * get Authors array for select box and other
     * @return array
     */
    public function getAuthors(){
        return ArrayHelper::map( Authors::find()
                                     ->addSelect([Authors::tableName().".*", "CONCAT(firstname, ' ', lastname) AS author_fullname"])
                                     ->all()
                                , 'id', 'author_fullname');
    }
}
