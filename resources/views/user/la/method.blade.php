@php
$listNavigation = [['label' => '<i class="fa fa-home"></i> Home', 'url' => route('home')], ['label' => '<i class="fa fa-dashboard"></i> Dashboard', 'url' => route('user.dashboard')], ['label' => '<i class="fa fa-hashtag"></i> Method List', 'url' => route('user.method.index')]];

if ($is_trash == 1) {
    array_push($listNavigation, ['label' => '<i class="fa fa-trash"></i> Trash', 'url' => route('user.method.index', ['is_trash' => 1])]);
}
@endphp
@extends('layouts.user.index')
@section('titlePage', 'Method List .:.')
@section('content')

    <div class="container-fluid">

        @include('layouts.user.components.breadcrumb-navigation', $listNavigation)

        <div class="card">
            <div class="card-header">
                {{ __('Method List') }}
                @if ($is_trash == 1)
                    <span class="badge rounded-pill bg-danger">Trash</span>
                @endif
                @if ($is_trash != 1)
                    <div class="float-end">
                        <button class="btn btn-primary btn-sm" onclick="addData();">
                            <i class="fa fa-plus"></i>
                            Add
                        </button>
                        <a href="{{ route('user.method.index', ['is_trash' => 1]) }}" class="btn btn-danger btn-sm"
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

                <div class="wrapper-table">
                    <table id="tableData" class="table">
                        @csrf
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-cogs"></i> Action</th>
                                <th>Name</th>
                                <th>Order</th>
                                <th>Activity Count</th>
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
                            <label>Order *</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group has-validation">
                                {!! Form::input('number', 'order', old('order'), [
                                    'class' => 'form-control',
                                    'min' => 1,
                                    'max' => 255,
                                    'aria-describedby' => 'validation-order',
                                
                                    'required',
                                ]) !!}
                                <div id="validation-order" class="invalid-feedback"></div>
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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'order',
                    name: 'order'
                },
                {
                    data: 'activity_count',
                    name: 'activity_count',
                    searchable: false
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
                    url: "{{ route('user.method.index.get-data') }}",
                    data: function(data) {
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
                }]
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


        function addData() {
            clearValidationData();

            $('#modal-management-data').modal('show');
            $('#modal-title-management-data').html('Add New Data');
            $('#form-management-data').attr('action', "{{ route('user.method.store') }}");

            $('[name="_method"]').val('POST');
            $('[name="id"]').val('');
            $('[name="name"]').val('');
            $('[name="order"]').val('');
        }

        function updateData(params) {
            const {
                id,
                name,
                order,
                url
            } = params;


            clearValidationData();

            $('[name="_method"]').val('PUT');

            $('#modal-management-data').modal('show');
            $('#modal-title-management-data').html('Update <strong>`' + name + '`</strong>');
            $('#form-management-data').attr('action', url);

            $('[name="id"]').val(id);
            $('[name="name"]').val(name);
            $('[name="order"]').val(order);
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
@endsection
