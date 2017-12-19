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
			$data = Yii::$app->request->post();
			if($model->load($data, '') && $model->validate()){
				if($model->save()){
					$this->sendEmail($data['fullName'], $data['country'], $data['education'], $data['grade'], $data['mobile']);
					return Helper::formatJson(200, 'Ok');
				}else{
					return Helper::formatJson(1007, '添加失败');
				}
			}else{
				return Helper::formatJson(1007, $model->getErrors());
			}
		}

		return Helper::formatJson(1003, '请求方式错误');
	}

	/**
	 * 发送邮件
	 */
	public function sendEmail($fullName, $country, $education, $grade, $mobile)
	{
		$subject = $fullName . ' 提交了 ' . $country . ' 的 ' . $education . ' 学历申请，当前在读年级 ' . $grade . '，联系电话 : ' . $mobile;

		return Yii::$app->mailer->compose()
		    ->setFrom(['15982279117@163.com' => '金桥留学'])
		    ->setTo(SERVICE_EMAIL_ADDRESS)
		    ->setSubject('留学申请')
			->setTextBody($subject)
		    ->send();
	}

}