<?php
require_once __DIR__ . '/FileUploadInterface.php';

class FileUpload implements FileUploadInterface
{
  const MAX_WIDTH     = 1920;
  const MAX_HEIGHT    = 1080;
  const MAX_FILE_SIZE = 2048 * 1024; // 2mb

  private static $allowedTypes = ['image/jpeg', 'image/jpg'];
  private        $file;
  private        $fileInfo;

  public function __construct($formFileName)
  {

    if (!isset($_FILES[$formFileName]))
    {
      throw new RuntimeException(('No file provided'));
    }
    if (!isset($_FILES[$formFileName]['error']) || is_array($_FILES[$formFileName]['error']))
    {
      throw new RuntimeException(_('Invalid "error" stuff.'));
    }

    $this->file = $_FILES[$formFileName];
  }

  /**
   * @inheritdoc
   */
  public function validateErrorCode()
  {
    switch ($this->file['error'])
    {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new RuntimeException(_('No file sent.'));
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new RuntimeException(_('Exceeded filesize limit.'));
      default:
        throw new RuntimeException(_('Unknown errors.'));
    }

    return true;
  }

  /**
   * @inheritdoc
   */
  public function readFileInfo()
  {
    if (($this->fileInfo = getimagesize($this->file['tmp_name'])) == false)
    {
      throw new RuntimeException(_('File info is not available, could not check image'));
    }

    return true;
  }

  /**
   * @inheritdoc
   */
  public function validateSize()
  {
    if (isset($this->file['size']) && $this->file['size'] > self::MAX_FILE_SIZE)
    {
      throw new RuntimeException(sprintf(
          _('This file is too big (%s), max file size is: %s'),
          human_filesize($this->file['size']),
          human_filesize(self::MAX_FILE_SIZE))
      );
    }

    return true;
  }

  /**
   * @inheritdoc
   */
  public function validateMimeType()
  {

    if ($this->hasFile() && (!isset($this->fileInfo['mime']) || !in_array($this->fileInfo['mime'], self::$allowedTypes)))
    {
      throw new RuntimeException(_('Type not supported'));
    }

    return true;
  }

  /**
   * @inheritdoc
   */
  public function validateResolution()
  {
    if ($this->hasFile() && ($this->fileInfo[0] > self::MAX_WIDTH && $this->fileInfo[1] > self::MAX_HEIGHT))
    {
      throw new RuntimeException(sprintf(
          _('Image resolution is to large (%s*%s), max file size is: %s*%s'),
          $this->fileInfo[0],
          $this->fileInfo[1],
          self::MAX_WIDTH,
          self::MAX_HEIGHT)
      );
    }

    return true;
  }

  /**
   * @inheritdoc
   */
  public function validateName()
  {
    if (empty($this->file['name']))
    {
      throw new RuntimeException(_('Image must have a name'));
    }

    return true;
  }

  /**
   * @inheritdoc
   */
  public function saveFile($filename)
  {
    return move_uploaded_file($this->file['tmp_name'], $filename);
  }

  /**
   * @return bool
   */
  private function hasFile()
  {
    return !empty($this->file);
  }
}
