<table class="table table-bordered table-striped table-vcenter table-dark">
    <thead>
        <tr>
            @foreach ($headers as $key => $header)
                @if ($key == "week_name" || in_array($key, $compare_list))
                    <th class="text-center"><strong>{{ $header }}</strong></th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($values as $value)
        <tr>
            <td class="text-center">
                {{ $value->week_name }}
            </td>
            @if (in_array("revenue", $compare_list))
            <td class="text-center">
                $ {{ $value->revenue }}
            </td>
            @endif
            @if (in_array("miles-total", $compare_list))
            <td class="text-center">
                {{ $value->miles }} mi.
            </td>
            @endif
            @if (in_array("fuelcost-total", $compare_list))
            <td class="text-center">
                $ {{ $value->fuel_cost }}
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>