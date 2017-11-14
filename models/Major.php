<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "major".
 *
 * @property integer $id
 * @property integer $cid
 * @property string $name
 * @property integer $usRank
 * @property integer $globalRank
 * @property string $createTime
 */
class Major extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'major';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'name', 'usRank', 'globalRank'], 'required'],
            [['cid', 'usRank', 'globalRank'], 'integer'],
            [['createTime'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'name' => 'Name',
            'usRank' => 'Us Rank',
            'globalRank' => 'Global Rank',
            'createTime' => 'Create Time',
        ];
    }
}
