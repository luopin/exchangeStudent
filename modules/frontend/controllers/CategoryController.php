<?php

namespace app\modules\frontend\controllers;

use app\common\Helper;
use app\controllers\BaseAPIController;
use app\models\Category;
use app\models\CategoryCollege;
use Yii;

class CategoryController extends BaseAPIController
{
	/**
	 * 查询分类列表
	 */
	public function actionList()
	{
		$data = Category::find()->all();

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 查询分类详情和分类下的学校
	 * @param $cateId
	 * @param null $type
	 * @param null $keywords
	 * @param string $orderBy
	 * @param int $pageSize
	 *
	 * @return array
	 */
	public function actionInfo($cateId, $type = null, $keywords = null, $sort = 'id', $pageSize = 5)
	{
		$data['info'] = Category::findOne(['id' => intval($cateId)]);
		if($sort == 'rank'){
			$orderBy = 'b.' . $sort . ' ASC';
		}else{
			$orderBy = 'b.' . $sort . ' DESC';
		}

		$data['colleges'] = (new CategoryCollege())->getListByCateId($cateId, $type, $keywords, $orderBy, $pageSize);

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 查询分类名
	 */
	public function actionNameList()
	{
		$data = Category::find()->select(['id', 'cname'])->all();
		return Helper::formatJson(200, 'Ok', $data);
	}

}