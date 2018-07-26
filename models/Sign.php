<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sign".
 *
 * @property integer $id
 * @property string $country
 * @property string $education
 * @property string $grade
 * @property string $fullName
 * @property string $mobile
 * @property integer $state
 */
class Sign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['state', 'default', 'value' => 1],
            [['country', 'education', 'grade', 'fullName', 'mobile'], 'required'],
            [['country'], 'string', 'max' => 10],
            [['education', 'grade', 'fullName'], 'string', 'max' => 5],
            [['mobile'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => '国家',
            'education' => '学历',
            'grade' => '年级',
            'fullName' => '姓名',
            'mobile' => '手机',
        ];
    }
}
