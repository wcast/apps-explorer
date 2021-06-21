<?php

namespace WCast\Apps;

class AppsExplorer
{

    public function getApps($diretorio = '/var/www/WCast')
    {
        $scandir = scandir($diretorio);
        $scandir = array_filter($scandir, function ($pasta) {
            return !preg_match('/^\./', $pasta);
        });
        $pastas = [];
        foreach ($scandir as $pasta) {
            if (is_dir($diretorio . DIRECTORY_SEPARATOR . $pasta)) {
                $pastas[] = [
                    'nome' => $pasta,
                    'tamanho' => $this->converterBytes($this->foldersize($diretorio . DIRECTORY_SEPARATOR . $pasta),2),
                    'diretorio' => encrypt($diretorio . DIRECTORY_SEPARATOR . $pasta)
                ];
            }
        }
        return $pastas;
    }

    public function foldersize($path) {
        $total_size = 0;
        $files = scandir($path);

        foreach($files as $t) {
            if (is_dir(rtrim($path, '/') . '/' . $t)) {
                if ($t<>"." && $t<>"..") {
                    $size = $this->foldersize(rtrim($path, '/') . '/' . $t);

                    $total_size += $size;
                }
            } else {
                $size = filesize(rtrim($path, '/') . '/' . $t);
                $total_size += $size;
            }
        }
        return $total_size;
    }

    private function converterBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'kB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}
