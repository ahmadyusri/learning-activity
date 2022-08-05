@if (count($learning_activity) > 0)
    <div class="card">
        <div class="card-body">
            <div class="wrapper-table">

                <table id="tableView" class="table table-striped">
                    <tbody>
                        <tr>
                            @foreach ($learning_activity as $item_year)
                                <td>
                                    <table id="tableView"
                                        class="table table-striped table-bordered border-dark table-responsive">
                                        @if (count($learning_activity) > 1)
                                            <thead class="table-dark">
                                                <tr>
                                                    <th colspan="{{ $item_year['max'] - $item_year['min'] + 2 }}"
                                                        class="text-center">
                                                        <h3>{{ $item_year['label'] }}</h3>
                                                    </th>
                                                </tr>
                                            </thead>
                                        @endif
                                        <thead>
                                            <tr>
                                                <td class="text-center" style="min-width: 150px">
                                                    <h5>Method</h5>
                                                </td>
                                                @for ($i = $item_year['min']; $i <= $item_year['max']; $i++)
                                                    <td class="text-center" style="min-width: 100px">
                                                        <h5>
                                                            {{ changeNumberToMonth($i) }}
                                                        </h5>
                                                    </td>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item_year['data'] as $index => $item_2)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $item_2['label'] }}
                                                    </td>
                                                    @for ($i = $item_year['min']; $i <= $item_year['max']; $i++)
                                                        <td>
                                                            @if (array_key_exists($i, $item_2['data']))
                                                                @if (count($item_2['data'][$i]) == 1)
                                                                    <p class="text-center"
                                                                        style="line-height: 1.1;margin-bottom: 7px;">
                                                                        {{ $item_2['data'][$i][0]['name'] }}
                                                                        <br />
                                                                    </p>
                                                                    <p class="text-primary text-center fw-bold"
                                                                        style="line-height: 0.85;">
                                                                        <small>
                                                                            <span class="text-nowrap">
                                                                                ({{ date('d/m/Y', strtotime($item_2['data'][$i][0]['start_date'])) }}
                                                                            </span>
                                                                            -
                                                                            <span class="text-nowrap">
                                                                                {{ date('d/m/Y', strtotime($item_2['data'][$i][0]['end_date'])) }})
                                                                            </span>
                                                                        </small>
                                                                    </p>
                                                                @else
                                                                    <ul
                                                                        style="margin-left: -10px;margin-bottom: 0px;padding-inline-start: 25px;">
                                                                        @foreach ($item_2['data'][$i] as $item_activity)
                                                                            <li>
                                                                                <p
                                                                                    style="line-height: 1.1;margin-bottom: 7px;">
                                                                                    {{ $item_activity['name'] }}
                                                                                    <br />
                                                                                </p>
                                                                                <p class="text-primary fw-bold"
                                                                                    style="line-height: 0.85;">
                                                                                    <small>
                                                                                        <span class="text-nowrap">
                                                                                            ({{ date('d/m/Y', strtotime($item_activity['start_date'])) }}
                                                                                        </span>
                                                                                        -
                                                                                        <span class="text-nowrap">
                                                                                            {{ date('d/m/Y', strtotime($item_activity['end_date'])) }})
                                                                                        </span>
                                                                                    </small>
                                                                                </p>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    @endfor
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Job Assignment</th>
                                                <td colspan="{{ $item_year['max'] - $item_year['min'] + 1 }}"
                                                    class="text-center">
                                                    Sesuai Penugasan
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
