# gcs_large_file_upload/gcs_large_file_upload/README.md

# GCS Large File Upload Module

This Drupal module provides functionality for uploading files larger than 5MB to a public Google Cloud Storage (GCS) bucket. It also triggers a Pub/Sub message upon successful upload.

## Features

- Upload files larger than 5MB to GCS.
- Authenticate with Google Cloud services.
- Trigger a Pub/Sub message upon successful file upload.
- Hooks into Drupal's file system to handle file uploads.

## Installation

1. Download the module and place it in the `modules/custom` directory of your Drupal installation.
2. Enable the module using Drush or the Drupal admin interface:
   - Using Drush: `drush en gcs_large_file_upload`
3. Configure the Google Cloud credentials and bucket settings in the module settings:
   - GCS Project ID
   - GCS Bucket Name
   - GCS Keyfile Path
   - Pub/Sub Topic (optional)
   - Pub/Sub Message (optional)

## Usage

1. Upload a file through any Drupal file upload interface.
2. Files larger than the configured size limit (in MB) will be automatically uploaded to GCS.
3. Upon successful upload, a Pub/Sub message will be sent to the configured topic.

## Requirements

- Drupal 9 or higher.
- Google Cloud account with access to Google Cloud Storage and Pub/Sub.
- PHP 7.2 or higher.

## License

This module is licensed under the MIT License. See the LICENSE file for more information.