<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{
    const STATUS_ON_TREE = 0;
    const STATUS_ON_GROUND = 1;

    function __construct($color = null)
    {
        # 18000 seconds == 5 hours
        $this->birthdayTime = time() + rand(-36000, 0);
        $this->fallingTime = null;
        $this->size = 1.0;
        $this->status = Apple::STATUS_ON_TREE;

        if (is_null($color)) {
            $colors = ['Красный', 'Зеленый', 'Желтый'];
            $randKey = array_rand($colors);
            $color = $colors[$randKey];
        }
        $this->color = $color;
    }

    public static function tableName()
    {
        return '{{apple}}';
    }

    public function rules()
    {
        return [
            [['color', 'size', 'status', 'birthdayTime'], 'required'],
            [['birthdayTime', 'fallingTime'], 'integer'],
            ['color', 'string', 'max' => 32],
            ['status', 'integer', 'min' => Apple::STATUS_ON_TREE, 'max' => Apple::STATUS_ON_GROUND],
        ];
    }

    private function setSize($value)
    {
        $this->size = $value >= 0.0 ? $value : 0.0;
    }

    private function setStatus($value)
    {
        if ($value !== Apple::STATUS_ON_TREE
            && $value !== Apple::STATUS_ON_GROUND) {
            throw new \Exception("Неизвестный статус", 1);
        }

        $this->status = $value;
    }

    /**
     * Съесть яблоко.
     * 
     * @param float $percent Процент, который будет откушен от яблока.
     * @throws \Throwable Когда либо яблоко еще не упало, либо оно гнилое.
     */
    public function eat($percent)
    {
        $newSize = $this->size - ($percent / 100.0);
        if ($this->status == static::STATUS_ON_TREE) {
            throw new \Exception("Яблоко на дереве", 1);
        } else if ($this->IsRotten()) {
            throw new \Exception("Яблоко гнилое", 1);
        }

        $this->size = $newSize;
        if ($newSize <= 0.0) {
            $this->delete();
        }
    }

    public function delete()
    {
        return parent::delete() !== false;
    }

    /**
     * Переводит состояние яблока из STATUS_ON_TREE в состояние STATUS_ON_GROUND.
     * 
     * @throws \Throwable В случае если яблоко уже в состояние STATUS_ON_GROUND.
     */
    public function fallToGround()
    {
        if (!$this->isOnTree()) {
            throw new \Exception("Яблоко уже упало", 1);
        }

        $this->fallingTime = time();
        $this->status = Apple::STATUS_ON_GROUND;
    }

    public function IsRotten()
    {
        # 18000 seconds == 5 hours
        return (!$this->isOnTree()) 
            && (($this->fallingTime - $this->birthdayTime) >= 18000);
    }

    public function isOnTree()
    {
        return $this->status === Apple::STATUS_ON_TREE;
    }

    public static function intStatusToString($value)
    {
        if ($value !== Apple::STATUS_ON_TREE
            && $value !== Apple::STATUS_ON_GROUND) {
            throw new \Exception("Неизвестный статус", 1);
        }

        $dict = [
            Apple::STATUS_ON_TREE => 'На дереве',
            Apple::STATUS_ON_GROUND => 'На земле / упало',
        ];

        return $dict[$value];
    }
}