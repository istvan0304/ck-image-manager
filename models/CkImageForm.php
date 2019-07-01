<?php


namespace app\ckImageManager\models;


use yii\base\Model;

class CkImageForm extends Model
{
    public $img_files;

    public function rules()
    {
        return [
            [['img_files'], 'file', 'extensions' => 'jpg, jpeg, png', 'maxSize' => 3000000, 'maxFiles' => 10],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->img_file as $file) {
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
