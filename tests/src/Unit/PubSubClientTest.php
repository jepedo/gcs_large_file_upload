<?php

namespace Drupal\Tests\gcs_large_file_upload\Unit;

use Drupal\gcs_large_file_upload\Service\PubSubClient;
use Google\Cloud\PubSub\PubSubClient as GooglePubSubClient;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Drupal\gcs_large_file_upload\Service\PubSubClient
 */
class PubSubClientTest extends TestCase {
  /**
   * @covers ::__construct
   * @covers ::publishMessage
   */
  public function testPublishMessage() {
    $topicName = 'test-topic';
    $message = 'Test message';
    $projectId = 'test-project-id';
    $keyFilePath = 'path/to/test-keyfile.json';

    $googlePubSubClientMock = $this->createMock(GooglePubSubClient::class);
    $topicMock = $this->createMock(GooglePubSubClient::class);

    $googlePubSubClientMock->method('topic')->willReturn($topicMock);
    $topicMock->method('publish')->willReturn(true);

    $pubSubClient = new PubSubClient($projectId, $keyFilePath);
    $pubSubClient->pubSubClient = $googlePubSubClientMock;

    $pubSubClient->publishMessage($topicName, $message);

    $this->assertTrue(true); // If no exception is thrown, the test passes.
  }
}
