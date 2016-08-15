<?php
namespace Microimgur;

interface FileUploadInterface
{
  /**
   * Check the error field in $_FILE to handle errors
   *
   * @return bool
   */
  public function validateErrorCode();

  /**
   * Save file info (width, height, mime-tiype, etc)
   *
   * @return bool
   */
  public function readFileInfo();

  /**
   * Check file size for max size
   *
   * @return bool
   */
  public function validateSize();

  /**
   * Check mime type. Depends on readFileInfo()
   *
   * @return bool
   */
  public function validateMimeType();

  /**
   * Check max image resolution. Depends on readFileInfo()
   *
   * @return bool
   */
  public function validateResolution();

  /**
   * Check if file 'name' is set
   *
   * @return bool
   */
  public function validateName();

  /**
   * Move the file to permanent location
   *
   * @param $filename
   * @return bool
   */
  public function saveFile($filename);
}
