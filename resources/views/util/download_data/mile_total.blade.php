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
                <strong>Total Miles</strong>
            </td>
            @foreach ($values as $idx => $value)
            <td class="text-center">
                {{ $value }} mi.
            </td>    
            @endforeach
        </tr>
    </tbody>
</table>