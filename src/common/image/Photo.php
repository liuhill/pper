<?php
/**
 * Created by PhpStorm.
 * User: liuligang
 * Date: 2018/12/20
 * Time: 3:41 PM
 * 图片处理
 */

namespace Common\Image;

use Common\Curl\Curlex;

class Photo
{

    static public function list($dir){
        $files = [];
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $i = 0;
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        //exit($dir.DIRECTORY_SEPARATOR.$file);
                        $files[$i]["name"] = $file;//获取文件名称
                        $files[$i]["size"] = round((filesize($dir.DIRECTORY_SEPARATOR.$file)/1024),2);//获取文件大小
                        $files[$i]["time"] = date("Y-m-d H:i:s",filemtime($dir.DIRECTORY_SEPARATOR.$file));//获取文件最近修改日期
                        $i++;
                    }
                }
            }
            closedir($dh);
            foreach($files as $k=>$v){
                $size[$k] = $v['size'];
                $time[$k] = $v['time'];
                $name[$k] = $v['name'];
            }
            array_multisort($time,SORT_DESC,SORT_STRING, $files);//按时间排序
            //array_multisort($name,SORT_DESC,SORT_STRING, $files);//按名字排序
            //array_multisort($size,SORT_DESC,SORT_NUMERIC, $files);//按大小排序
        }
        return $files;
    }


    static public function getFormate($url){
        $imageTypeArray = array
        (
            0=>'UNKNOWN',
            1=>'GIF',
            2=>'JPEG',
            3=>'PNG',
            4=>'SWF',
            5=>'PSD',
            6=>'BMP',
            7=>'TIFF_II',
            8=>'TIFF_MM',
            9=>'JPC',
            10=>'JP2',
            11=>'JPX',
            12=>'JB2',
            13=>'SWC',
            14=>'IFF',
            15=>'WBMP',
            16=>'XBM',
            17=>'ICO',
            18=>'COUNT'
        );
        $size = getimagesize($url);
        return $imageTypeArray[$size[2]];
    }


    static public function download($url,$dir){

        if(!is_dir($dir)){
            mkdir($dir,0775,true);
        }
        //去除URL连接上面可能的引号
        $url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url );

        $ext = self::getFormate($url);
        list($msec,$sec) = explode ( " ", microtime () );
        $seq = str_replace('0.','~',$msec);
        $filename = date('Ymd~His',$sec). "$seq.$ext";

        $originalFile = $dir . DIRECTORY_SEPARATOR . $filename;
        $res = Curlex::get($url,$originalFile);
        if($res === true){
            return $originalFile;
        }
        else{
            return $res;
        }
    }


    /**
     * @param $src : 源图片路径
     * @param $dest  : 目标图片路径
     * @param array $size   : 图片的 [宽度,高度]
     * @return bool
     */
    static public function resize($src,$dest,$size = [120,160]){

        if(!file_exists($src)){
            return "源文件不存在: $src";
        }

        $destDir = dirname($dest);
        if(!is_dir($destDir))
        {
            mkdir($destDir,0775,true);
        }

        list($distWidth,$distHeight) = $size;

        list($width, $height) = getimagesize($src);
        $percent = 1;
        if($width /$height >= $distWidth/$distHeight)
        {

            if($width > $distWidth)
            {
                $percent = $distHeight/$height;
            }
        }
        else
        {
            if($height > $distHeight)
            {
                $percent = $distWidth/$width;
            }
        }
        $new_width = $width * $percent;
        $new_height = $height * $percent;
        //创建新的图片此图片的标志为$image_p
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($src);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Output
        imagejpeg($image_p, $dest, 100);//quality为图片输出的质量范围从 0（最差质量，文件更小）到 100（最佳质量，文件最大）。

        return true;
    }

}