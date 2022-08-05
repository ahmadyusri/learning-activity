@php
$listNavigation = [['label' => '<i class="fa fa-home"></i> Home', 'url' => route('home')], ['label' => '<i class="fa fa-dashboard"></i> Dashboard', 'url' => route('user.dashboard')], ['label' => '<i class="fa fa-hashtag"></i> Activity List', 'url' => route('user.activity.index')]];

if ($is_trash == 1) {
    array_push($listNavigation, ['label' => '<i class="fa fa-trash"></i> Trash', 'url' => route('user.activity.index', ['is_trash' => 1])]);
}
@endphp
@extends('layouts.user.index')
@section('titlePage', 'Activity List .:.')
@section('content')

    <div class="container-fluid">

        @include('layouts.user.components.breadcrumb-navigation', $listNavigation)

        <div class="card d-print-none">
            <div class="card-header">
                {{ __('Activity List') }}
                @if ($is_trash == 1)
                    <span class="badge rounded-pill bg-danger">Trash</span>
                @endif
                @if ($is_trash != 1)
                    <div class="float-end">
                        <button class="btn btn-primary btn-sm" onclick="addData();">
                            <i class="fa fa-plus"></i>
                            Add
                        </button>
                        <a href="{{ route('user.activity.index', ['is_trash' => 1]) }}" class="btn btn-danger btn-sm"
                            id="trashBtn">
                            <i class="fa fa-trash"></i>
                            Show Trash
                            (<span id="trashInfo" class="small">{{ $trashCount }}</span>)
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @include('layouts.user.components.status-alert')

                <div class="row row-filter">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-6">
                                <div style="margin-bottom: 10px" class="input-group">
                                    <label class="input-group-text"><small>Start Date</small></label>
                                    <input class="form-control" type="date" name="filter_start_date"
                                        value="{{ date('Y') . '-01-01' }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div style="margin-bottom: 10px" class="input-group">
                                    <label class="input-group-text"><small>End Date</small></label>
                                    <input class="form-control" type="date" name="filter_end_date"
                                        value="{{ date('Y') . '-12-31' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <button type="text" id="btnFiterSubmitSearch" class="btn btn-sm btn-success">Filter</button>
                        <button type="text" id="btnClearFiter" class="btn btn-sm btn-reddit">Clear Filter</button>
                    </div>
                </div>

                <div class="wrapper-table">
                    <table id="tableData" class="table">
                        @csrf
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-cogs"></i> Action</th>
                                <th>Method</th>
                                <th>Activity</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr />
        <div id="tableView"></div>

    </div>


    <!-- modal manajemen Data -->
    <div class="modal modal-default fade" id="modal-management-data" ng-app>
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open([
                    'id' => 'form-management-data',
                    'class' => 'form-horizontal',
                ]) !!}

                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modal-title-management-data">Add New Data</h4>
                </div>

                <div class="modal-body">
                    <input name="id" type="hidden">
                    <input name="_method" type="hidden">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Method *</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group has-validation">
                                {!! Form::select('method_id', [], old('method_id'), [
                                    'class' => 'form-control',
                                    'style' => 'width: 100%',
                                    'aria-describedby' => 'validation-method_id',
                                    'required',
                                ]) !!}
                                <div id="validation-method_id" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Name *</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group has-validation">
                                {!! Form::input('text', 'name', old('name'), [
                                    'class' => 'form-control',
                                    'minlength' => 2,
                                    'maxlength' => 100,
                                    'aria-describedby' => 'validation-name',
                                    'required',
                                ]) !!}
                                <div id="validation-name" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Start Date *</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group has-validation">
                                {!! Form::input('date', 'start_date', old('start_date'), [
                                    'class' => 'form-control',
                                    'aria-describedby' => 'validation-start_date',
                                    'required',
                                ]) !!}
                                <div id="validation-start_date" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>End Date *</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group has-validation">
                                {!! Form::input('date', 'end_date', old('end_date'), [
                                    'class' => 'form-control',
                                    'aria-describedby' => 'validation-end_date',
                                    'required',
                                ]) !!}
                                <div id="validation-end_date" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fa fa-remove"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Save
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@endsection

@section('scripts')
    <!-- DataTables -->
    <script defer src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/frameworks/select2/js/select2.min.js') }}"></script>

    <script>
        // Define variable table data
        var table;

        var trashCount = '{{ $trashCount }}';
        var is_trash = '{{ $is_trash }}';

        $(document).ready(function() {
            if (trashCount > 0) {
                $('#trashBtn').show();
            } else {
                $('#trashBtn').hide();
            }

            if (window.location.hash) {
                if (window.location.hash == '#show-modal-create') {
                    addData();
                    try {
                        var uri = window.location.toString();
                        if (uri.indexOf("#") > 0) {
                            var clean_uri = uri.substring(0,
                                uri.indexOf("#"));

                            window.history.replaceState({},
                                document.title, clean_uri);
                        }
                    } catch (error) {}
                }
            }

            if (is_trash != 1) {
                getMethodData();

                loadTableView();
            }

            const columnTableData = [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'method_id',
                    name: 'method_id',
                    orderable: false,
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                },
                {
                    data: 'created_by',
                    name: 'createdBy.name',
                    orderable: false,
                },
                {
                    data: 'updated_by',
                    name: 'updatedBy.name',
                    orderable: false,
                },
            ];

            // Init Datatable
            table = $('#tableData').DataTable({
                dom: 'Bfrtip',
                stateSave: true,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.activity.index.get-data') }}",
                    data: function(data) {
                        data.filter_start_date = $('[name=filter_start_date]').val();
                        data.filter_end_date = $('[name=filter_end_date]').val();
                        data.is_trash = is_trash;
                    }
                },
                columns: columnTableData,
                'columnDefs': [{
                    'targets': 1,
                    'createdCell': function(td, cellData, rowData, row, col) {
                        $(td).attr('align', 'center');
                        $(td).attr('nowrap', true);
                    }
                }],
            });

            // Datatable search
            $('#btnFiterSubmitSearch').click(function(e) {
                e.preventDefault();
                $('#tableData').DataTable().draw(true);
                if (is_trash != 1) {
                    loadTableView();
                }
            });

            // Datatable clear search
            $('#btnClearFiter').click(function(e) {
                e.preventDefault();
                $('[name=filter_start_date]').val('');
                $('[name=filter_end_date]').val('');
                $('#tableData').DataTable().draw(true);
                if (is_trash != 1) {
                    loadTableView();
                }
            });

            // Submit Form
            $('#form-management-data').on('submit', function(e_form) {
                // Clear Validation Data
                clearValidationData();

                e_form.preventDefault();

                $('.bgLoading').fadeIn('fast', function() {
                    let formData = new FormData($("#form-management-data")[0]);
                    let urlData = $('#form-management-data').attr('action');

                    $.ajax({
                        url: urlData,
                        data: formData,
                        async: false,
                        processData: false,
                        contentType: false,
                        type: 'post',
                        dataType: 'json',
                        success: function(response) {
                            if (response.result == "success") {
                                alertify.success(response.title);
                                table.ajax.reload(null, false);
                                if (is_trash != 1) {
                                    loadTableView();
                                }
                                $('#modal-management-data').modal('hide');

                                $('[name="id"]').val('');
                                $('[name="name"]').val('');
                            } else {
                                alertify.error(response.title);

                                const validationData = response?.data?.validation;
                                // Check Error Validation
                                if (validationData) {
                                    if (typeof validationData === 'object') {
                                        processValidatingData(validationData);
                                    }
                                }
                            }

                            $('.bgLoading').fadeOut();
                        },
                        error: function(error) {
                            var errorTitle = error?.statusText ??
                                "Error processing data";
                            if (typeof error?.responseJSON?.title === 'string') {
                                errorTitle = error?.responseJSON?.title;
                            }

                            const validationData = error?.responseJSON?.data
                                ?.validation;
                            // Check Error Validation
                            if (validationData) {
                                if (typeof validationData === 'object') {
                                    processValidatingData(validationData);
                                }
                            }

                            alertify.error(errorTitle);
                            $('.bgLoading').fadeOut();
                        },
                    });

                });

            });

            // deleting data
            $('#tableData').on('click', '.btnDelete[data-url]', function(e) {
                e.preventDefault();

                var url = $(this).data('url');

                alertify.confirm(
                    "Are you sure want to delete this data? Data will be entered into Trash",
                    function() {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            data: {
                                _token: csrfToken
                            },
                            beforeSend: function() {
                                $('.bgLoading').fadeIn();
                            },
                            success: function(response) {
                                if (response.result == 'success') {
                                    alertify.success(response.title);
                                    table.ajax.reload(null, false);
                                    if (is_trash != 1) {
                                        loadTableView();
                                    }

                                    try {
                                        var trashInfo = $('#trashInfo').text();
                                        $('#trashInfo').text(parseInt(trashInfo) + 1);
                                    } catch (error) {}

                                    $('#trashBtn').show();
                                } else {
                                    alertify.error(response.title);
                                }
                                $('.bgLoading').fadeOut();
                            },
                            error: function(error) {
                                var errorTitle = error?.statusText ??
                                    "Error when deleting data";
                                if (typeof error?.responseJSON?.title === 'string') {
                                    errorTitle = error?.responseJSON?.title;
                                }

                                alertify.error(errorTitle);
                                $('.bgLoading').fadeOut();
                            }
                        });
                    });
            });

            // restoring data
            $('#tableData').on('click', '.btnRestore[data-url]', function(e) {
                e.preventDefault();

                var url = $(this).data('url');

                alertify.confirm(
                    "Are you sure want to restore this data?",
                    function() {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                _token: csrfToken
                            },
                            beforeSend: function() {
                                $('.bgLoading').fadeIn();
                            },
                            success: function(response) {
                                if (response.result == 'success') {
                                    alertify.success(response.title);
                                    table.ajax.reload(null, false);
                                    if (is_trash != 1) {
                                        loadTableView();
                                    }
                                } else {
                                    alertify.error(response.title);
                                }
                                $('.bgLoading').fadeOut();
                            },
                            error: function(error) {
                                var errorTitle = error?.statusText ??
                                    "Error when restoring data";
                                if (typeof error?.responseJSON?.title === 'string') {
                                    errorTitle = error?.responseJSON?.title;
                                }

                                alertify.error(errorTitle);
                                $('.bgLoading').fadeOut();
                            }
                        });
                    });
            });

            // delete permanent data
            $('#tableData').on('click', '.btnDeletePermanent[data-url]', function(e) {
                e.preventDefault();

                var url = $(this).data('url');

                alertify.confirm(
                    "Are you sure want to Delete Permanent this data?",
                    function() {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'json',
                            data: {
                                _token: csrfToken
                            },
                            beforeSend: function() {
                                $('.bgLoading').fadeIn();
                            },
                            success: function(response) {
                                if (response.result == 'success') {
                                    alertify.success(response.title);
                                    table.ajax.reload(null, false);
                                    if (is_trash != 1) {
                                        loadTableView();
                                    }
                                } else {
                                    alertify.error(response.title);
                                }
                                $('.bgLoading').fadeOut();
                            },
                            error: function(error) {
                                var errorTitle = error?.statusText ??
                                    "Error when Delete Permanent data";
                                if (typeof error?.responseJSON?.title === 'string') {
                                    errorTitle = error?.responseJSON?.title;
                                }

                                alertify.error(errorTitle);
                                $('.bgLoading').fadeOut();
                            }
                        });
                    });
            });

        });

        function getMethodData() {
            $('[name="method_id"]').select2({
                placeholder: "Select Method",
                dropdownParent: $("#modal-management-data"),
                multiple: false,
                ajax: {
                    url: "{{ route('user.method.select.get-data') }}",
                    type: "POST",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function(params) {
                        return {
                            _token: csrfToken,
                            search: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data
                        };
                    },
                },
            });
        }

        function addData() {
            clearValidationData();

            $('#modal-management-data').modal('show');
            $('#modal-title-management-data').html('Add New Data');
            $('#form-management-data').attr('action', "{{ route('user.activity.store') }}");

            $('[name="_method"]').val('POST');
            $('[name="id"]').val('');
            $('[name="method_id"]').val('');
            $('[name="method_id"]').trigger("change");
            $('[name="name"]').val('');
            $('[name="start_date"]').val('');
            $('[name="end_date"]').val('');
        }

        function updateData(params) {
            const {
                id,
                method_id,
                method_name,
                name,
                start_date,
                end_date,
                url
            } = params;
            clearValidationData();

            $('[name="_method"]').val('PUT');

            $('#modal-management-data').modal('show');
            $('#modal-title-management-data').html('Update <strong>`' + name + '`</strong>');
            $('#form-management-data').attr('action', url);

            $('[name="id"]').val(id);
            $('[name="method_id"]').html('<option value="'+ method_id +'" selected="selected">'+ method_name +'</option>');
            $('[name="name"]').val(name);
            $('[name="start_date"]').val(start_date);
            $('[name="end_date"]').val(end_date);
        }

        function loadTableView() {
            $.ajax({
                url: "{{ route('user.activity.index.get-table-view') }}",
                data: {
                    filter_start_date: $('[name=filter_start_date]').val(),
                    filter_end_date: $('[name=filter_end_date]').val(),
                },
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#tableView').html(
                        '<div class="d-flex justify-content-center"><i class="fas fa-spinner fa-pulse"></i></div>'
                    );
                },
                success: function(response) {
                    if (response.result == 'success') {
                        $('#tableView').html(response.data);
                    } else {
                        $('#tableView').html('');
                        alertify.error(response.title);
                    }
                },
                error: function(error) {
                    var errorTitle = error?.statusText ??
                        "Error when load data table view";
                    if (typeof error?.responseJSON?.title === 'string') {
                        errorTitle = error?.responseJSON?.title;
                    }

                    alertify.error(errorTitle);
                    $('#tableView').html('');
                }
            });
        }

        function clearValidationData() {
            $('input.is-invalid').removeClass('is-invalid');
        }

        function processValidatingData(validationData) {
            Object.entries(validationData).map((e,
                index) => {
                var errorTitle = "";
                if (typeof e[1] === 'string') {
                    errorTitle = e[1];
                } else if (typeof e[1] ===
                    'object') {
                    errorTitle = "<ul>";
                    e[1].map((titleRow) => {
                        errorTitle +=
                            "<li>" +
                            titleRow +
                            "</li>";
                    })
                    errorTitle += "</ul>";
                }

                // Set Form Error
                const formInputElement = $(
                    '[name="' + e[0] + '"]');
                if (formInputElement) {
                    formInputElement.addClass(
                        'is-invalid');
                }

                const validationInfoElement = $(
                    '#validation-' + e[0]);
                validationInfoElement.html(
                    errorTitle);
            })
        }
    </script>
@endsection


@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frameworks/select2/css/select2.min.css') }}">
@endsection
