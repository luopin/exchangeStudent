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
			if(!isset($data['fullName']) || !$data['fullName']){
			    return Helper::formatJson(1007, '缺少必要参数');
            }

            if(!isset($data['mobile']) || !$data['mobile']){
                return Helper::formatJson(1007, '缺少必要参数');
            }

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

        $model = new Sign();
        $model->country = $country;
        $model->education = $education;
        $model->grade = $grade;
        $model->state = 2;
        $model->save();

        return Helper::formatJson(200, 'ok', ['id' => $model->id]);
    }

    /**
     * @return array]
     */
    public function actionApply()
    {
        $id = Yii::$app->request->post('id');
        $fullName = Yii::$app->request->post('fullName');
        $mobile = Yii::$app->request->post('mobile');
        $school = Yii::$app->request->post('school');
        $major = Yii::$app->request->post('major');

        if(!$id || !$fullName || !$mobile || !$school || !$major){
            return Helper::formatJson(1007, '缺少必要参数');
        }

        $info = Sign::findOne($id);
        $info->fullName = trim($fullName);
        $info->mobile = trim($mobile);
	    $info->school = trim($school);
	    $info->major = trim($major);
        $info->state = 1;
        if($info->save()){
            return Helper::formatJson(200, 'ok');
        }else{
            return Helper::formatJson(1007, $info->getFirstErrors());
        }
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

        $query->andWhere(['state' => 1]);
        $count = $query->count('id');
        $pagination = new Pagination(['totalCount' => $count, 'pageSizeParam' => 'pageSize']);
        $list = $query->offset($pagination->offset)
            ->limit($pagination->getPageSize())
            ->orderBy('createTime DESC')
            ->all();

        $data = ['rows' => $list, 'count' => $count];
        return Helper::formatJson(200, 'ok', $data);
    }

    /**
     * 设置已联系
     *
     * @return array
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->post('id');
        $info = Sign::findOne($id);
        if(!$info){
            return Helper::formatJson(1007, $info->getFirstErrors());
        }

        $info->isContacted = 2;
        $info->save();

        return Helper::formatJson(200, 'ok');
    }

    /**
     * 导出数据
     *
     * @return array
     */
    public function actionExport($keyword = '')
    {
	    $query = Sign::find();
	    if($keyword != ''){
		    $query->orWhere(['like', 'fullName', trim($keyword)]);
		    $query->orWhere(['like', 'mobile', trim($keyword)]);
	    }

	    $query->andWhere(['state' => 1]);
		$items = $query->orderBy('createTime DESC')->asArray()->all();

	    $excelObj = new \PHPExcel();
	    $sheet = $excelObj->setActiveSheetIndex();

	    //ID，用户名，所属机构，部门，属性，注册时间，状态，首次注册平台
	    $sheet->setCellValue('A1','ID');
	    $sheet->setCellValue('B1','姓名');
	    $sheet->setCellValue('C1','手机');
	    $sheet->setCellValue('D1','意向国家');
	    $sheet->setCellValue('E1','学历');
	    $sheet->setCellValue('F1','在读年级');
	    $sheet->setCellValue('G1','学校');
	    $sheet->setCellValue('H1','专业');
	    $sheet->setCellValue('I1','报名时间');
	    $sheet->setCellValue('J1','状态');

	    $sheet->getColumnDimension('C')->setWidth(20);
	    $sheet->getColumnDimension('G')->setWidth(30);
	    $sheet->getColumnDimension('H')->setWidth(30);
	    $sheet->getColumnDimension('I')->setWidth(30);


	    $num = 2;
	    foreach($items as $k => $row){
		    $sheet->setCellValue('A' . $num, $row['id']);
		    $sheet->setCellValue('B' . $num, $row['fullName']);
		    $sheet->setCellValueExplicit('C' . $num, $row['mobile'],\PHPExcel_Cell_DataType::TYPE_STRING);
		    $sheet->setCellValueExplicit('D' . $num, $row['country']);
		    $sheet->setCellValue('E' . $num, $row['education']);
		    $sheet->setCellValue('F' . $num, $row['grade']);
		    $sheet->setCellValue('G' . $num, $row['school']);
		    $sheet->setCellValue('H' . $num, $row['major']);
		    $sheet->setCellValue('I' . $num, $row['createTime']);
		    $sheet->setCellValue('J' . $num, $row['isContacted'] == 1 ? '未联系' : '已联系');
		    $num++;
	    }

	    $excelObj->createSheet();

	    $filename = '报名数据.xlsx';
	    $path = 'data/download/' . date('Ymd') . '/';
	    if(!file_exists($path)){
		    mkdir($path, 0755, true);
	    }

	    $fullPath = $path . $filename;
	    if(file_exists($fullPath)){
		    @unlink($fullPath);
	    }

	    $objWriter = \PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
	    $objWriter->save($fullPath);

        return Helper::formatJson(200, 'ok', ['url' => Yii::$app->request->getHostInfo() . '/' . $fullPath]);
    }

}