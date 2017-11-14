<?php

namespace app\common;

use Yii;

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

}