<?php

namespace Modules\MailLog\Http\Controllers\Admin;

use Modules\MailLog\Models\MailLog;

use Modules\MailLog\Http\Resources\Admin\MailLogResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class MailLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
            'start_date' => 'sometimes|string',
            'end_date' => 'sometimes|string',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $mailLog = MailLog::query();

        if($keyword = $request->input('keyword')){
            $mailLog->whereKeyword(['to', 'subject', 'content'], $keyword);
        }

        if($orderby = $request->input('orderby')){
            $mailLog->queryOrderBy($orderby);
        }

        if($startDate = $request->input('start_date')){
            $mailLog->startOf('send_datetime', getDateTime($startDate, true)->startOfDay());
        }

        if($endDate = $request->input('end_date')){
            $mailLog->endOf('send_datetime', getDateTime($endDate, true)->endOfDay());
        }

        if($request->filled(['page_size', 'page'])){
            $data = $mailLog->paginate(intval($request->get('page_size')), ['*'], 'page', intval($request->get('page')));
        }else{
            $data = $mailLog->get();
        }

        return Response::success(MailLogResource::collection($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Response::success(new MailLogResource(MailLog::findOrFail($id)));
    }
}
