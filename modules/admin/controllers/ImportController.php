<?php

namespace app\modules\admin\controllers;

use app\controllers\BaseAPIController;
use app\common\Helper;
use Yii;

/**
 * Class ImportController
 * @package app\modules\admin\Controller
 */
class ImportController extends BaseAPIController
{
	/**
	 * 导入excel文件
	 */
	public function actionExcel()
	{
		$sheetIndex = Yii::$app->request->post('sheetIndex');
		//$fields = Yii::$app->request->post('fields');
		$tableName = Yii::$app->request->post('tableName');

		$excelFile = $_FILES;
		if(!$excelFile){
			return Helper::formatJson(1001, '请上传文件');
		}

		$letter = range('A', 'Z');
		$fields = array('cateId', 'collegeId', 'cost', 'target', 'content', 'quarter', 'requirement', 'language', 'gpa', 'applyEndDate', 'costEndDate', 'courseDate', 'costRemarks', 'remarks');
		$data = array();

		foreach($excelFile as $file){
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			if($ext === 'xls'){
				$reader = \PHPExcel_IOFactory::createReader('Excel5');
			}elseif($ext === 'xlsx'){
				$reader = \PHPExcel_IOFactory::createReader('Excel2007');
			}

			$PHPExcel = $reader->load($file['tmp_name']);
			$PHPExcel->setActiveSheetIndex($sheetIndex);
			$objWorksheet = $PHPExcel->getActiveSheet();
			// 取得总行数
			$highestRow = $objWorksheet->getHighestRow();
			// 取得总列数
			$highestColumn = $objWorksheet->getHighestColumn();
			for($y = 2; $y <= $highestRow; $y++){
				$rows = array();
				for($x = 1; $x < 26; $x++){
					$cellValue = $objWorksheet->getCell($letter[$x] . $y)->getValue();
					//富文本转换字符串
					if($cellValue instanceof \PHPExcel_RichText){
						$cellValue = $cellValue->__toString();
					}
					$rows[] = $cellValue;
					//$rows[] = $letter[$x] . $y;
					$rows = array_filter($rows);
					if($letter[$x] === $highestColumn){
						break;
					}
				}

				$data[] = $rows;
			}
		}

		if($data){
			if(Yii::$app->db->createCommand()->batchInsert($tableName, $fields, $data)->execute()){
				return Helper::formatJson(200, 'Ok');
			}
		}

		return Helper::formatJson(1007, '导入数据失败');
	}

}