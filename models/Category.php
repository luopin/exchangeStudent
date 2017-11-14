<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $cname
 * @property string $target
 * @property string $requirement
 * @property string $introduce
 * @property string $advantage
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cname', 'target', 'requirement', 'introduce', 'advantage'], 'required'],
            [['advantage'], 'string'],
            [['cname'], 'string', 'max' => 20],
            [['target', 'requirement'], 'string', 'max' => 500],
            [['introduce'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cname' => 'Cname',
            'target' => 'Target',
            'requirement' => 'Requirement',
            'introduce' => 'Introduce',
            'advantage' => 'Advantage',
        ];
    }

}
