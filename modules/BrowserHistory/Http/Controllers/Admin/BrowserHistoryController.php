<?php

namespace Modules\BrowserHistory\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Modules\BrowserHistory\Models\BrowserHistory;

use Modules\BrowserHistory\Http\Resources\Admin\BrowserHistoryResource;
use Modules\BrowserHistory\Http\Resources\Admin\DeviceTypeChartReportResource;
use Modules\BrowserHistory\Http\Resources\Admin\BrowserChartReportResource;
use Modules\BrowserHistory\Http\Resources\Admin\TrafficChartReportResource;

use Illuminate\Http\Request;

use Jiannei\Response\Laravel\Support\Facades\Response;

class BrowserHistoryController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $browserHistory = BrowserHistory::query();

        if ($request->anyFilled(['start_date', 'end_date'])) {
            $browserHistory->where(function ($query) use ($request) {
                if (($start = $request->date('start_date')) !== null) {
                    $query->startOf('created_at', $start->startOfDay());
                }

                if (($end = $request->date('end_date')) !== null) {
                    $query->endOf('created_at', $end->endOfDay());
                }
            });
        }

        $data = $browserHistory->get();

        return Response::success(BrowserHistoryResource::collection($data));
    }

    //取得設備佔比
    public function deviceTypeChartReport(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $browserHistory = BrowserHistory::query();

        if ($request->anyFilled(['start_date', 'end_date'])) {
            $browserHistory->where(function ($query) use ($request) {
                if (($start = $request->date('start_date')) !== null) {
                    $query->startOf('created_at', $start->startOfDay());
                }

                if (($end = $request->date('end_date')) !== null) {
                    $query->endOf('created_at', $end->endOfDay());
                }
            });
        }

        $data = $browserHistory->get();

        $deviceTypes = collect($data)->groupBy(function ($browserHistory) {
            return $browserHistory->device_type;
        })->map(function ($browserHistories, $key) {
            return [
                'device_type' => $key,
                'count' => $browserHistories->count(),
            ];
        })->values();

        return Response::success(DeviceTypeChartReportResource::collection($deviceTypes));
    }

    //取得瀏覽器佔比
    public function browserChartReport(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $browserHistory = BrowserHistory::query();

        if ($request->anyFilled(['start_date', 'end_date'])) {
            $browserHistory->where(function ($query) use ($request) {
                if (($start = $request->date('start_date')) !== null) {
                    $query->startOf('created_at', $start->startOfDay());
                }

                if (($end = $request->date('end_date')) !== null) {
                    $query->endOf('created_at', $end->endOfDay());
                }
            });
        }

        $data = $browserHistory->get();

        $browsers = collect($data)->groupBy(function ($browserHistory) {
            return $browserHistory->browser;
        })->map(function ($browserHistories, $key) {
            return [
                'browser' => $key,
                'count' => $browserHistories->count(),
            ];
        })->values();

        return Response::success(BrowserChartReportResource::collection($browsers));
    }

    //取得流量數據
    public function trafficChartReport(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
        ], [], [
            'start_date' => '開始日期',
            'end_date' => '結束日期',
        ]);

        $browserHistory = BrowserHistory::query();

        if ($request->anyFilled(['start_date', 'end_date'])) {
            $browserHistory->where(function ($query) use ($request) {
                if (($start = $request->date('start_date')) !== null) {
                    $query->startOf('created_at', $start->startOfDay());
                }

                if (($end = $request->date('end_date')) !== null) {
                    $query->endOf('created_at', $end->endOfDay());
                }
            });
        }

        $data = $browserHistory->get();

        $traffics = collect($data)->groupBy(function ($browserHistory) {
            return getDateTime($browserHistory->created_at, 'Y/m/d');
        })->map(function ($browserHistories, $key) {
            return [
                'date' => $key,
                'traffic_count' => $browserHistories->count(),
                'ip_count' => $browserHistories->unique(['sourceip'])->count(),
            ];
        })->values();

        return Response::success(TrafficChartReportResource::collection($traffics));
    }
}
