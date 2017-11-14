<?php

namespace app\controllers;

use Yii;
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
			[
				'class' => Cors::className(),
			],

		], parent::behaviors());

		$behaviors['contentNegotiator']['formats'] = [
			'application/json' => Response::FORMAT_JSON,
		];

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

	}

}