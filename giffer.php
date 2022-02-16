<?php

class Giffer {
    
    public function __construct($arr, $srcPath, $saveDirectoryPath) {
        $this->width = $arr[1];
        $this->height = $arr[2];
        $this->watermarkPath = $arr[3];
        $this->srcPath = $srcPath;
        $this->saveDirectoryPath = $saveDirectoryPath;
        $this->gifFile = new Imagick($srcPath);
        $this->newGifFile = new Imagick();
        $this->pathOfSaveFrames = "";

    }
// функция для корректировки png изображения 
    private function alphaBlend ($i) {
        imagealphablending($i, false);
        imagesavealpha($i, true);
    }

// функция измененения размеров фрэймов gif
    private function createPicWithNewSize ($i, $width, $height) {
        $newImage = imagecreate($width, $height);
        $color = imagecolorallocate($newImage, 0, 0, 0);
        imagecolortransparent($newImage, $color);
        $this->alphaBlend ($newImage);
        imagecopyresized($newImage, $i, 0, 0, 0, 0,  $width, $height, imagesx($i), imagesy($i));
        return $newImage;
    }
    
// функция загрузки, сохранения и изменения размеров png
    public function loadPng ($adress, $userWidth, $userHeight) {
        $png = imageCreateFromPng($adress);
        $resizedPng = $this->createPicWithNewSize ($png, $userWidth, $userHeight);
        imagepng($resizedPng, $this->pathOfSaveFrames . "/frames/watermark.png");
    }
// функция получения пути к директории сохранения и обработки фрэймов
    public function createSavePath () {
        $path = explode("\\", $this->saveDirectoryPath);
        unset($path[count($path) - 1]);
        $this->pathOfSaveFrames = implode("\\", $path);
        $this->saveFramePath = $this->saveFramePath . "\\";
        echo $this->pathOfSaveFrames;
    }
// функция разбивки на фрэймы
    public function getFramesOfGif () {
        mkdir($this->pathOfSaveFrames . "/frames", 0700);
        $count = 0;
        foreach ($this->gifFile as $frame) {
            // $frame->thumbnailImage(intval($this->width), intval($this->height)); // Изменение размеров. Некорректная функция.
            $frame->writeImage($this->pathOfSaveFrames . "/frames/" . "frame{$count}.gif");
            $count++;
        }
    }
// функция добавления водяного знака и изменения размера фрэйма
    public function addWaterMarkInFrame () {
        $framesDir = $this->pathOfSaveFrames . "\\frames/";
        $frames = scandir($framesDir);
        $this->loadPng($this->watermarkPath, 80, 40);

        for($i = 0; $i < count($frames) - 2; $i++) {

            $frame = imagecreatefromgif($framesDir . "/frame{$i}.gif");
            $watermark = imagecreatefrompng($framesDir . "/watermark.png");
            $frameWithNewSize = $this->createPicWithNewSize($frame, intval($this->width), intval($this->height));
            imagecopymerge($frameWithNewSize, $watermark, 0, 0, 0, 0, 80, 40, 30);
            unlink($framesDir . "/frame{$i}.gif");
            imagegif($frameWithNewSize, $framesDir . "/frame{$i}.gif");
            imagedestroy($frameWithNewSize);
        }

    }

    // функция склеивания фрэймов в цельный gif
    public function makeNewGif () {
        $framesDir = $this->pathOfSaveFrames . "/frames/";
        $frames = scandir($framesDir);
        for($i = 0; $i < count($frames) - 3; $i++) {
            $frame = new Imagick($framesDir . "frame{$i}.gif");
            $this->newGifFile->newImage($this->width, $this->height, $this->gifFile->getImageBackgroundColor());
            $this->newGifFile->compositeImage($frame, imagick::COMPOSITE_DISSOLVE, 0, 0);
            $this->newGifFile->setImageDelay($this->gifFile->getImageDelay());
            unlink($framesDir . "frame{$i}.gif");
        }

        unlink($this->pathOfSaveFrames . "\\frames" . "/watermark.png");
        rmdir($this->pathOfSaveFrames . "/frames");
        $this->newGifFile->setFormat("gif");
        $this->newGifFile->writeImages($this->saveDirectoryPath, true);
    }

    
    
}

$gif = new Giffer($argv, $argv[4], $argv[5]);
$gif-> createSavePath();
$gif-> getFramesOfGif();
$gif-> addWaterMarkInFrame();
$gif-> makeNewGif();
echo "0";