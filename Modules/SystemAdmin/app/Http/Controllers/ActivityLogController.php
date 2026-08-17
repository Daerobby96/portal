<?php

namespace Modules\SystemAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'ilike', "%{$search}%")
                  ->orWhere('action', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'ilike', "%{$search}%")
                                ->orWhere('email', 'ilike', "%{$search}%");
                  });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(25)->withQueryString();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        $actions = ActivityLog::select('action')
            ->distinct()
            ->whereNotNull('action')
            ->pluck('action');

        return Inertia::render('SystemAdmin/ActivityLog/Index', [
            'logs'    => $logs,
            'users'   => $users,
            'actions' => $actions,
            'filters' => [
                'search'  => $request->search ?? '',
                'user_id' => $request->user_id ?? '',
                'action'  => $request->action ?? '',
            ],
        ]);
    }
}
