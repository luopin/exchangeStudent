<?php

namespace app\controllers;

use app\models\PlatformUsers;
use Yii;

class SiteController extends BaseAPIController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    	//$client = PlatformUsers::generateClientId(1, 'PC');
        return [];
    }

    /*public function actionTestUrl($url)
    {
    	$timestamp = time();
    	$client_id = 'c09fe4fc33669df339b4e8035e008a94';
    	$client_secret = 'd8e58b37b24db1382d8444255a488a0cfcbc63af';

    	$sign = '';
    	$params = array('timestamp' => $timestamp, 'client_id' => $client_id, 'client_secret' => $client_secret);
	    ksort($params);

	    foreach($params as $key => $value){
	        $sign .= $key . '=' . $value;
	    }

	    $sign = sha1($sign);
	    return [$url . "?timestamp=$timestamp&client_secret=$client_secret&client_id=$client_id&sign=$sign"];
    }*/

}
