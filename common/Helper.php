<?php

namespace app\common;

use Yii;
use yii\helpers\Json;

class Helper
{
	/**
	 * 格式化返回数据结构
	 * @param  integer $status  [description]
	 * @param  string  $message [description]
	 * @param  array   $data    [description]
	 * @return [type]           [description]
	 */
	public static function formatJson($status = 200, $message = '', $data = array())
	{
		return array('status' => $status, 'message' => $message, 'data' => $data);
	}

	/**
	 * @param int $status
	 * @param string $message
	 * @param string $data
	 */
	public static function arrayToJson($status = 502, $message = '请求不合法', $data = '')
	{
		echo Json::encode(array('status' => $status, 'message' => $message, 'data' => $data));exit;
	}

}