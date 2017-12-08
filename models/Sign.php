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
            'country' => 'Country',
            'education' => 'Education',
            'grade' => 'Grade',
            'fullName' => 'Full Name',
            'mobile' => 'Mobile',
        ];
    }
}
