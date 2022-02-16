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
        imagealphablending($this->newPng, false);
        imagesavealpha($this->newPng, true);
        imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0, $this->width, $this->height,  $this->width, $this->height);
    }


    private function alphaBlend ($i) {
        imagealphablending($i, false);
        imagesavealpha($i, true);
    }

    public function setWidth ($width) {
        if($width === 0 || false) {
            $this->setWidth = $this->height;
        } else {
            $this->setWidth = $width;
        }
        

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
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->setWidth, $this->setWidth / $this->coefficient, $this->width, $this->height);
                break;
            }

            case "fill": {
                $this->newPng = imagecreatetruecolor($this->setWidth, $this->setWidth / $this->coefficient);
                $this->alphaBlend ($this->newPng);
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->width, $this->height, $this->width, $this->height);
                break;
            }
        }
        
    }

    public function setHeight ($height) {

        if($height === 0 || false) {
            $this->setHeight = $this->width;
        } else {
            $this->setHeight = $height;
        }

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
                imagecopyresized($this->newPng, $this->basicPng, 0, 0, 0, 0,  $this->width, $this->height, $this->width, $this->height);
                break;
            }
        }

        
        
    }

    public function setResizeType ($type) {
        if(in_array($type, $this->types)) {
            $this->type = $type;
        } else {
            echo "error";
        }
    }

    public function showImg () {
        imagepng($this->newPng);
    }

    public function process ($outPath) {
        imagepng($this->newPng, $outPath);
        imagedestroy($this->newPng);
    }

    }


    //header("Content-type: image/png");
    $image = new Image("cat2.png");
    $image->setResizeType("fit");
    $image->setHeight(1000);
    //$image->showImg();
    $image->process("cat2resize.png");