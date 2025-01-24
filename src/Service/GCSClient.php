<?php

namespace Drupal\gcs_large_file_upload\Service;

use Google\Cloud\Storage\StorageClient;
use Drupal\file\Entity\File;
use Psr\Log\LoggerInterface;

class GCSClient {
  protected $storageClient;
  protected $bucketName;
  protected $logger;

  /**
   * GCSClient constructor.
   *
   * @param string $bucketName
   *   The name of the Google Cloud Storage bucket.
   * @param string $projectId
   *   The Google Cloud project ID.
   * @param int $keyfileId
   *   The file ID of the Google Cloud service account keyfile.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct($bucketName, $projectId, $keyfileId, LoggerInterface $logger) {
    $this->logger = $logger;
    $file = File::load($keyfileId);
    $keyFilePath = $file->getFileUri();
    $this->bucketName = $bucketName;
    $this->storageClient = new StorageClient([
      'projectId' => $projectId,
      'keyFilePath' => $keyFilePath, // Use the keyfile path for authentication.
    ]);
  }

  /**
   * Uploads a file to Google Cloud Storage.
   *
   * @param resource $fileStream
   *   The file stream to be uploaded.
   * @param string $fileName
   *   The name of the file to be uploaded.
   *
   * @return string
   *   The URL of the uploaded file.
   *
   * @throws \Exception
   *   If an error occurs while uploading the file.
   */
  public function uploadFile($fileStream, $fileName) {
    try {
      $bucket = $this->storageClient->bucket($this->bucketName);
      $object = $bucket->upload(
        $fileStream,
        [
          'name' => $fileName,
          'predefinedAcl' => 'PUBLIC_READ',
        ]
      );

      return $object->info()['mediaLink'];
    } catch (\Exception $e) {
      $this->logger->error('Error uploading file to GCS: @message', ['@message' => $e->getMessage()]);
      throw $e;
    }
  }
}
