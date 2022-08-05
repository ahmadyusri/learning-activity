@extends('layouts.user.index')
@section('titlePage', 'Dashboard .:.')
@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">{{ __('Dashboard') }}</div>

            <div class="card-body">
                @include('layouts.user.components.status-alert')

                <p>Welcome <strong>{{ auth()->user()->name }}</strong></p>
                <p>
                    <a href="{{ route('user.activity.index') }}" class="btn btn-success">
                        List Activity
                    </a>
                    <a href="{{ route('user.activity.index') }}#show-modal-create" class="btn btn-primary">
                        Create Activity
                    </a>
                </p>


                @if ($ListActivity['ongoing']->count() > 0)
                    <div class="wrapper-table">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Method
                                    </th>
                                    <th>
                                        Activity
                                    </th>
                                    <th>
                                        Start Date
                                    </th>
                                    <th>
                                        End Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ListActivity['ongoing'] as $item)
                                    <tr>
                                        <td>
                                            {{ $item->method->name }}
                                        </td>
                                        <td>
                                            {{ $item->name }}
                                            <span class="badge badge-pill bg-primary">ONGOING</span>
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($item->start_date)) }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($item->end_date)) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
