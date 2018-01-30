<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "college".
 *
 * @property integer $id
 * @property string $name
 * @property string $enName
 * @property integer $rank
 * @property integer $usRank
 * @property integer $qsRank
 * @property integer $wlRank
 * @property string $description
 * @property string $officialUrl
 * @property string $url
 * @property string $type
 * @property string $country
 * @property string $area
 * @property string $natures
 * @property integer $createYear
 * @property double $cost
 * @property string $logo
 * @property string $authentication
 * @property double $acceptanceRate
 * @property string $createTime
 */
class College extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'college';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'enName'], 'required'],
            [['rank', 'usRank', 'qsRank', 'wlRank', 'createYear'], 'integer'],
            [['description', 'authentication'], 'string'],
            [['cost', 'acceptanceRate'], 'number'],
            [['createTime'], 'safe'],
            [['name', 'officialUrl'], 'string', 'max' => 50],
            [['enName', 'logo'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 300],
            [['type', 'country', 'area'], 'string', 'max' => 20],
            [['natures'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'enName' => 'En Name',
            'rank' => 'Rank',
            'usRank' => 'Us Rank',
            'qsRank' => 'Qs Rank',
            'wlRank' => 'Wl Rank',
            'description' => 'Description',
            'officialUrl' => 'Official Url',
            'url' => 'Url',
            'type' => 'Type',
            'country' => 'Country',
            'area' => 'Area',
            'natures' => 'Natures',
            'createYear' => 'Create Year',
            'cost' => 'Cost',
            'logo' => 'Logo',
            'authentication' => 'Authentication',
            'acceptanceRate' => 'Acceptance Rate',
            'createTime' => 'Create Time',
        ];
    }

	/**
	 * 特殊字段处理
	 */
    public function fields()
    {
	    $fields = parent::fields();

	    if(isset($fields['acceptanceRate'])){
		    $fields['acceptanceRate'] = function (){
			    return $this->acceptanceRate * 100 . '%';
		    };
	    }

	    if(isset($fields['logo'])){
		    $fields['logo'] = function(){
			    return Yii::$app->request->hostInfo . '/images/logo/' . $this->logo;
		    };
	    }

	    return $fields;
    }

	/**
	 * 查询排名学校
	 * @param $fields
	 * @param $orderBy
	 * @param $limit
	 *
	 * @return array|\yii\db\ActiveRecord[]
	 */
    public function getList($fields, $orderBy, $limit)
    {
    	return self::find()->select($fields)->orderBy($orderBy)->limit($limit)->all();
    }

	/**
	 * 分页列表
	 * @param null $keyword
	 * @param array $where
	 * @param string $orderBy
	 * @param string $fields
	 * @param int $pageSize
	 *
	 * @return mixed
	 */
    public function getPageList($keyword = null, $where = array(), $orderBy = 'id DESC', $fields = '*', $pageSize = 20)
    {
    	$query = self::find()->select($fields);
		if(isset($keyword)){
			$query->orWhere(['like', 'name', $keyword])->orWhere(['like', 'enName', $keyword]);
		}

		if($where){
			$query->andWhere($where);
		}

		$count = $query->count('id');
		$pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => $pageSize]);

		$list['rows'] = $query->orderBy($orderBy)->offset($pagination->offset)->limit($pageSize)->all();
		$list['totalRows'] = $count;

		return $list;
    }

}
