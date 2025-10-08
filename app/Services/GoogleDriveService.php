<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    private $client;
    private $service;
    private $folderId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(config('services.google.credentials_path'));
        $this->client->addScope(Drive::DRIVE);
        $this->client->setAccessType('offline');
        
        $this->service = new Drive($this->client);
        $this->folderId = config('services.google.drive_folder_id');
    }

    /**
     * Upload a file to Google Drive
     */
    public function uploadFile($localFilePath, $fileName, $mimeType = null, $description = null)
    {
        try {
            // Set access token if available
            $this->setAccessToken();
            
            $file = new DriveFile();
            $file->setName($fileName);
            $file->setParents([$this->folderId]);
            
            if ($description) {
                $file->setDescription($description);
            }

            $result = $this->service->files->create(
                $file,
                [
                    'data' => file_get_contents($localFilePath),
                    'mimeType' => $mimeType ?: mime_content_type($localFilePath),
                    'uploadType' => 'multipart',
                    'fields' => 'id,name,webViewLink,webContentLink'
                ]
            );

            return [
                'success' => true,
                'file_id' => $result->getId(),
                'name' => $result->getName(),
                'web_view_link' => $result->getWebViewLink(),
                'web_content_link' => $result->getWebContentLink(),
            ];

        } catch (\Exception $e) {
            Log::error('Google Drive upload failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload file from Laravel Storage
     */
    public function uploadFromStorage($storagePath, $fileName, $mimeType = null, $description = null)
    {
        $localPath = Storage::path($storagePath);
        
        if (!file_exists($localPath)) {
            return [
                'success' => false,
                'error' => 'File not found in storage'
            ];
        }

        return $this->uploadFile($localPath, $fileName, $mimeType, $description);
    }

    /**
     * Delete a file from Google Drive
     */
    public function deleteFile($fileId)
    {
        try {
            $this->setAccessToken();
            $this->service->files->delete($fileId);
            
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Google Drive delete failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get file information from Google Drive
     */
    public function getFileInfo($fileId)
    {
        try {
            $this->setAccessToken();
            $file = $this->service->files->get($fileId, [
                'fields' => 'id,name,size,mimeType,webViewLink,webContentLink,createdTime,modifiedTime'
            ]);

            return [
                'success' => true,
                'file' => $file
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive get file info failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create a folder in Google Drive
     */
    public function createFolder($folderName, $parentFolderId = null)
    {
        try {
            $this->setAccessToken();
            
            $file = new DriveFile();
            $file->setName($folderName);
            $file->setMimeType('application/vnd.google-apps.folder');
            
            if ($parentFolderId) {
                $file->setParents([$parentFolderId]);
            } else {
                $file->setParents([$this->folderId]);
            }

            $result = $this->service->files->create($file, [
                'fields' => 'id,name,webViewLink'
            ]);

            return [
                'success' => true,
                'folder_id' => $result->getId(),
                'name' => $result->getName(),
                'web_view_link' => $result->getWebViewLink()
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive create folder failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set access token for authentication
     */
    private function setAccessToken()
    {
        $token = config('services.google.access_token');
        if ($token) {
            $this->client->setAccessToken($token);
        }
    }

    /**
     * Get authorization URL for OAuth
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Exchange authorization code for access token
     */
    public function exchangeCodeForToken($code)
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            return [
                'success' => true,
                'token' => $token
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive token exchange failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Refresh access token
     */
    public function refreshToken()
    {
        try {
            $this->setAccessToken();
            $token = $this->client->getAccessToken();
            
            if ($this->client->isAccessTokenExpired()) {
                $this->client->refreshToken($token['refresh_token']);
                $token = $this->client->getAccessToken();
            }

            return [
                'success' => true,
                'token' => $token
            ];
        } catch (\Exception $e) {
            Log::error('Google Drive token refresh failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}