<?php

namespace app\modules\frontend\controllers;

use app\common\Helper;
use app\models\Article;
use Yii;
use app\controllers\BaseController;

class ArticleController extends BaseController
{
	/**
	 * 查询首页文章
	 */
	public function actionList()
	{
		$data = (new Article())->getIndexNews();

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 新闻动态
	 */
	public function actionNews($type = ARTICLE_TYPE['news'], $pageSize = 20)
	{
		$data = (new Article())->getNewsListByType($type, $pageSize);

		return Helper::formatJson(200, 'Ok', $data);
	}

	/**
	 * 详情
	 * @param $articleId
	 *
	 * @return array
	 */
	public function actionDetail($articleId)
	{
		$info = Article::findOne(['id' => intval($articleId)]);

		return Helper::formatJson(200, 'Ok', $info);
	}

}