<?php

namespace App\Helpers;

class ImageResize
{
    private $srcPath;
    private $destPath;
    private $extension;

    private $E_UNSUPPORTED_TYPE = 1;
    private $E_TOO_SMALL = 2;
    private $E_FILE_NOT_FOUND = 3;

    private $lastError = false;

    function __construct($srcPath = null, $destPath = null, $extension = null)
    {
        $this->srcPath = $srcPath;
        $this->destPath = $destPath;
        $this->extension = $extension;
    }

    function setSourcePath($path)
    {
        $this->srcPath = $path;
    }

    function setDestPath($path)
    {
        $this->destPath = $path;
    }

    function setExtension($ext)
    {
        $this->extension = $ext;
    }

    function resize($width, $height)
    {
        return $this->imageResize($this->srcPath, $this->destPath, $height, $width);
    }

    function crop($width, $height)
    {
        return $this->imageResize($this->srcPath, $this->destPath, $height, $width, true);
    }

    function hasError()
    {
        return $this->lastError !== false;
    }

    function getLastError($asString = false)
    {
        if ($asString && $this->lastError) {
            switch ($this->lastError) {
                case $this->E_UNSUPPORTED_TYPE:
                    return 'UNSUPPORTED_TYPE';
                break;
                case $this->E_TOO_SMALL:
                    return 'E_TOO_SMALL';
                break;
                case $this->E_FILE_NOT_FOUND:
                    return 'E_FILE_NOT_FOUND';
                break;
            }
        }

        return $this->lastError;
    }

    private function imageResize($srcPath, $destPath, $toHeight, $toWidth, $crop = false)
    {
        $this->lastError = false;

        if (empty($destPath)) {
            $destPath = $srcPath;
        }

        $imageData = $this->getImageData($srcPath);
        $resizeData = $this->getResizeData($srcPath, $toHeight, $toWidth, $crop);

        if (strripos($destPath, '.') === false) {
            $destPath.='.'.$imageData['extension'];
        }

        if (!$resizeData) {
            return false;
        }

        if ($crop) {
            $imageData['width'] = $resizeData['width'];
            $imageData['height'] = $resizeData['height'];
        } else {
            $toWidth = $resizeData['width'];
            $toHeight = $resizeData['height'];
        }

        if ($imageData['extension'] == "gif" or $imageData['extension'] == "png") {
            imagecolortransparent($resizeData['thumb'], imagecolorallocatealpha($resizeData['thumb'], 0, 0, 0, 127));
            imagealphablending($resizeData['thumb'], false);
            imagesavealpha($resizeData['thumb'], true);
        }

        imagecopyresized($resizeData['thumb'], $imageData['image'], 0, 0, $resizeData['offset-width'], 0, $toWidth, $toHeight, $imageData['width'], $imageData['height']);

        switch ($imageData['extension']) {
            case 'bmp':
                imagewbmp($resizeData['thumb'], $destPath);
                break;
            case 'gif':
                imagegif($resizeData['thumb'], $destPath);
                break;
            case 'jpg':
                imagejpeg($resizeData['thumb'], $destPath);
                break;
            case 'png':
                imagepng($resizeData['thumb'], $destPath);
                break;
        }

        return true;
    }

    private function getResizeData($path, $toHeight, $toWidth, $crop)
    {
        $imageData = $this->getImageData($path);

        if (!$imageData) {
            return false;
        }

        if ($crop) {
            if ($imageData['width'] < $toWidth or $imageData['height'] < $toHeight) {
                $this->setLastError($this->E_TOO_SMALL);
                return false;
            }

            $ratio = max($toWidth/$imageData['width'], $toHeight/$imageData['height']);
            $h = $toHeight / $ratio;
            $x = ($imageData['width'] - $toWidth / $ratio) / 2;
            $w = $toWidth / $ratio;
            $thumb = imagecreatetruecolor($toWidth, $toHeight);
        } else {
            if ($imageData['width'] < $toWidth and $imageData['height'] < $toHeight) {
                $this->setLastError($this->E_TOO_SMALL);
                return false;
            }

            $ratio = min($toWidth/$imageData['width'], $toHeight/$imageData['height']);
            $w = $imageData['width'] * $ratio;
            $h = $imageData['height'] * $ratio;
            $x = 0;
            $thumb = imagecreatetruecolor($w, $h);
        }

        return [
            'height' => $h,
            'width' => $w,
            'offset-width' => $x,
            'thumb' => $thumb
        ];
    }

    private function getImageData($path)
    {
        if (!file_exists($path)) {
            $this->setLastError($this->E_FILE_NOT_FOUND);
            return false;
        }

        if (!list($width, $height) = getimagesize($path)) {
            $this->setLastError($this->E_UNSUPPORTED_TYPE);
            return false;
        }

        $extension = $this->extractExtension($path);

        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }

        $img = null;

        switch ($extension) {
            case 'bmp':
                $img = @imagecreatefromwbmp($path);
                break;
            case 'gif':
                $img = @imagecreatefromgif($path);
                break;
            case 'jpg':
                $img = @imagecreatefromjpeg($path);
                break;
            case 'png':
                $img = @imagecreatefrompng($path);
                break;
        }

        if (!$img) {
            $img = @imagecreatefromstring(file_get_contents($path));

            if (!$img) {
                $this->setLastError($this->E_UNSUPPORTED_TYPE);
                return false;
            }
        }

        return [
            'height' => $height,
            'width' => $width,
            'extension' => $extension,
            'image' => $img
        ];
    }

    private function extractExtension($path)
    {
        if ($this->extension != null) {
            return $this->extension;
        } else {
            return strtolower(substr(strrchr($path, "."), 1));
        }
    }

    private function setLastError($errCode)
    {
        $this->lastError = $errCode;
    }
}
