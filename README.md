# GCS Large File Upload Module

This Drupal module provides functionality for uploading files larger than a specified size to a public Google Cloud Storage (GCS) bucket. It also triggers a Pub/Sub message upon successful upload and removes the file from the web server.

## Features

- Upload files larger than a configurable size to GCS.
- Authenticate with Google Cloud services using a service account keyfile.
- Trigger a Pub/Sub message upon successful file upload.
- Store the GCS file URL in a custom field on the file entity.
- Remove the file from the web server after successful upload to GCS.
- Hooks into Drupal's file system to handle file uploads.

## Requirements

- Drupal 9 or higher.
- Google Cloud account with access to Google Cloud Storage and Pub/Sub.
- PHP 7.2 or higher.

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

## Configuration

1. Navigate to the module configuration page: `/admin/config/gcs-large-file-upload`.
2. Set the following configuration options:
   - **File size limit (MB)**: The maximum file size (in MB) that will be uploaded to GCS.
   - **GCS Project ID**: The Google Cloud project ID.
   - **GCS Bucket Name**: The name of the Google Cloud Storage bucket.
   - **GCS Keyfile**: Upload the JSON keyfile for your Google Cloud service account.
   - **Pub/Sub Message**: The message to be sent to Pub/Sub upon successful file upload. Leave empty to disable.
   - **Pub/Sub Topic**: The Pub/Sub topic to publish messages to.

## Usage

1. Upload a file through any Drupal file upload interface.
2. Files larger than the configured size limit (in MB) will be automatically uploaded to GCS.
3. Upon successful upload, the GCS file URL will be stored in a custom field on the file entity.
4. A Pub/Sub message will be sent to the configured topic (if provided).
5. The file will be removed from the web server after successful upload to GCS.

## Code Overview

### GCSUploadSettingsForm

The `GCSUploadSettingsForm` class defines the configuration form for the module. It allows users to set the file size limit, GCS project ID, bucket name, keyfile, Pub/Sub message, and Pub/Sub topic.

### GCSClient

The `GCSClient` class handles the interaction with Google Cloud Storage. It uploads files to the specified bucket and returns the URL of the uploaded file.

### PubSubClient

The `PubSubClient` class handles the interaction with Google Pub/Sub. It publishes messages to the specified topic.

### gcs_large_file_upload.module

The main module file defines the following hooks:
- `hook_help()`: Provides help text for the module.
- `hook_form()`: Defines the form for uploading files to GCS.
- `hook_file_insert()`: Handles file uploads and uploads files larger than the configured size limit to GCS. It also stores the GCS file URL in a custom field, sends a Pub/Sub message, and removes the file from the web server.

## Error Handling

The module includes error handling and logging to ensure that any issues encountered during file upload or Pub/Sub message publishing are logged and displayed to the user.

## Security Considerations

- Ensure that the keyfile is stored in a secure location with restricted file permissions.
- Use environment variables or a secrets management service to store sensitive information securely.

## License

This module is licensed under the MIT License. See the LICENSE file for more information.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request on GitHub.

## Support

If you encounter any issues or have questions, please open an issue on GitHub or contact the module maintainer.