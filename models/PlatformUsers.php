<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "platform_users".
 *
 * @property integer $pid
 * @property integer $uid
 * @property string $username
 * @property string $client_id
 * @property string $client_secret
 * @property string $remark
 * @property string $addTime
 */
class PlatformUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'platform_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['username'], 'required'],
            [['addTime'], 'safe'],
            [['username'], 'string', 'max' => 20],
            [['client_id', 'client_secret'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pid' => 'Pid',
            'uid' => 'Uid',
            'username' => 'Username',
            'client_id' => 'Client ID',
            'client_secret' => 'Client Secret',
            'remark' => 'Remark',
            'addTime' => 'Add Time',
        ];
    }

	/**
	 * 生成client_id
	 * @param $pid
	 * @param $username
	 *
	 * @return string
	 */
    public static function generateClientId($pid, $username)
    {
		return md5($pid . self::generateSalt() . $username);
    }

	/**
	 * 生成client_secret
	 * @param $pid
	 * @param $addTime
	 *
	 * @return string
	 */
    public static function generateClientSecret($pid, $addTime)
    {
	    return sha1($pid . self::generateSalt() . $addTime);
    }

	/**
	 * @param int $length
	 *
	 * @return string
	 */
    public static function generateSalt($length = 6)
    {
	    $salt = '';
	    $letter = array_merge(range('a', 'z'), range('A', 'Z'));
	    for($i = 0; $i <= $length; $i++){
		    $salt .= $letter[mt_rand(0, 52)];
	    }

	    return $salt;
    }
}
