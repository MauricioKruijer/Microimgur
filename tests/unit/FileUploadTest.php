<?php
namespace Microimgur;

use Exception;

class FileUploadTest extends \PHPUnit_Framework_TestCase
{
  /**
   * @var FileUpload
   */
  private $_fileUpload;

  protected function setUp()
  {
     $this->makeFile('image',
      'source-test.jpg',
      'image/jpeg',
      161250,
      __DIR__ . '/_files/source-test.jpg',
      UPLOAD_ERR_OK
    );

    $this->_fileUpload = new FileUpload('image');
  }

  private function makeFile($formFileName, $filename, $filetype, $filesize, $tempname, $errorCode)
  {
    $_FILES = [
      $formFileName => [
        'name' => $filename,
        'type' => $filetype,
        'size' => $filesize,
        'tmp_name' => $tempname,
        'error' => $errorCode
      ]
    ];
  }

  protected function tearDown()
  {
    unset($_FILES);
    unset($this->_object);
    @unlink(__DIR__ . '/../test.jpg');
  }

  public function testSaveFile()
  {
    $this->assertTrue($this->_fileUpload->saveFile('test.jpg'));
  }

  public function testNoFileProvided()
  {
    $this->expectException(\RuntimeException::class);
    new FileUpload('random');

//    $errorMessage = null;
//    try
//    {
//      new FileUpload('random');
//    }
//    catch (Exception $e)
//    {
//      $errorMessage = $e->getMessage();
//    }
//
//    $this->assertEquals('No file provided', $errorMessage);
  }

  public function testMissingFileUploadError()
  {
    $_FILES = ['image' => []];
    $this->expectException(\RuntimeException::class);

    new FileUpload('image');
  }

  public function testValidateErrorCodeNoFile()
  {
    $this->makeFile(
      'image',
      'source-test.jpg',
      'image/jpeg',
      161250,
      __DIR__ . '/_files/source-test.jpg',
      UPLOAD_ERR_NO_FILE
    );

    $this->expectException(\RuntimeException::class);

    $fileupload = new FileUpload('image');
    $fileupload->validateErrorCode();
  }

  public function testValidateErrorCodeIniSize()
  {
    $this->makeFile(
      'image',
      'source-test.jpg',
      'image/jpeg',
      161250,
      __DIR__ . '/_files/source-test.jpg',
      UPLOAD_ERR_INI_SIZE
    );

    $this->expectException(\RuntimeException::class);

    $fileupload = new FileUpload('image');
    $fileupload->validateErrorCode();
  }

  public function testValidateErrorCodeFormSize()
  {
    $this->makeFile(
      'image',
      'source-test.jpg',
      'image/jpeg',
      161250,
      __DIR__ . '/_files/source-test.jpg',
      UPLOAD_ERR_FORM_SIZE
    );

    $this->expectException(\RuntimeException::class);

    $fileupload = new FileUpload('image');
    $fileupload->validateErrorCode();
  }

  public function testValidateErrorCodeUnknown()
  {
    $this->makeFile(
      'image',
      'source-test.jpg',
      'image/jpeg',
      161250,
      __DIR__ . '/_files/source-test.jpg',
      1337
    );

    $this->expectException(\RuntimeException::class);

    $fileupload = new FileUpload('image');
    $fileupload->validateErrorCode();
  }

  public function testValidateSizeAllowed()
  {
    $this->assertTrue($this->_fileUpload->validateSize());
  }

  public function testValidateSizeNotAllowed()
  {
    $this->expectException(\RuntimeException::class);

    $this->makeFile('image',
      'source-test.jpg',
      'image/jpeg',
      16125000000000,
      __DIR__ . '/_files/source-test.jpg',
      UPLOAD_ERR_OK
    );

    $fileupload = new FileUpload('image');
    $fileupload->validateSize();
  }

  public function testValidateMimeTypeWithoutReadFile()
  {
    $this->expectException(\RuntimeException::class);

    $this->_fileUpload->validateMimeType();
  }

  public function testReadFileInfoSuccess()
  {
    $this->assertTrue($this->_fileUpload->readFileInfo());
  }

  public function testValidateMimeTypeSuccess()
  {
    $this->_fileUpload->readFileInfo();

    $this->assertTrue($this->_fileUpload->validateMimeType());
  }


  public function testValidateMimeTypeUnsupportedFile()
  {
    $this->expectException(\RuntimeException::class);

    $this->makeFile('image',
      'source-test.gif',
      'image/gif',
      2440759,
      __DIR__ . '/_files/source-test.gif',
      UPLOAD_ERR_OK
    );

    $fileupload = new FileUpload('image');
    $fileupload->readFileInfo();
    $fileupload->validateMimeType();
  }

  public function testValidateResolutionSuccess()
  {
    $this->_fileUpload->readFileInfo();

    $this->assertTrue($this->_fileUpload->validateResolution());
  }

  public function testValidateResolutionTooBig()
  {
    $this->expectException(\RuntimeException::class);

    $this->makeFile('image',
      'source-test.gif',
      'image/jpeg',
      214323,
      __DIR__ . '/_files/source-test-too-big.jpg',
      UPLOAD_ERR_OK
    );

    $fileupload = new FileUpload('image');
    $fileupload->readFileInfo();
    $fileupload->validateResolution();
  }

  public function testFilenameSuccess()
  {
    $this->assertTrue($this->_fileUpload->validateName());
  }

  public function testEmptyFilename()
  {
    $this->expectException(\RuntimeException::class);

    $this->makeFile('image',
      '',
      'image/jpeg',
      161250,
      __DIR__ . '/_files/source-test.jpg',
      UPLOAD_ERR_OK
    );

    $fileupload = new FileUpload('image');
    $fileupload->validateName();
  }
}