<table class="table table-bordered table-striped table-vcenter table-dark">
    <thead>
        <tr>
            <th class="text-center">Vehicle #</th>
            @foreach ($headers as $idx => $header)
                <th class="text-center"><strong>{{ $header }}</strong></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($values as $key => $value)
        <tr>
            <td class="text-center text-success">
                <strong>{{ $key }}</strong>
            </td>
            @foreach ($value as $v)
            <td class="text-right">
                {{ number_format($v, 2) }}
            </td>    
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>