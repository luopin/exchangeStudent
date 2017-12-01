<?php

namespace app\controllers;

use app\common\Helper;
use app\models\PlatformUsers;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\Cors;
use yii\web\Response;
use yii\base\InlineAction;

class BaseController extends Controller
{
	/**
	 * 配置跨域和返回格式
	 * @return array
	 */
	public function behaviors()
	{
		$behaviors = ArrayHelper::merge([
			'corsFilter' => [
				'class' => Cors::className(),
				'cors' => [
					'Origin' => ['*'],
					'Access-Control-Allow-Credentials' => true,
				]
			],
		], parent::behaviors());

		$behaviors['contentNegotiator']['formats'] = [
			'application/json' => Response::FORMAT_JSON,
		];

		/*$behaviors['authenticator'] = [
			'class' => QueryParamAuth::className(),
			'tokenParam' => 'token',
		];*/

		return $behaviors;
	}

	/*
	 * 重写路由规则
	 */
	public function createAction($id)
	{
		if ($id === '') {
			$id = $this->defaultAction;
		}

		$actionMap = $this->actions();
		if (isset($actionMap[$id])) {
			return Yii::createObject($actionMap[$id], [$id, $this]);
		} elseif (preg_match('/^[a-z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
			$methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
			if (method_exists($this, $methodName)) {
				$method = new \ReflectionMethod($this, $methodName);
				if ($method->isPublic() && $method->getName() === $methodName) {
					return new InlineAction($id, $this, $methodName);
				}
			}
		} else {
			$methodName = 'action' . ucwords($id);
			if (method_exists($this, $methodName)) {
				$method = new \ReflectionMethod($this, $methodName);
				if ($method->isPublic() && $method->getName() === $methodName) {
					return new InlineAction($id, $this, $methodName);
				}
			}
		}

		return null;
	}

	public function init()
	{
		/*$postParams = Yii::$app->request->bodyParams;
		$paramStr = Yii::$app->request->queryString;
		parse_str($paramStr, $getParams);
		$params = array_merge($postParams, $getParams);

		if(!isset($params['sign']) || !isset($params['timestamp']) || !isset($params['client_id']) || !isset($params['client_secret'])){
			Helper::arrayToJson();
		}*/

		//验证clientId clientSecret
		/*if(!PlatformUsers::findOne(['client_id' => $params['client_id'], 'client_secret' => $params['client_secret']])){
			Helper::arrayToJson();
		}

		if(time() - $params['timestamp'] > 60){
			Helper::arrayToJson(502, '请求已失效');
		}*/

		//签名验证
		/*$sign = $params['sign'];
		unset($params['sign']);

		$paramStr = '';
		ksort($params);
		if($params){
			foreach($params as $key => $value){
				if(is_array($value)){
					$str = '';
					foreach ($value as $v){
						$str .= $v;
					}

					$paramStr .= $key . '=' . $str;
				}else{
					$paramStr .= $key . '=' . $value;
				}
			}
		}

		$authToken = sha1($paramStr);
		if(strncasecmp($authToken, $sign, 40) !== 0){
			Helper::arrayToJson();
		}*/
	}

}