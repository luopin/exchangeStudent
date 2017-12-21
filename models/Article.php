<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $title
 * @property integer $categoryId
 * @property string $content
 * @property string $subject
 * @property string $createTime
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'subject'], 'required'],
            [['type'], 'integer'],
            [['content'], 'string'],
            [['createTime'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['subject'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'type' => 'Type',
            'content' => 'Content',
            'subject' => 'Subject',
            'createTime' => 'Create Time',
        ];
    }

	/**
	 * 查询新闻列表
	 */
	public function getList($where = array(), $limit = 20, $orderBy = 'createTime DESC')
	{
		return self::find()->where($where)->orderBy($orderBy)->limit($limit)->all();
	}

	/**
	 * 查询首页新闻
	 */
    public function getIndexNews()
    {
    	$row['news'] = $this->getList(['type' => ARTICLE_TYPE['news']], 4);
	    $row['enroll'] = $this->getList(['type' => ARTICLE_TYPE['enroll']], 1);
	    $row['information'] = $this->getList(['type' => ARTICLE_TYPE['information']], 1);

        return $row;
    }

	/**
	 * 新闻中心
	 * @param $type
	 * @param int $pageSize
	 *
	 * @return mixed
	 */
    public function getNewsListByType($type, $pageSize = 20)
    {
    	$arr = [
		    ARTICLE_TYPE['news'] => '新闻动态',
		    ARTICLE_TYPE['enroll'] => '最新录取',
		    ARTICLE_TYPE['information'] => '留学资讯'
	    ];

    	$query = self::find()->where(array('type' => intval($type)));
    	$count = $query->count('id');

    	$pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => $pageSize]);
    	$rows['current']['list'] = $query->orderBy('createTime DESC')->offset($pagination->offset)->limit($pageSize)->all();
    	$rows['current']['totalRows'] = $count;
	    $rows['current']['title'] = $arr[$type];

	    $allType = ARTICLE_TYPE;
	    $types = array();
	    foreach($allType as $key => $value){
	    	if($value != $type){
	    		$types[] = $value;
		    }
	    }

    	$rows['other']['item1']['info'] = $this->getList(array('type' => $types[0]), 1)[0];
	    $rows['other']['item1']['title'] = $arr[$types[0]];

	    $rows['other']['item2']['info'] = $this->getList(array('type' => $types[1]), 1)[0];
	    $rows['other']['item2']['title'] = $arr[$types[1]];

    	return $rows;
    }

}
