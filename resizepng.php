<?php

class Image {

    public function __construct ($path) {
        $this->basicPng = imageCreateFromPng($path);
        $this->type = "force";
        $this->types = ["force", "fill", "fit"];
        $this->width = imagesx($this->basicPng);
        $this->height = imagesy($this->basicPng);
        $this->setHeight = $this->height;
        $this->setWidth = $this->width;
        $this->coefficient = $this->width / $this->height;
        $this->newPng = imagecreatetruecolor($this->width, $this->height);
    }

// функция корректировки цветов после ресайза
    private function alphaBlend ($i) {
        imagealphablending($i, false);
        imagesavealpha($i, true);
    }


// функция заливки фона
    function assignColor ($i, $red, $green, $blue) {
        $color = imagecolorallocatealpha($i, $red, $green, $blue, false);
        imagefill($i, 0, 0, $color);
    }

// функция ресайза по ширине
    public function setWidth ($width) {
        // если значение не задано, берем значение высоты картинки
        if($width === 0 || false) {
            $this->setWidth = $this->height;
        } else {
            $this->setWidth = $width;
        }
        
// Проверка на тип ресайза. По умолчанию "force"
        Switch ($this->type) {
            case "force": {
                $this->newPng = imagecreatetruecolor($this->setWidth, $this->setHeight);
                $this->alphaBlend ($this->newPng);
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->setWidth, $this->setHeight, $this->width, $this->height);
                break;
            }

            case "fit": {
                $this->newPng = imagecreatetruecolor($this->setWidth, $this->setWidth / $this->coefficient);
                $this->alphaBlend ($this->newPng);
                $this->setHeight = $this->setWidth / $this->coefficient;
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->setWidth, $this->setHeight, $this->width, $this->height);
                break;
            }

            case "fill": {
                $this->setHeight = $this->setWidth / $this->coefficient;
                $this->newPng = imagecreatetruecolor($this->setWidth, $this->setHeight);
                $this->alphaBlend ($this->newPng);
                $this->assignColor($this->newPng, 255, 255, 255);
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->width, $this->height, $this->width, $this->height);
                break;
            }
        }
        
    }
// функция ресайза по высоте
    public function setHeight ($height) {
// если значение не задано, берем значение ширины картинки
        if($height === 0 || false) {
            $this->setHeight = $this->width;
        } else {
            $this->setHeight = $height;
        }
// Проверка на тип ресайза. По умолчанию "force"
        Switch ($this->type) {

            case "force": {
                $this->newPng = imagecreatetruecolor($this->setWidth, $this->setHeight);
                $this->alphaBlend ($this->newPng);
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->setWidth, $this->setHeight, $this->width, $this->height);
                break;
            }

            case "fit": {
                $this->newPng = imagecreatetruecolor($this->setHeight * $this->coefficient, $this->setHeight);
                $this->alphaBlend ($this->newPng);
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->setHeight * $this->coefficient, $this->setHeight, $this->width, $this->height);
                break;
            }

            case "fill": {
                $this->newPng = imagecreatetruecolor($this->setHeight * $this->coefficient, $this->setHeight);
                $this->alphaBlend ($this->newPng);
                $this->assignColor($this->newPng, 255, 255, 255);
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->width, $this->height, $this->width, $this->height);
                break;
            }
        }

        
        
    }
// Функция установки типа ресайза. Проверяет на соответствие с вариантами ресайза в массиве
    public function setResizeType ($type) {
        if(in_array($type, $this->types)) {
            $this->type = $type;
        } else {
            $this->type = "force";
        }
    }
// Функция вывода в браузер 
    public function showImg () {
        imagepng($this->newPng);
    }
// Функция сохранения изображения по определенному пути
    public function process ($outPath) {
        imagepng($this->newPng, $outPath);
        imagedestroy($this->newPng);
    }

    }


   // header("Content-type: image/png");
    $image = new Image("watermark.png");
    $image->setResizeType("fill");
    $image->setHeight(3000);
   // $image->showImg();
    $image->process("cat2resize.png");