<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2018/12/24
 * Time: 1:07 PM
 */

function getFiles($path,&$files)
{
    if(is_dir($path))
    {
        $dp = dir($path);
        while ($file = $dp ->read())
        {
            if($file !== "." && $file !== "..")
            {
                self::get_allfiles($path."/".$file, $files);
            }
        }
        $dp ->close();
    }
    if(is_file($path))
    {
        $files[] =  $path;
    }
}