<?php

namespace Drupal\gcs_large_file_upload\Service;

use Google\Cloud\PubSub\PubSubClient;
use Drupal\file\Entity\File;
use Psr\Log\LoggerInterface;

class PubSubClient {
  protected $pubSubClient;
  protected $logger;

  /**
   * PubSubClient constructor.
   *
   * @param string $projectId
   *   The Google Cloud project ID.
   * @param int $keyfileId
   *   The file ID of the Google Cloud service account keyfile.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger service.
   */
  public function __construct($projectId, $keyfileId, LoggerInterface $logger) {
    $this->logger = $logger;
    $file = File::load($keyfileId);
    $keyFilePath = $file->getFileUri();
    $this->pubSubClient = new PubSubClient([
      'projectId' => $projectId,
      'keyFilePath' => $keyFilePath, // Use the keyfile path for authentication.
    ]);
  }

  /**
   * Publishes a message to a Pub/Sub topic.
   *
   * @param string $topicName
   *   The name of the Pub/Sub topic.
   * @param string $message
   *   The message to be published.
   *
   * @throws \Exception
   *   If an error occurs while publishing the message.
   */
  public function publishMessage($topicName, $message) {
    try {
      $topic = $this->pubSubClient->topic($topicName);
      $topic->publish([
        'data' => $message,
      ]);
    } catch (\Exception $e) {
      $this->logger->error('Error publishing message to Pub/Sub: @message', ['@message' => $e->getMessage()]);
      throw $e;
    }
  }
}
