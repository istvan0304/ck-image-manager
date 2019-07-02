<?php


namespace istvan0304\imagemanager\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "ckimage".
 *
 * @property int $id Id
 * @property string $file_name
 * @property string $orig_name
 * @property string $file_hash
 * @property string $mime
 * @property string $extension
 * @property int $size
 * @property string $cr_date Létrehozás dátuma
 * @property string $mod_date Módosítás dátuma
 */
class CkImage extends ActiveRecord
{
    const THUMBNAIL = 'thumbnail_';
    const THUMBNAIL_DIRECTORY = '.thumbnails';
    const THUMBNAIL_WIDTH = 100;
    const THUMBNAIL_HEIGHT = 100;
    public $img_file;
    public $thumbnail;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ckimage';
    }

    /**
     * @param bool $event
     * @return bool
     */
    public function beforeSave($event)
    {
        if (parent::beforeSave($event)) {
            if ($this->isNewRecord) {
                $this->cr_date = new Expression('NOW()');
            } else {
                $this->mod_date = new Expression('NOW()');
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['img_file', 'thumbnail'], 'file', 'extensions' => 'jpg, jpeg, png', 'maxSize' => 3000000, 'maxFiles' => 1],
            [['file_name', 'orig_name', 'file_hash'], 'required'],
            [['size'], 'integer'],
            [['cr_date', 'mod_date'], 'safe'],
            [['file_name', 'orig_name', 'file_hash', 'mime'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 32],
//            [['file_hash'], 'validateFileHash']
        ];
    }

    /**
     * Validate if file already uploaded.
     * @param $attribute
     */
    public function validateFileHash($attribute)
    {
        $fileCount = CkImage::find()->where(['file_hash' => $this->file_hash])->count();

        if($fileCount > 0){
            $this->addError($attribute, Yii::t('ckimage', 'The file is already uploaded!'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('ckimage', 'ID'),
            'file_name' => Yii::t('ckimage', 'File Name'),
            'orig_name' => Yii::t('ckimage', 'Orig Name'),
            'file_hash' => Yii::t('ckimage', 'File Hash'),
            'mime' => Yii::t('ckimage', 'Mime'),
            'extension' => Yii::t('ckimage', 'Extension'),
            'size' => Yii::t('ckimage', 'Size'),
            'cr_date' => Yii::t('ckimage', 'Cr Date'),
            'mod_date' => Yii::t('ckimage', 'Mod Date'),
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload()
    {
        $path = Yii::$app->ckimagemanager->uploadPath;
        $fileName = Yii::$app->ckimagemanager->useOriginalFilename ? $this->orig_name : $this->file_name;

        if(!file_exists($path)){
            FileHelper::createDirectory($path);
        }

        if (is_writable($path) && $this->img_file->saveAs($path . DIRECTORY_SEPARATOR . $fileName)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function uploadThumbnail()
    {
        $path = Yii::$app->ckimagemanager->uploadPath . DIRECTORY_SEPARATOR . self::THUMBNAIL_DIRECTORY;
        $fileName = Yii::$app->ckimagemanager->useOriginalFilename ? self::THUMBNAIL . $this->orig_name : self::THUMBNAIL . $this->file_name;

        if(!file_exists($path)){
            FileHelper::createDirectory($path);
        }

        if (is_writable($path) && $this->thumbnail->save($path . DIRECTORY_SEPARATOR . $fileName)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteFile()
    {
        $path = Yii::$app->ckimagemanager->uploadPath;
        $fileName = Yii::$app->ckimagemanager->useOriginalFilename ? $this->orig_name : $this->file_name;

        $thumbPath = Yii::$app->ckimagemanager->uploadPath . DIRECTORY_SEPARATOR . self::THUMBNAIL_DIRECTORY;
        $thumbFileName = Yii::$app->ckimagemanager->useOriginalFilename ? self::THUMBNAIL . $this->orig_name : self::THUMBNAIL . $this->file_name;

        if(file_exists($path . DIRECTORY_SEPARATOR . $fileName) && file_exists($thumbPath . DIRECTORY_SEPARATOR . $thumbFileName)){
            FileHelper::unlink($path . DIRECTORY_SEPARATOR . $fileName);
            FileHelper::unlink($thumbPath . DIRECTORY_SEPARATOR . $thumbFileName);

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isExistsFile()
    {
        $ckImage = CkImage::findOne($this->id);
        $path = Yii::$app->ckimagemanager->uploadPath;

        if ($ckImage && (is_file($path . DIRECTORY_SEPARATOR . $ckImage->orig_name) || is_file($path . DIRECTORY_SEPARATOR . $ckImage->file_name))) {
            return true;
        }

        return false;
    }
}
