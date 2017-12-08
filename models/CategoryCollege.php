<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "category_college".
 *
 * @property integer $id
 * @property integer $collegeId
 * @property integer $cateId
 * @property double $cost
 * @property string $costEndDate
 * @property string $costRemarks
 * @property string $target
 * @property string $content
 * @property string $quarter
 * @property string $requirement
 * @property string $language
 * @property string $gpa
 * @property string $applyEndDate
 * @property string $courseDate
 * @property string $remarks
 * @property string $createTime
 */
class CategoryCollege extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_college';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['collegeId'], 'required'],
            [['collegeId', 'cateId'], 'integer'],
            [['cost'], 'number'],
            [['createTime'], 'safe'],
            [['costEndDate', 'target', 'quarter', 'language', 'gpa', 'applyEndDate', 'courseDate'], 'string', 'max' => 50],
            [['costRemarks', 'requirement'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 255],
            [['remarks'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'collegeId' => 'College ID',
            'cateId' => 'Cate ID',
            'cost' => 'Cost',
            'costEndDate' => 'Cost End Date',
            'costRemarks' => 'Cost Remarks',
            'target' => 'Target',
            'content' => 'Content',
            'quarter' => 'Quarter',
            'requirement' => 'Requirement',
            'language' => 'Language',
            'gpa' => 'Gpa',
            'applyEndDate' => 'Apply End Date',
            'courseDate' => 'Course Date',
            'remarks' => 'Remarks',
            'createTime' => 'Create Time',
        ];
    }

	/**
	 * 查询分类下的学校
	 * @param $cateId
	 * @param null $type
	 * @param null $keywords
	 * @param string $orderBy
	 * @param int $pageSize
	 *
	 * @return array
	 */
    public function getListByCateId($cateId, $type = null, $keywords = null, $orderBy = 'b.id DESC', $pageSize = 10)
    {
    	$where = array();
		$query = self::find()
			->select(['b.id', 'b.name', 'b.area', 'b.natures', 'b.rank', 'b.cost', 'b.acceptanceRate', 'b.authentication', 'b.officialUrl', 'a.target', 'a.language', 'a.content', 'a.courseDate'])
		    ->alias('a');

		if(isset($keywords)){
			$query->orWhere(['like', 'b.name', $keywords])->orWhere(['like', 'b.enName', $keywords]);
		}

		if($cateId){
			$where['a.cateId'] = intval($cateId);
		}

	    if(isset($type) && $type != null){
		    $where['b.type'] = $type;
	    }

	    if($where){
		    $query->andWhere($where);
	    }

		$query->leftJoin(College::tableName() . ' as b', 'b.id = a.collegeId');

		$count = $query->count('b.id');
		$pagination = new Pagination(['totalCount' => $count, 'defaultPageSize' => $pageSize]);
		$rows = $query->orderBy($orderBy)->offset($pagination->offset)->limit($pageSize)->asArray()->all();

		$collegeIds = array();
		$list = array();
		if($rows){
			foreach($rows as $key => $value){
				$collegeIds[] = $value['id'];
				$value['majors'] = array();

				$list[$value['id']] = $value;
			}
		}

		if($collegeIds){
			$majors = Major::find()->where(['cid' => $collegeIds])->all();
			if($majors){
				foreach($majors as $major){
					array_push($list[$major->cid]['majors'], $major);
				}
			}
		}

		return array('rows' => array_values($list), 'totalRows' => $count);
    }

}
