<?php

namespace app\modules\frontend\controllers;

use Yii;
use app\models\Sign;
use app\common\Helper;
use app\controllers\BaseController;
use yii\data\Pagination;

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

    /**
     * @return array
     */
	public function actionStepOne()
    {
        $country = Yii::$app->request->post('country');
        $education = Yii::$app->request->post('education');
        $grade = Yii::$app->request->post('grade');

        if(!$country || !$education || !$grade){
            return Helper::formatJson(1007, '缺少必要参数');
        }

        $id = Yii::$app->db->createCommand()->insert('sign', [
            'country' => $country,
            'education' => $education,
            'grade' => $grade,
            'state' => 2
        ]);

        return Helper::formatJson(200, 'ok', ['id' => $id]);
    }

    /**
     * @return array]
     */
    public function actionApply()
    {
        $id = Yii::$app->request->post('id');
        $fullName = Yii::$app->request->post('fullName');
        $mobile = Yii::$app->request->post('mobile');

        if(!$id || !$fullName || !$mobile){
            return Helper::formatJson(1007, '缺少必要参数');
        }

        $info = Sign::findOne($id);
        $info->fullName = trim($fullName);
        $info->mobile = trim($mobile);
        $info->state = 1;
        $info->save();

        return Helper::formatJson(200, 'ok');
    }

    /**
     * @param string $keyword
     * @return array
     */
    public function actionList($keyword = '')
    {
        $query = Sign::find();
        if($keyword != ''){
            $query->orWhere(['like', 'fullName', trim($keyword)]);
            $query->orWhere(['like', 'mobile', trim($keyword)]);
        }

        $count = $query->count('id');
        $pagination = new Pagination(['totalCount' => $count, 'pageSizeParam' => 'pageSize']);
        $list = $query->offset($pagination->offset)->limit($pagination->getPageSize())->all();

        $data = ['rows' => $list, 'count' => $count];
        return Helper::formatJson(200, 'ok', $data);
    }

}