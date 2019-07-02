<?php


namespace istvan0304\imagemanager\components;

use yii\base\Component;
use yii\base\InvalidConfigException;

class CkImageManagerComponent extends Component
{
    /**
     * Uploaded files path.
     * @var string
     */
    public $uploadPath = 'uploads/files';

    /**
     * @var boolean $useOriginalFilename use original filename
     */
    public $useOriginalFilename = true;

    /**
     * Init set config
     */
    public function init() {
        parent::init();

        // Check if the user input is correct
        $this->checkAttributes();
    }

    /**
     * Check the user configurable variables.
     * @throws InvalidConfigException
     */
    private function checkAttributes()
    {
        if (! is_string($this->uploadPath)) {
            throw new InvalidConfigException("Image upload file path '$this->uploadPath' is not a string");
        }
    }

}
