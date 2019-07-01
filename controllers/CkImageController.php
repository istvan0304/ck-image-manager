<?php


namespace istvan0304\ckImageManager\controllers;

use Yii;
use app\ckImageManager\assets\CkImageManagerAsset;
use app\ckImageManager\components\UploadException;
use app\ckImageManager\models\CkImage;
use app\ckImageManager\models\CkImageForm;
use yii\filters\VerbFilter;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class CkImageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $ckImageManagerForm = new CkImageForm();
        $ckImages = CkImage::find()->all();
        $this->layout = "layout";
        CkImageManagerAsset::register($this->view);

//        var_dump(Yii::$app->request->get("view-mode", "page"));
//        var_dump($selectType = Yii::$app->request->get("select-type", "input"));

        return $this->render('index', [
            'ckImageManagerForm' => $ckImageManagerForm,
            'ckImages' => $ckImages
        ]);
    }

    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ckImageFormModel = new CkImageForm();
        $response = [];
        $successUpload = 0;

        if ($ckImageFormModel->load(Yii::$app->request->post())) {
            $files = UploadedFile::getInstances($ckImageFormModel, 'img_files');

            foreach ($files as $file) {
                $ckImageModel = new CkImage();
                $ckImageModel->img_file = $file;
                $extension = $ckImageModel->img_file->getExtension();
                $uid = uniqid(time(), true);
                $fileName = $uid . '.' . $extension;
                $filePath = $ckImageModel->img_file->tempName;

                try {
                    if ($ckImageModel->img_file->getHasError()) {
                        throw new UploadException($ckImageModel->img_file->error);
                    }

//                    var_dump($ckImageModel->img_file->getHasError());
//
//                    var_dump($ckImageModel->img_file->error);
//                    die();

                    $ckImageModel->file_name = $fileName;
                    $ckImageModel->orig_name = $ckImageModel->img_file->name;
                    $ckImageModel->file_hash = hash_file('md5', $filePath);
                    $ckImageModel->mime = $ckImageModel->img_file->type;
                    $ckImageModel->extension = $extension;
                    $ckImageModel->size = $ckImageModel->img_file->size;

                    if ($ckImageModel->save()) {
                        $ckImageModel->thumbnail = Image::thumbnail($filePath, CkImage::THUMBNAIL_WIDTH, CkImage::THUMBNAIL_WIDTH);

                        if ($ckImageModel->upload() && $ckImageModel->uploadThumbnail()) {
                            $response[$ckImageModel->orig_name] = [
                                'success' => true,
                                'class' => 'ck-success',
                                'message' => Yii::t('ckimage', 'File has been uploaded successfully!')
                            ];

                            $successUpload++;
                        } else {
                            $response[$ckImageModel->orig_name] = [
                                'success' => false,
                                'class' => 'ck-error',
                                'message' => Yii::t('ckimage', 'File upload error')
                            ];

                            $ckImageModel->delete();
                        }
                    } else {
                        $response[$ckImageModel->orig_name] = [
                            'success' => false,
                            'class' => 'ck-error',
                            'message' => $ckImageModel->getErrors()
                        ];
                    }
                } catch (UploadException $e) {
                    $response[$ckImageModel->img_file->name] = [
                        'success' => false,
                        'class' => 'ck-error',
                        'message' => $e->getMessage()
                    ];
                }
            }

            return $this->renderAjax('_uploadResponse', ['responsesData' => $response, 'filesNumber' => count($files), 'successUpload' => $successUpload]);
        } else {
            return 'sadf';
        }


    }

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $post = Yii::$app->request->post();
            $imgId = $post['id'] ?? null;

            if ($imgId != null) {
                $ckImageModel = CkImage::findOne(['id' => $imgId]);

                if ($ckImageModel && $ckImageModel->delete() && $ckImageModel->deleteFile()) {
                    $response['success'] = true;
                }
            }

            return $response;
        } else {
            throw new NotFoundHttpException(Yii::t('ckimage', 'Page not found!'));
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function actionPreviewThumbnail($id)
    {
        $ckImage = CkImage::findOne($id);

        if ($ckImage) {
            $path = Yii::$app->ckimagemanager->uploadPath . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL_DIRECTORY;

            if (!is_file($path . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL . $ckImage->orig_name) && !is_file($path . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL . $ckImage->file_name)) {
                throw new \Exception(Yii::t('ckimage', 'File not found!'));
            } else {
                $pointer = null;

                if (is_file($path . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL . $ckImage->orig_name)) {
                    $imagePath = $path . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL . $ckImage->orig_name;
                    header('Content-type: ' . mime_content_type($imagePath));
                    header('Content-Length: ' . filesize($imagePath));
                    $pointer = @fopen($imagePath, 'rb');
                } elseif (is_file($path . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL . $ckImage->file_name)) {
                    $imagePath = $path . DIRECTORY_SEPARATOR . CkImage::THUMBNAIL . $ckImage->file_name;
                    header('Content-type: ' . mime_content_type($imagePath));
                    header('Content-Length: ' . filesize($imagePath));
                    $pointer = @fopen($imagePath, 'rb');
                }

                if ($pointer) {
                    fpassthru($pointer);
                    exit();
                }
            }
        }

        throw new \Exception(Yii::t('ckimage', 'File not found!'));
    }
}
