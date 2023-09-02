<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PicturesService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        // * on renomme l'image
        $file = md5(uniqid(rand(), true)).'webp';

        // * on recupere les données de l'img

        $imgInfo = getimagesize($picture);

        if(!$imgInfo) {
            throw new Exception("Format d'image incorrect");
        }

        // * onverifie le format de l'img
        switch ($imgInfo['mime']) {
            case 'image/png':
                $img_source = imagecreatefrompng($picture);
                break;

            case 'image/jpg':
                $img_source = imagecreatefromjpeg($picture);
                break;

            case 'image/jpeg':
                $img_source = imagecreatefromjpeg($picture);
                break;

            case 'image/webp':
                $img_source = imagecreatefromwebp($picture);
                break;

            default:
                throw new Exception("Format d'image incorrect");
                break;
        }

        // * on recadre l'image
        $imgWidth = $imgInfo[0];
        $imgHeight = $imgInfo[1];

        switch ($imgWidth <=> $imgHeight) {
            case -1: // portrait
                $squareSize = $imgWidth;
                $src_x = 0;
                $src_y = ($imgHeight - $squareSize) / 2;
                break;

            case 0: //carrée
                $squareSize = $imgWidth;
                $src_x = 0;
                $src_y = 0;
                break;

            case 1: // paysage
                $squareSize = $imgHeight;
                $src_x = ($imgWidth - $squareSize) / 2;
                $src_y = 0;
                break;

        }

        // * On crée une nouvelle image "vierge"
        $resized_picture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resized_picture, $img_source, 0, 0, $src_x, $src_y, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('images_directory').$folder;

        // * on crée le dossier si'll n'existe pas
        if (!file_exists($path.'/mini/')) {
            mkdir($path.'/mini/', 0755, true);
        }

        // * on stock l'image recadrée
        imagewebp($resized_picture, $path.'/mimi/'.$width.'x'.$height.'-'.$file);

        $picture->move($path.'/'.$file);
        return($file);

    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if($fichier !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/mini/' . $width . 'x' . $height . '-' . $fichier;

            if(file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if(file_exists($original)) {
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}
