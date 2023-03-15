<?php

namespace App\Modules\EmailLog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Telescope\Storage\EntryQueryOptions;
use Laravel\Telescope\Contracts\EntriesRepository;

class EmailLogController extends Controller
{
    public function index(Request $request, EntriesRepository $storage)
    {
        $perPage = $request->per_page ?? 10;

        $take = $request->take ?? 100;

        $request->merge([
            'take' => $take,
        ]);

        $entries = $storage->get(
                'mail',
                EntryQueryOptions::fromRequest($request)
        );

        if($perPage == 'all'){
            $perPage = $entries->count();
        }

        $entries = self::paginate($entries, $perPage);

        return view('email_log::index', [
            'entries' => $entries,
            'per_page' => $request->per_page,
            'take' => $take
        ]);
    }

    public function show(EntriesRepository $storage, $id)
    {
        $entry = $storage->find($id);
        return view('email_log::show', [
            'entry' => $entry
        ]);
    }

    public static function paginate(Collection $results, $pageSize)
    {
        $page = Paginator::resolveCurrentPage('page');

        $total = $results->count();

        return self::paginator($results->forPage($page, $pageSize), $total, $pageSize, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
