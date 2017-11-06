<?php

namespace Upadd\Bin;

class Debug{


    /**
     * 获取文件加载信息
     * @param bool  $detail 是否显示详细
     * @return integer|array
     */
    public static function getLoadFile($detail = false)
    {
        if ($detail) {
            $files = get_included_files();
            $info  = [];
            foreach ($files as $key => $file) {

                $info[] = $file . ' ( ' . number_format(filesize($file) / 1024, 2) . ' KB )';
            }
            return $info;
        }
        return count(get_included_files());
    }


    public static function trace()
    {
        $array = debug_backtrace();
        unset($array[0]);
        $data = [];
        foreach ($array as $row)
        {
            $file = isset($row['file']) ? $row['file'] : $row['class'];
            $line = isset($row['line']) ? $row['line'] : '';
            $function = $row['function'];
            $data [] = [
                'function'=>$function,
                'line'=>$line,
                'file'=>$file,
            ];
        }
        return $data;
    }




}