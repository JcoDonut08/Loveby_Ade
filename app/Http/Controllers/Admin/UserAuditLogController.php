<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserAuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAuditLogController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->string('search')->toString();
        $module = $request->string('module')->toString();
        $status = $request->string('status')->toString();
        $pageSize = (int) $request->integer('page_size', 5);
        $pageSize = in_array($pageSize, [5, 10, 20], true) ? $pageSize : 5;

        $logs = UserAuditLog::query()
            ->with('user')
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('user_name', 'like', '%'.$search.'%')
                        ->orWhere('user_email', 'like', '%'.$search.'%')
                        ->orWhere('activity', 'like', '%'.$search.'%')
                        ->orWhere('module', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('status', 'like', '%'.$search.'%');
                });
            })
            ->when($module !== '', fn ($query) => $query->where('module', $module))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate($pageSize)
            ->withQueryString();

        return view('pages.admin.audit-logs', [
            'logs' => $logs,
            'modules' => UserAuditLog::query()->select('module')->distinct()->orderBy('module')->pluck('module'),
            'statuses' => UserAuditLog::query()->select('status')->distinct()->orderBy('status')->pluck('status'),
        ]);
    }
}
