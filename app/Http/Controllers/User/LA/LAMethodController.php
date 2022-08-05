<?php

namespace App\Http\Controllers\User\LA;

use App\Http\Controllers\Controller;
use App\Models\LA\LAMethod;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class LAMethodController extends Controller
{
    /**
     * Show the application Method Index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $is_trash = $request->is_trash;
        if ($is_trash != 1) {
            $is_trash = 0;
        }

        $trashCount = LAMethod::onlyTrashed()->count();

        return view('user.la.method', compact(['trashCount', 'is_trash']));
    }

    /**
     * Show Data the application Method Index.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $is_trash = $request->is_trash;
        if ($is_trash != 1) {
            $is_trash = 0;
        }

        $get_data = LAMethod::select('id', 'name', 'order', 'created_by', 'updated_by')
            ->with(['createdBy:id,name', 'updatedBy:id,name'])
            ->withCount('activity');

        if ($is_trash == 1) {
            $get_data->onlyTrashed();
        }

        return DataTables::of($get_data)
            ->addIndexColumn()
            ->editcolumn('activity_count', function ($selected) {
                $html = $selected->activity_count;

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
                    // Add Btn Edit
                    $params_edit = [
                        'id' => $selected->id,
                        'name' => htmlentities($selected->name, ENT_QUOTES),
                        'order' => $selected->order,
                        'url' => route('user.method.update', ['method' => $selected->id]),
                    ];

                    $html .= '<button class="btn btn-sm btn-info text-white btnEdit" onclick=\'updateData(' . json_encode($params_edit) . ')\'><i class="fa fa-edit"></i></button> ';

                    // Add Btn Delete
                    $html .= '<button class="btn btn-sm btn-danger btnDelete" data-url="' . route('user.method.destroy', ['method' => $selected->id]) . '" data-id="' . $selected->id . '"><i class="fa fa-remove"></i></button> ';
                } else {
                    // Add Btn Delete Permanent
                    $html .= '<button class="btn btn-sm btn-danger btnDeletePermanent" data-url="' . route('user.method.trash.delete-permanent', ['method' => $selected->id]) . '" data-id="' . $selected->id . '"><i class="fa fa-trash"></i> Delete Permanent</button> ';

                    // Add Btn Restore
                    $html .= '<button class="btn btn-sm btn-primary btnRestore" data-url="' . route('user.method.trash.restore', ['method' => $selected->id]) . '" data-id="' . $selected->id . '"><i class="fa fa-recycle"></i> Restore</button> ';
                }

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get Data for select2.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataSelect(Request $request)
    {
        $search = '';
        if ($request->has('search') && $request->search != "") {
            $search = $request->get('search');
        }

        $get_data = LAMethod::select('id', 'name', 'order', 'created_by', 'updated_by')
            ->withCount('activity');

        if ($search != "") {
            $get_data = $get_data->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $get_data = $get_data->orderBy('order', 'ASC')
            ->limit(100)
            ->get();

        $response = [];

        foreach ($get_data as $row) {
            array_push($response, [
                'id'         => $row->id,
                'text'       => $row->name . ' (' . $row->activity_count . ')'
            ]);
        }

        return response()->json([
            'result' => 'success',
            'title' => 'Success get data method',
            'data' => $response
        ], 200);
    }

    /**
     * Store.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $object_LAMethod = new LAMethod();

            // Get Rules
            $rules = $object_LAMethod->getDBRules();

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

            $save = LAMethod::create([
                'name' => $request->name,
                'order' => $request->order,
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
            $this->saveLogErrors($request, 'LAMethodController.store', $th->getMessage());

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
            $LAMethod = LAMethod::find($id);
            if (!$LAMethod) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Method Data not found',
                ], 404);
            }

            $object_LAMethod = new LAMethod();

            // Get Rules
            $rules = $object_LAMethod->getDBRules($id);

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

            $save = $LAMethod->update([
                'name'          => $request->name,
                'order'          => $request->order,
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
            $this->saveLogErrors($request, 'LAMethodController.update', $th->getMessage());

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
            $LAMethod = LAMethod::find($id);
            if (!$LAMethod) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Method Data not found',
                ], 404);
            }

            \DB::beginTransaction();

            $deleted = $LAMethod->delete();

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
            $this->saveLogErrors($request, 'LAMethodController.destroy', $th->getMessage());

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
            $id = $request->method;

            // Check Data
            $LAMethod = LAMethod::onlyTrashed()->find($id);
            if (!$LAMethod) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Method Data not found',
                ], 404);
            }

            \DB::beginTransaction();

            $restored = $LAMethod->restore();

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
            $this->saveLogErrors($request, 'LAMethodController.restore', $th->getMessage());

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
            $id = $request->method;

            // Check Data
            $LAMethod = LAMethod::onlyTrashed()->find($id);
            if (!$LAMethod) {
                return response()->json([
                    'result'    => 'error',
                    'title'     => 'Method Data not found',
                ], 404);
            }

            \DB::beginTransaction();

            $deleted = $LAMethod->forceDelete();

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
            $this->saveLogErrors($request, 'LAMethodController.deletePermanent', $th->getMessage());

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
