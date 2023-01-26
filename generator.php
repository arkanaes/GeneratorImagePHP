<?php

//Amount image to create
$amountIMG = 50;

//path to save
$path =  "captcha";

//file to save reference
$fileNameToSave = "list-reference.txt";

/*
    Fazer um verificador de codigo, para ver se o código já não existe
*/

for ($i = 0; $i < $amountIMG; $i++) {



    // get image local 84 x 28    
    $img = imagecreatefromjpeg('background.jpg');

    // color text rgb
    $textcolor = imagecolorallocate($img, 88, 88, 87);

    // check if file exist
    if (file_exists($fileNameToSave)) {
        while (true) {
            //Generate text random
            $randText = substr(md5(microtime()), rand(0, 26), 5);

            if (!checkIfExistCaptcha($randText, $fileNameToSave)) {
                break;
            }

        }
        while (true) {
           
            // image name to save
            $imgFileName = strval(rand(100000, 800000));

            if (!checkIfExistCaptcha($imgFileName, $fileNameToSave)) {
                break;
            }
        }
    } else {
        //Generate text random
        $randText = substr(md5(microtime()), rand(0, 26), 5);
         // image name to save
        $imgFileName = strval(rand(100000, 800000));
    }



    // Write the string at the top left
    imagestring($img, 5, 20, 6, $randText, $textcolor);


    //Save img
    imagejpeg($img, $path . DIRECTORY_SEPARATOR . $imgFileName . ".jpg");

    //closed img
    imagedestroy($img);

    $fileSaveText = fopen($fileNameToSave, 'a') or die("Unable to open file!");;

    //Struct to save info about captcha img and code
    $captchaText = <<<text
        $imgFileName => "$randText",\n
    text;

    //write into file
    fwrite($fileSaveText, $captchaText);

    fclose($fileSaveText);
}
/**
 * @description check if already created the code
 * 
 * @param string $code | Code random exemple 4Ec1R
 * @param string $fileList | Exemple  list.txt 
 * 
 */
function checkIfExistCaptcha($code, $fileList): bool
{
    //Create handle, only read of file
    $file = fopen($fileList, 'r');
    //Get size of file
    $fileSize = filesize($fileList);
    //Load in memory all binary data
    $file = fread($file, $fileSize);

    //check if already exists this word
    if (strpos($file, $code) !== false) {
        return true;
    } else {
        return false;
    }
}
