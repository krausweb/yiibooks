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
            [['id', 'date_update', 'author_id'], 'integer'],
            [['name', 'preview', 'author', 'author_fullname', 'date', 'date_create'], 'safe'], // Additional 'author|safe' - 'Detect' data for getAuthor()
            [['date_update', 'book_date_from','book_date_to'], 'date', 'format' => 'dd/MM/yyyy'],
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
            ->addSelect([$this->tableName().".*", "CONCAT(authors.firstname, ' ', authors.lastname) AS author_fullname"])
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

        // поиск по названию книги
        $query->andFilterWhere(['like', 'books.name', $this->name]);

        // поиск по дате от/до
        if(!empty($this->book_date_from) and !empty($this->book_date_to)) {
            // конвертирую в корректный формат времени и добавляю четкие временные границы
            $book_date_from = Yii::$app->formatter->asDatetime( str_replace("/", "-", $this->book_date_from), 'dd-MM-yyyy 00:00:00');
            $book_date_to =  Yii::$app->formatter->asDatetime( str_replace("/", "-", $this->book_date_to), 'dd-MM-yyyy 23:59:59');
            $book_date_from = Yii::$app->formatter->asTimestamp($book_date_from);
            $book_date_to = Yii::$app->formatter->asTimestamp($book_date_to);
            $query->andFilterWhere(['between', 'books.date', $book_date_from, $book_date_to ]);
        }

        // поиск по автору
        if($this->author_fullname == 7
            or in_array(trim(mb_strtolower($this->author)), array('без', 'без привязки', 'без привязки к автору'))){
            // для книг без привязки к автору - из books
            $query->andFilterWhere(['=', 'author_id', 0]);
        }else{
            // из authors
            $query->andFilterWhere(['like', 'authors.firstname', $this->author]);
            $query->andFilterWhere(['=', 'authors.id', $this->author_fullname]);
        }

        /////////////////////// доп параметры для filterModel (если раскомменчены filterModel и Pjax в books/index.php)
        $query->andFilterWhere(['=', 'books.id', $this->id])
            ->andFilterWhere(['like', 'books.preview', $this->preview]);

        // дата Выхода книги
        if(!empty($this->date)) {
            // конвертирую в корректный формат времени и добавляю четкие временные границы
            $book_date_from = Yii::$app->formatter->asDatetime( str_replace("/", "-", $this->date), 'dd-MM-yyyy 00:00:00');
            $book_date_to =  Yii::$app->formatter->asDatetime( str_replace("/", "-", $this->date), 'dd-MM-yyyy 23:59:59');
            $book_date_from = Yii::$app->formatter->asTimestamp($book_date_from);
            $book_date_to = Yii::$app->formatter->asTimestamp($book_date_to);
            $query->andFilterWhere(['between', 'books.date', $book_date_from, $book_date_to ]);
        }

        // дата Добавления книги
        if(!empty($this->date_create)) {
            // конвертирую в корректный формат времени и добавляю четкие временные границы
            $book_date_from = Yii::$app->formatter->asDatetime( str_replace("/", "-", $this->date_create), 'dd-MM-yyyy 00:00:00');
            $book_date_to =  Yii::$app->formatter->asDatetime( str_replace("/", "-", $this->date_create), 'dd-MM-yyyy 23:59:59');
            $book_date_from = Yii::$app->formatter->asTimestamp($book_date_from);
            $book_date_to = Yii::$app->formatter->asTimestamp($book_date_to);
            $query->andFilterWhere(['between', 'books.date_create', $book_date_from, $book_date_to ]);
        }
        ///////////////////

        return $dataProvider;
    }
}
