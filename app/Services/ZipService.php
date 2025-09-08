<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ZipService
{
    public function gerarZip(Collection $arquivos, string $nomeZip)
    {
        $zip_fullfilename = storage_path('app/temp/' . $nomeZip);
        $zip_path = dirname($zip_fullfilename);
        if (!File::exists($zip_path))
            File::makeDirectory($zip_path, 755, true);

        $zip = new ZipArchive();
        if ($zip->open($zip_fullfilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($arquivos as $arquivo) {
                $file_path = Storage::path($arquivo->caminho);
                if (file_exists($file_path)) {
                    $zip->addFile($file_path, $arquivo->nome_original);
                }
            }
            $zip->close();
            return $zip_fullfilename;
        }

        return false;
    }
}
