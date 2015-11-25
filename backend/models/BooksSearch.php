<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BooksSearch represents the model behind the search form about `app\models\Books`.
 */
class BooksSearch extends Books
{
    /**
     * @var string - additional for getAuthor()
     */
    public $author;
    /**
     * @var string - additional mysql CONCAT field Author fullname
     */
    public $author_fullname;

    /**
     * @var string - for search from|to
     */
    public $book_date_from;
    public $book_date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date_create', 'date_update', 'date', 'author_id'], 'integer'],
            [['name', 'preview', 'author', 'author_fullname'], 'safe'], // Additional 'author|safe' - 'Detect' data for getAuthor()
            [['date', 'date_update', 'date_create', 'book_date_from','book_date_to'], 'date', 'format' => 'dd/MM/yyyy'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Books::find()
            ->addSelect([$this->tableName().".*", "CONCAT(firstname, ' ', lastname) AS author_fullname"])
            ->joinWith(['author']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ],
            ]*/
        ]);
        $dataProvider->sort->attributes['author'] = [
            'asc' => ['authors.firstname' => SORT_ASC],
            'desc' => ['authors.firstname' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // для поиска по названию книги
        $query->andFilterWhere(['like', 'name', $this->name]);

        // для поиска по дате от/до
        if(!empty($this->book_date_from) and !empty($this->book_date_to)) {
            $book_date_from = Yii::$app->formatter->asTimestamp(str_replace("/", "-", $this->book_date_from));
            $book_date_to = Yii::$app->formatter->asTimestamp(str_replace("/", "-", $this->book_date_to));
            $query->andFilterWhere([ 'between', 'date', $book_date_from, $book_date_to ]);
        }

        // для поиска по автору
        if($this->author_fullname == 0) {
            // для селекта "автор"
        }elseif($this->author_fullname == 7){
            // для книг без привязки к автору - из books
            $query->andFilterWhere(['=', 'author_id', 0]);
        }else{
            // из authors
            $query->andFilterWhere(['=', 'authors.id', $this->author_fullname]);
        }

        return $dataProvider;
    }
}
