<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ActivityLogController extends AppBaseController
{
    /**
     * @param  Request  $request
     * @return Application|Factory|JsonResponse|View
     *
     * @throws Throwable
     */
    public function index(Request $request)
    {
        $activityLogs = ActivityLog::with('createdBy')->orderBy('created_at', 'DESC')->paginate(10);

        if ($request->ajax()) {
            $startDate = $request->get('startDate');
            $endDate = $request->get('endDate');

            if (! empty($startDate) && ! empty($endDate)) {
                $activityLogs = ActivityLog::whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)->get();

                $html = view('activity_logs.activity_log_lists', compact('activityLogs'))->render();

                return response()->json(['html' => $html]);
            }
        }

        if ($request->ajax()) {
            try {
                return $this->sendResponse($activityLogs, 'Activity log data retrieved successfully.');
            } catch (\Exception $e) {
                return $this->sendError($e, '404');
            }
        }

        return view('activity_logs.index', compact('activityLogs'));
    }
}
