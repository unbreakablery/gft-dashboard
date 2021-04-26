<table class="table table-bordered table-striped table-vcenter table-dark">
    <thead>
        <tr>
            <th class="text-center"></th>
            @foreach ($headers as $idx => $header)
                <th class="text-center"><strong>{{ $header }}</strong></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center text-success">
                <strong>Total Fuel Cost</strong>
            </td>
            @foreach ($values as $idx => $value)
            <td class="text-center">
                $ {{ $value->cost }}
            </td>    
            @endforeach
        </tr>
    </tbody>
</table>