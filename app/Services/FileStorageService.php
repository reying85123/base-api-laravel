<?php

namespace App\Services;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Base\FileStorage;

class FileStorageService
{
    public static function uploadFile($file, $filename = null, $path = null)
    {
        $file_data = null;
        $file_resource = collect([]);

        if (filter_var($file, FILTER_VALIDATE_URL)) {
            //檔案網址
            $file_data = file_get_contents($file);
            $file = FileStorageService::getFileResource($file_data);
            $file_resource = $file['info'];
        } else if (is_string($file) && ($fileType = FileStorageService::getBase64FileType($file))) {
            //BASE64(圖片、音檔、檔案)
            $file = preg_replace('#^data:' . $fileType . '/\w+;base64,#i', '', $file);
            $file_data = base64_decode($file);
            $file = FileStorageService::getFileResource($file_data);
            $file_resource = $file['info'];
        } else if (request()->hasFile($file)) {
            //FormData檔案上傳            
            $file_data = request()->file($file);
            $file = FileStorageService::getFileResource(file_get_contents($file_data->path()));
            $file_resource = $file['info'];
            $file_resource['origin_name'] = explode('.', $file_data->getClientOriginalName())[0] ?? '';
        }

        abort_if(is_null($file_data), 400, '找不到上傳檔案');

        $file_resource['name'] = ($filename ?: $file_resource->get('origin_name', '')) . '.' . $file_resource->get('file_ext', '');

        $path = $file_resource['file_path'] = ($path ?: FileStorageService::getDeafultFolder($file_resource['name']));

        $downloadFileName = urlencode($file_resource['name']);

        //2023-09-28，先註解此功能
        // if($filename !== null){
        //自定義上傳檔案名稱
        // $filename .= (count(explode('.', $filename)) < 2 ? ('.' . $file_resource->get('file_ext', '')): '');

        // $storage_name = Storage::putFileAs($file_resource['file_path'], $file_data, $filename, [
        //     'visibility' => 'public',
        //     'ContentDisposition' => "application/octet-stream; filename*=utf-8''{$downloadFileName}",
        // ]);
        // }else{
        //系統產生上傳檔案名稱
        $storage_name = Storage::putFile($file_resource['file_path'], $file['tmp_file'], [
            'visibility' => 'public',
            'ContentDisposition' => "application/octet-stream; filename*=utf-8''{$downloadFileName}",
        ]);
        // }

        if (!!$storage_name) {
            $storage_name = collect(explode('/', $storage_name))->last();
            $file_resource['file_storage_name'] = $storage_name;
        }

        $fileStorage = new FileStorage([
            'origin_name' => $file_resource['origin_name'],
            'download_name' => $filename ?: $file_resource['origin_name'],
            'file_storage_name' => $file_resource['file_storage_name'],
            'path' => $file_resource['file_path'],
            'file_json' => $file_resource->toArray(),
            'driver' => config('filesystems.default'),
        ]);

        $fileStorage->save();

        return $fileStorage;
    }

    public static function deleteFileByPath($file_paths)
    {
        if (!is_array($file_paths)) {
            $file_paths = [$file_paths];
        }

        $result = Storage::delete($file_paths);

        return $result;
    }

    public static function deleteFileById($id)
    {
        $fileStorage = FileStorage::whereId($id)->first();

        if (!$fileStorage) {
            return false;
        }

        $result = Storage::delete("{$fileStorage->path}/{$fileStorage->file_storage_name}");

        if ($result) {
            $fileStorage->delete();
        }

        return $result;
    }

    private static function getBase64FileType($base64Data)
    {
        $base64FileTypes = ['image', 'application', 'video', 'audio'];
        foreach ($base64FileTypes as $base64FileType) {
            if (FileStorageService::isBase64FileType($base64Data, $base64FileType)) {
                return $base64FileType;
            }
        }
        return null;
    }

    private static function isBase64FileType($base64Data, $fileType)
    {
        $regex = '#^data:' . $fileType . '/\w+;base64,#i';
        $base64 = preg_replace($regex, '', $base64Data);
        $decodedData = base64_decode($base64);
        $f = finfo_open();
        $result = finfo_buffer($f, $decodedData, FILEINFO_MIME_TYPE);
        return !is_bool(strpos($result, $fileType));
    }

    private static function getFileResource(string $file_data)
    {
        $tmp_file_path = sys_get_temp_dir() . '/' . uniqid('tmp_files_');
        file_put_contents($tmp_file_path, $file_data);
        $tmp_file = new File($tmp_file_path);
        $file_ext = $tmp_file->extension();

        $file_resource = collect([
            'tmp_name' => $tmp_file->getFilename() . '.' . $file_ext,
            'origin_name' => '',
            'name' => '',
            'file_mime' => $tmp_file->getMimeType(),
            'file_ext' => $file_ext,
            'size' => $tmp_file->getSize(),
            'file_path' => '',
            'file_storage_name' => '',
        ]);

        return [
            'tmp_file' => $tmp_file,
            'info' => $file_resource,
        ];
    }

    private static function getDeafultFolder($filesname)
    {
        $tmpclassfolder = "";
        if ($filesname == "") {
            $tmpclassfolder = "public";
        } else {
            switch (strtolower(pathinfo($filesname, PATHINFO_EXTENSION))) {
                case 'xls':
                case 'xlsx':
                case 'doc':
                case 'docx':
                case 'ppt':
                case 'pptx':
                case 'pdf':
                case 'txt':
                case 'csv':
                case 'zip':
                case '7z':
                case 'gzip':
                case 'iso':
                case 'rar':
                case 'tar':
                    $tmpclassfolder = "files";
                    break;
                case 'bmp':
                case 'gif':
                case 'jpeg':
                case 'jpg':
                case 'png':
                case 'ico':
                case 'tif':
                case 'tiff':
                    $tmpclassfolder = "images";
                    break;
                case 'mp3':
                case 'avi':
                case 'mp4':
                case 'wav':
                case 'flv':
                case 'mpg':
                case 'mpeg':
                case 'mov':
                case 'rmvb':
                case 'wmv':
                case 'swf':
                    $tmpclassfolder = "video";
                    break;
                default:
                    $tmpclassfolder = "other";
                    break;
            }
        }
        return $tmpclassfolder;
    }

    /**
     * 取得檔案資料流
     *
     * @param \App\Models\Base\FileStorage|string $file
     */
    public static function getFileContent($file)
    {
        if (!$file instanceof FileStorage) {
            $file = FileStorage::query()->find($file);
        }

        if ($file === null) {
            return null;
        }

        $storage = Storage::disk($file->drive);

        return $storage->get($file->fileStoragePath);
    }

    /**
     * 取得檔案url
     *
     * @param \App\Models\Base\FileStorage|string $file
     */
    public static function getFileUrl($file)
    {
        $url = null;
        switch (config('filesystems.default')) {
            case 's3':
                $url = Storage::url($file->path . '/' . $file->file_storage_name);
                break;
            default:
                $url = route('files', ['file_uuid' => $file->id, 'show_type' => 'd']);
                break;
        }
        return $url;
    }
}
