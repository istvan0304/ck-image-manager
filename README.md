Image file upload manager for Yii2 CK editor
=================

Requirements
------------
- php >=7.2
- mySQL >=5.7

Installation
------------
The preferred way to install this extension is through composer.

- Run

    $ php composer.phar require istvan0304/ck-image-manager "dev-master"
    
or add:
    
        "istvan0304/ck-image-manager": "dev-master"
        
to the require section of your application's composer.json file.    

- Run the migrate to create the database table

        yii migrate --migrationPath=@istvan0304/imagemanager/migrations
        
- Add new modules section to your configuration file:

        'modules' => [
        	'imagemanager' => [
                        'class' => 'istvan0304\imagemanager\Module'
                    ]
        ],
        
- Add a new component in components section of your configuration file:

        'imagemanager' => [
                    'class' => 'istvan0304\imagemanager\components\CkImageManagerComponent',
                    'useOriginalFilename' => false,     		     //use filename (seo friendly) or use a hash
                    'uploadPath' => 'uploads/files',                 //set upload path (default /uploads)
                    'allowDuplicateImage' => false,                  //Let you to upload an image more than one times (default: false)
                ],
                
Usage
------------

For using the filebrowser in CKEditor add the filebrowserImageBrowseUrl to the clientOptions of the CKEditor widget. Tested only with CKEditor from 2amigOS.

        use dosamigos\ckeditor\CKEditor;
        
        <?= $form->field($model, 'text')->widget(CKEditor::class, [
                'options' => ['rows' => 6],
                'preset' => 'advanced',
                'clientOptions' => [
                        'filebrowserImageBrowseUrl' => yii\helpers\Url::to(['imagemanager/ck-image', 'view-mode'=>'iframe', 'select-type'=>'ckeditor']),
                    ],
                ],
            ])
            ?>

Access
------------
if use rbac set access:

        'as access' => [
                'class' => 'mdm\admin\components\AccessControl',
                'allowActions' => [
                    'imagemanager/ck-image/get-image',
                    'imagemanager/ck-image/preview-thumbnail'
                ]
            ],