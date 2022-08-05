<?php

namespace App\Http\Controllers\User\LA;

use App\Http\Controllers\Controller;
use App\Models\LA\LAActivity;
use App\Models\LA\LAMethod;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class LAActivityController extends Controller
{
    /**
     * Show the application Activity Index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $is_trash = $request->is_trash;
        if ($is_trash != 1) {
            $is_trash = 0;
        }

        if ($is_trash == 0) {
            // check if none of the methods
            $LAMethod = LAMethod::count();
            if ($LAMethod <= 0) {
                return redirect()->route('user.method.index')->with(['info' => 'Please add the method first']);
            }
        }

        $trashCount = LAActivity::onlyTrashed()->count();

        return view('user.la.activity.index', compact(['trashCount', 'is_trash']));
    }

    /**
     * Show Data the application Activity Index.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $is_trash = $request->is_trash;
        if ($is_trash != 1) {
            $is_trash = 0;
        }

        $get_data = LAActivity::select('id', 'method_id', 'name', 'start_date', 'end_date', 'created_by', 'updated_by');

        if ($is_trash == 1) {
            $get_data->onlyTrashed();
        }

        $filter_start_date   = $request->filter_start_date;
        $filter_end_date = $request->filter_end_date;

        if ($filter_start_date != null && $filter_end_date != null) {
            $get_data = $get_data->whereRaw("date(start_date) >= '" . $filter_start_date . "' AND date(start_date) <= '" . $filter_end_date . "'");
        }

        $get_data = $get_data->with(['method:id,name', 'createdBy:id,name', 'updatedBy:id,name']);

        return DataTables::of($get_data)
            ->addIndexColumn()
            ->editcolumn('method_id', function ($selected) {
                $html = '-';
                if ($selected->method) {
                    $html = $selected->method->name;
                }
                return $html;
            })
            ->addcolumn('status', function ($selected) {
                $html = '-';
                if (date('Y-m-d') > $selected->end_date) {
                    $html = '<span class="badge badge-pill bg-warning">END</span>';
                } elseif (date('Y-m-d') >= $selected->start_date && date('Y-m-d') <= $selected->end_date) {
                    $html = '<span class="badge badge-pill bg-primary">ONGOING</span>';
                } elseif (date('Y-m-d') < $selected->start_date) {
                    $html = '<span class="badge badge-pill bg-info">COMINGSOON</span>';
                }

                return $html;
            })
            ->editcolumn('created_by', function ($selected) {
                $html = '-';
                if ($selected->createdBy) {
                    $html = $selected->createdBy->name;
                }
                return $html;
            })
            ->editcolumn('updated_by', function ($selected) {
                $html = '-';
                if ($selected->updatedBy) {
                    $html = $selected->updatedBy->name;
                }
                return $html;
            })
            ->addColumn('action', function ($selected) use ($is_trash) {
                $html = '';

                if ($is_trash == 0) {
                    $method_name = '';
                    if ($selected->method) {
                        $method_name = $selected->method->name;
                    }
                    // Add Btn Edit
                    $params_edit = [
                        'id' => $selected->id,
                        'method_id' => $selected->method_id,
                        'method_name' => htmlentities($method_name, ENT_QUOTES),
                        'name' => htmlentities($selected->name, ENT_QUOTES),
                        'start_date' => $selected->start_date,
                        'end_date' => $selected->end_date,
                        'url' => route('user.activity.update', ['activity' => $selected->id]),
                    ];

                    $html .= '<button class="btn btn-sm btn-info text-white btnEdit" onclick=\'updateData(' . json_encode($params_edit) . ')\'><i class="fa fa-edit"></i></button> ';

                    // Add Btn Delete
                    $html .= '<button class="btn btn-sm btn-danger btnDelete" data-url="' . route('user.activity.destroy', ['activity' => $selected->id]) . '" data-id="' . $selected->id . '"><i class="fa fa-remove"></i></button> ';
                } else {
                    // Add Btn Delete Permanent
                    $html .= '<button class="btn btn-sm btn-danger btnDeletePermanent" data-url="' . route('user.activity.trash.delete-permanent', ['activity' => $selected->id]) . '" data-id="' . $selected->id . '"><i class="fa fa-trash"></i> Delete Permanent</button> ';

                    // Add Btn Restore
                    $html .= '<button class="btn btn-sm btn-primary btnRestore" data-url="' . route('user.activity.trash.restore', ['activity' => $selected->id]) . '" data-id="' . $selected->id . '"><i class="fa fa-recycle"></i> Restore</button> ';
                }

                return $html;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show Table View the application Activity Index.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTableView(Request $request)
    {
        $learning_activity = [];

        $filter_start_date   = $request->filter_start_date;
        $filter_end_date = $request->filter_end_date;

        $LAMethod = LAMethod::whereHas('activity')
            ->orderBy('order', 'ASC')
            ->with(['activity' => function ($q) use ($filter_start_date, $filter_end_date) {
                $q->select('id', 'method_id', 'name', 'start_date', 'end_date');
                if ($filter_start_date != null && $filter_end_date != null) {
                    $q->whereRaw("date(start_date) >= '" . $filter_start_date . "' AND date(start_date) <= '" . $filter_end_date . "'");
                }
                $q->orderBy('start_date', 'ASC');
            }])
            ->get();

        // Re Structure Data
        foreach ($LAMethod as $item_method) {
            $method_id      = $item_method->id;
            $method_name    = $item_method->name;

            foreach ($item_method->activity as $item_activity) {
                $year   = date('Y', strtotime($item_activity->start_date));
                $month  = intval(date('m', strtotime($item_activity->start_date)));

                if (!array_key_exists($year, $learning_activity)) {
                    $learning_activity[$year] = [
                        "label" => $year,
                        'min' => $month,
                        'max' => $month,
                        "data" => [],
                    ];
                }

                if (!array_key_exists($method_id, $learning_activity[$year]['data'])) {
                    $learning_activity[$year]['data'][$method_id] = [
                        'label' => $method_name,
                        'data' => [],
                    ];
                }

                if ($month > $learning_activity[$year]['max']) {
                    $learning_activity[$year]['max'] = $month;
                }
                if ($month < $learning_activity[$year]['min']) {
                    $learning_activity[$year]['min'] = $month;
                }

                if (!array_key_exists($month, $learning_activity[$year]['data'][$method_id]['data'])) {
                    $learning_activity[$year]['data'][$method_id]['data'][$month] = [];
                }

                $data_row = $item_activity->toArray();

                unset($data_row['method_id']);

                array_push(
                    $learning_activity[$year]['data'][$method_id]['data'][$month],
                    $data_row
                );
            }
        }

        $view = view('user.la.activity.table', compact(['learning_activity']))->render();

        return response()->json(['result' => 'success', 'title' => 'Table Rendered', 'data' => $view], 200);
    }

    /**
     * Store.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $object_LAActivity = new LAActivity();

            // Get Rules
            $rules = $object_LAActivity->getDBRules(null, $request->method_id);

            // Validate Data
            $Validator = Validator::make($request->all(), $rules);
            if ($Validator->fails()) {
                $title_error_validate = '';
                foreach ($Validator->errors()->toArray() as $data) {
                    if (is_array($data)) {
                        foreach ($data as $child_data) {
                            if (!is_array($child_data)) {
                                $title_error_validate .= $child_data . "<br>";
                            }
                        }
                    } else {
                        $title_error_validate .= $data . "<br>";
                    }
                }

                return response()->json([
                    'result'    => 'error',
                    'title'     => $title_error_validate,
                    'data'      => [
                        'validation' => $Validator->errors()->toArray()
                    ],
                ], 422);
            }

            \DB::beginTransaction();

            $save = LAActivity::create([
                'method_id' => $request->method_id,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => auth()->id(),
            ]);

            if (!$save) {
                \DB::rollback();

                return response()->json([
                    'result' => 'error',
                    'title' => 'Data failed to stored',
                ], 200);
            }

            \DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Data Stored',
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollback();

            // Save Logs
            $this->saveLogErrors($request, 'LAActivityController.store', $th->getMessage());

            $limit_error_message = $th->getMessage();
            if (strlen($limit_error_message) > 100) {
                $limit_error_message = substr($th->getMessage(), 0, 100) . '...';
            }

            return response()->json([
                'result'    => 'error',
                'title'     => 'There is something wrong!. ' . $limit_error_message
            ], 500);
        }
    }

    /**
     * Update.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $id = $request->id;

            // Check Data
            $LAActivity = LAActivity::find($id);
            if (!$LAActivity) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Activity Data not found',
                ], 404);
            }

            $object_LAActivity = new LAActivity();

            // Get Rules
            $rules = $object_LAActivity->getDBRules($id, $request->method_id);

            // Validate Data
            $Validator = Validator::make($request->all(), $rules);
            if ($Validator->fails()) {
                $title_error_validate = '';
                foreach ($Validator->errors()->toArray() as $data) {
                    if (is_array($data)) {
                        foreach ($data as $child_data) {
                            if (!is_array($child_data)) {
                                $title_error_validate .= $child_data . "<br>";
                            }
                        }
                    } else {
                        $title_error_validate .= $data . "<br>";
                    }
                }

                return response()->json([
                    'result'    => 'error',
                    'title'     => $title_error_validate,
                    'data'      => [
                        'validation' => $Validator->errors()->toArray()
                    ],
                ], 422);
            }

            \DB::beginTransaction();

            $save = $LAActivity->update([
                'method_id'     => $request->method_id,
                'name'          => $request->name,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'updated_by'    => auth()->id(),
            ]);

            if (!$save) {
                \DB::rollback();

                return response()->json([
                    'result' => 'error',
                    'title' => 'Data failed to updated',
                ], 200);
            }

            \DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Data Updated',
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollback();

            // Save Logs
            $this->saveLogErrors($request, 'LAActivityController.update', $th->getMessage());

            $limit_error_message = $th->getMessage();
            if (strlen($limit_error_message) > 100) {
                $limit_error_message = substr($th->getMessage(), 0, 100) . '...';
            }

            return response()->json([
                'result'    => 'error',
                'title'     => 'There is something wrong!. ' . $limit_error_message
            ], 500);
        }
    }

    /**
     * Destroy.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Check Data
            $LAActivity = LAActivity::find($id);
            if (!$LAActivity) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Activity Data not found',
                ], 404);
            }

            \DB::beginTransaction();

            $deleted = $LAActivity->delete();

            if (!$deleted) {
                \DB::rollback();

                return response()->json([
                    'result' => 'error',
                    'title' => 'Data failed to deleted',
                ], 200);
            }

            \DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Data deleted',
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollback();

            // Save Logs
            $this->saveLogErrors($request, 'LAActivityController.destroy', $th->getMessage());

            $limit_error_message = $th->getMessage();
            if (strlen($limit_error_message) > 100) {
                $limit_error_message = substr($th->getMessage(), 0, 100) . '...';
            }

            return response()->json([
                'result'    => 'error',
                'title'     => 'There is something wrong!. ' . $limit_error_message
            ], 500);
        }
    }

    /**
     * Restore.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        try {
            $id = $request->activity;

            // Check Data
            $LAActivity = LAActivity::onlyTrashed()->find($id);
            if (!$LAActivity) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Activity Data not found',
                ], 404);
            }

            \DB::beginTransaction();

            $restored = $LAActivity->restore();

            if (!$restored) {
                \DB::rollback();

                return response()->json([
                    'result' => 'error',
                    'title' => 'Data failed to restored',
                ], 200);
            }

            \DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Data restored',
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollback();

            // Save Logs
            $this->saveLogErrors($request, 'LAActivityController.restore', $th->getMessage());

            $limit_error_message = $th->getMessage();
            if (strlen($limit_error_message) > 100) {
                $limit_error_message = substr($th->getMessage(), 0, 100) . '...';
            }

            return response()->json([
                'result'    => 'error',
                'title'     => 'There is something wrong!. ' . $limit_error_message
            ], 500);
        }
    }

    /**
     * Delete Permanent.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePermanent(Request $request)
    {
        try {
            $id = $request->activity;

            // Check Data
            $LAActivity = LAActivity::onlyTrashed()->find($id);
            if (!$LAActivity) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Activity Data not found',
                ], 404);
            }

            \DB::beginTransaction();

            $deleted = $LAActivity->forceDelete();

            if (!$deleted) {
                \DB::rollback();

                return response()->json([
                    'result' => 'error',
                    'title' => 'Data failed to deleted',
                ], 200);
            }

            \DB::commit();

            return response()->json([
                'result' => 'success',
                'title' => 'Data deleted',
            ], 200);
        } catch (\Throwable $th) {
            \DB::rollback();

            // Save Logs
            $this->saveLogErrors($request, 'LAActivityController.deletePermanent', $th->getMessage());

            $limit_error_message = $th->getMessage();
            if (strlen($limit_error_message) > 100) {
                $limit_error_message = substr($th->getMessage(), 0, 100) . '...';
            }

            return response()->json([
                'result'    => 'error',
                'title'     => 'There is something wrong!. ' . $limit_error_message
            ], 500);
        }
    }
}
