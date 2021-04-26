<table class="table table-bordered table-striped table-vcenter table-dark">
    <thead>
        <tr>
        <th class="text-center">Driver ID</th>
            <th class="text-center">Driver Name</th>
            @foreach ($headers as $idx => $header)
                <th class="text-center"><strong>{{ $header }}</strong></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($values as $key => $value)
        <tr>
            <td class="text-center text-primary">
                <strong>{{ $key }}</strong>
            </td>
            <td class="text-center text-success">
                <strong>{{ $value['driver_name'] }}</strong>
            </td>
            @foreach ($value['trips'] as $idx => $v)
            <td class="text-right">
                {{ $v }}
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>