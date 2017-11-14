<?php

namespace app\modules\frontend\controllers;

use app\common\Helper;
use app\models\Article;
use Yii;
use app\controllers\BaseAPIController;

class ArticleController extends BaseAPIController
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
	public function actionNews()
	{
		$data = (new Article())->getList(['type' => ARTICLE_TYPE['news']], 4);

		return Helper::formatJson(200, 'Ok', $data);
	}

}