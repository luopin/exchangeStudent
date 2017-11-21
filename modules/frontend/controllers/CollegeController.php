<?php

namespace app\modules\frontend\controllers;

use app\common\Helper;
use app\controllers\BaseController;
use app\models\College;
use Yii;

class CollegeController extends BaseController
{
	/**
	 * 首页学校排名
	 */
	public function actionTop20()
	{
		$model = new College();
		$fields = array('id', 'name');

		$data['usTop'] = $model->getList($fields,'usRank', 20);
		$data['wlTop'] = $model->getList($fields,'wlRank', 20);
		$data['highTop'] = $model->getList($fields,'rank', 20);

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 热门推荐
	 */
	public function actionHotRecommend()
	{
		$fields = array('id', 'name', 'enName', 'logo');
		$data = (new College())->getList($fields,'qsRank', 16);

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 查询院校列表
	 */
	public function actionIndex($keywords = null, $type = null, $orderBy = 'rank', $pageSize = 16)
	{
		$where = array();
		if(isset($type)){
			$where['type'] = $type;
		}

		$fields = array('id', 'name', 'enName', 'logo', 'follow', 'area', 'natures', 'cost', 'acceptanceRate', 'authentication', 'rank');
		$list = (new College())->getPageList($keywords, $where, $orderBy, $fields, $pageSize);

		return Helper::formatJson(200, 'Ok', $list);
	}

	/**
	 * 排名列表
	 */
	public function actionRank()
	{
		$fields = array('id', 'name', 'country');
		$data['QS'] = (new College())->getList($fields,'qsRank', 15);
		$data['US'] = (new College())->getList($fields,'usRank', 15);
		$data['THE'] = (new College())->getList($fields,'rank', 15);

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 搜索
	 */
	public function actionSearch()
	{

	}
}