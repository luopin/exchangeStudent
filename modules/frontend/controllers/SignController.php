<?php

namespace app\modules\frontend\controllers;

use app\common\Helper;
use Yii;
use app\models\Sign;
use app\controllers\BaseController;

class SignController extends BaseController
{
	/**
	 * 报名
	 */
	public function actionIndex()
	{
		if(Yii::$app->request->isPost){
			$model = new Sign();
			if($model->load(Yii::$app->request->post(), '') && $model->validate()){
				if($model->save()){
					return Helper::formatJson(200, 'Ok');
				}else{
					return Helper::formatJson(1007, '添加失败');
				}
			}else{
				return Helper::formatJson(1007, $model->getFirstErrors());
			}
		}

		return Helper::formatJson(1003, '请求方式错误');
	}

}