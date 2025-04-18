<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

use App\Models\Base\FileStorage;

use App\Services\FileStorageService;

use App\Http\Resources\Base\FileStorage\FileStorageResource;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

use Jiannei\Response\Laravel\Support\Facades\Response;

class FileStorageController extends Controller
{
    public function getFile(Request $request, $showType='d', $uuid, $filename = null){
        $fileStorage = FileStorage::where('id', $uuid)->first();

        if(!$fileStorage){
            return response(null, 404);
        }

        $real_file_path = $fileStorage->path . '/' . $fileStorage->file_storage_name;

        $storage = Storage::disk($fileStorage->driver);

        if(!$storage->exists($real_file_path)){
            return response(null, 404);
        }

        $downloadFileName = '';

        if(!!$fileStorage){
            $downloadFileName = (($fileStorage->download_name ?: $fileStorage->origin_name) ?: $fileStorage->file_storage_name) . '.' . $fileStorage->file_json->file_ext;

            if(!!$filename){
                $downloadFileName = $filename . '.' . $fileStorage->file_json->file_ext;
            }
        }

        $file = $storage->get($real_file_path);
        $mime_type = $storage->mimeType($real_file_path);

        switch ($showType) {
            case 'r':
                $downloadMode = 'inline';
                break;
            case 'd':
                $downloadMode = 'attachment';
                break;
            default:
                break;
        }
        
        $request->whenHas('download', function($download) use (&$downloadMode){
            if($download === 'y'){
                $downloadMode = 'application/octet-stream';
            }
        });

        $downloadFileName = urlencode($downloadFileName);

        return response($file)
                ->header('Cache-Control', 'public,max-age=604800')   //檔案下載瀏覽器Cache(7天)
                ->header('Content-Type', $mime_type)
                ->header('Content-Disposition', "$downloadMode; filename={$downloadFileName}; filename*=utf-8''{$downloadFileName}");
    }

    public function fileInfo($uuid)
    {
        $fileStorage = FileStorage::findOrFail($uuid);

        return Response::success(new FileStorageResource($fileStorage));
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
            'file_name' => 'sometimes|nullable',
        ], [], [
            'file' => '檔案',
            'file_name' => '檔案名稱',
        ]);

        try {
            if($request->file('file') !== null){
                //檔案上傳
                $fileStorage = FileStorageService::uploadFile('file', $request->input('file_name'));
            }else{
                //Base64 OR Url
                $fileStorage = FileStorageService::uploadFile($request->get('file'), $request->input('file_name'));
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return Response::success(new FileStorageResource($fileStorage));
    }
}
