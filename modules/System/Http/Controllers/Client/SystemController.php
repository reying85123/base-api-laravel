<?php

namespace Modules\System\Http\Controllers\Client;

use App\Http\Controllers\Controller;

use Modules\System\Models\Viewcount;

use Modules\System\Http\Resources\Client\ViewcountResource;

use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class SystemController extends Controller
{
    public function getViewcount(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $viewcount = Viewcount::query();

        if ($request->anyFilled(['start_date', 'end_date'])) {
            $viewcount->where(function ($query) use ($request) {
                if (($start = $request->date('start_date')) !== null) {
                    $query->startOf('created_at', $start->startOfDay());
                }

                if (($end = $request->date('end_date')) !== null) {
                    $query->endOf('created_at', $end->endOfDay());
                }
            });
        }

        $data = collect([
            'count' => $viewcount->count(),
        ]);

        return Response::success(new ViewcountResource($data));
    }
}
