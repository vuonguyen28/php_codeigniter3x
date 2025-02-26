<?php

namespace Cloudinary;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;

class Uploader
{
    /**
     * Upload file lên Cloudinary
     *
     * @param string $filePath Đường dẫn file cần upload
     * @param array $options Các tùy chọn upload (tags, folder, format, v.v.)
     * @return array Kết quả upload từ Cloudinary
     * @throws \Exception Nếu có lỗi xảy ra
     */
    public static function upload($filePath, $options = [])
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File không tồn tại: " . $filePath);
        }

        try {
            $uploadApi = new UploadApi();
            $result = $uploadApi->upload($filePath, $options);
            return $result;
        } catch (ApiError $e) {
            throw new \Exception("Lỗi upload Cloudinary: " . $e->getMessage());
        }
    }
}
