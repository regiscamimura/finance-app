<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\BalanceEntry;

use Illuminate\Support\Facades\DB;

class BalanceEntryController extends Controller
{
    public function index(Request $request)
    {
        $result = BalanceEntry::all()->sortByDesc('date')->values()->all();
        if ($request->input('grouped_by_date')) {
            $entries = [];
            foreach ($result as $row) {
                $date = date("n/j/Y", strtotime($row->date));
                $entries[$date][] = $row;
            }

            $result = $entries;
        }

        return $result;
    }

    public function show($id)
    {
        return BalanceEntry::find($id);
    }

    public function store(Request $request)
    {
        return BalanceEntry::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $rec = BalanceEntry::findOrFail($id);
        $rec->update($request->all());

        return $rec;
    }

    public function delete(Request $request, $id)
    {
        $rec = BalanceEntry::findOrFail($id);
        $rec->delete();

        return 204;
    }

    public function total(Request $request) {

        $query = DB::table('balance_entries');
        if ($request->input('date')) $query->whereRaw('date <= ?', [$request->input('date')]);

        return ['total'=>$query->sum('amount')];

    }
}
