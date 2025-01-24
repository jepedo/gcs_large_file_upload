<?php

namespace Drupal\Tests\gcs_large_file_upload\Unit;

use Drupal\gcs_large_file_upload\Service\GCSClient;
use Google\Cloud\Storage\StorageClient;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Drupal\gcs_large_file_upload\Service\GCSClient
 */
class GCSClientTest extends TestCase {
  /**
   * @covers ::__construct
   * @covers ::uploadFile
   */
  public function testUploadFile() {
    $bucketName = 'test-bucket';
    $projectId = 'test-project-id';
    $keyFilePath = 'path/to/test-keyfile.json';
    $fileName = 'test-file.txt';
    $fileStream = fopen('php://memory', 'rb');

    $storageClientMock = $this->createMock(StorageClient::class);
    $bucketMock = $this->createMock(StorageClient::class);
    $objectMock = $this->createMock(StorageClient::class);

    $storageClientMock->method('bucket')->willReturn($bucketMock);
    $bucketMock->method('upload')->willReturn($objectMock);
    $objectMock->method('info')->willReturn(['mediaLink' => 'http://example.com/test-file.txt']);

    $gcsClient = new GCSClient($bucketName, $projectId, $keyFilePath);
    $gcsClient->storageClient = $storageClientMock;

    $result = $gcsClient->uploadFile($fileStream, $fileName);

    $this->assertEquals('http://example.com/test-file.txt', $result);
  }
}
