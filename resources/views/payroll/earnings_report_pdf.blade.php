<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Driver Earnings Report - {{ $driver_name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<style type="text/css">
    p {
        font-size: 1.5rem !important;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    @page {
        margin-bottom:0px;
    }
    
    .font-weight-bolder {
        font-weight: bolder !important;
    }
    .font-size-h3 {
        font-size: 1.25rem !important;
    }
</style>
<body>
    <!-- Header -->
    <table class="table table-bordered table-striped table-light table-vcenter">
        <thead>
            <tr>
                <th rowspan="3" style="width: 260px; vertical-align: middle;">
                    <img src="{{ asset('storage/uploads/company/' . $company['logo']) }}"
                        alt="Company Logo"
                        title="{{ $company['brand'] }}"
                        width="105"
                    />
                </th>
                <th rowspan="3" class="text-center font-weight-bolder font-size-h3" style="vertical-align: middle;">
                    <p>{{ $company['name'] }}</p>
                    <p class="mb-0">{{ 'Driver Earnings Report' }}</p>
                </th>
                <th class="text-right font-weight-bolder" style="width: 60px;">Date:</th>
                <th class="font-weight-bolder" style="width: 200px;">{{ $from_date }} - {{ $to_date }}</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th class="text-right font-weight-bolder"></th>
                <th class="font-weight-bolder"></th>
            </tr>
        </thead>
        <tbody>
            <tr class="font-weight-bolder">
                <td colspan="4">Payment Date: {{ $payment_date }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Overview -->
    <table class="table table-bordered table-striped table-light table-vcenter mt-1">
        <thead>
            <tr>
                <th class="text-center">Driver ID</th>
                <th class="text-center">Driver Name</th>
                <th class="text-center"># of Trips</th>
                <th class="text-center">Metro $</th>
                <th class="text-center">Miles Driven</th>
                <th class="text-center">$/Mile</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">{{ $driver_id }}</td>
                <td class="text-center">{{ $driver_name }}</td>
                <td class="text-center">{{ $fr_trips_num }}</td>
                <td class="text-center">${{ number_format($fr_price, 2) }}</td>
                <td class="text-center">{{ $other_miles }}</td>
                <td class="text-center">${{ number_format($other_price, 2) }}</td>
                <td class="text-center">${{ number_format($total_price, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Driver Earnings Report Detail -->
    <table class="table table-bordered table-striped table-light table-vcenter mt-1 mb-0">
        <thead>
            <tr>
                <th class="text-center font-weight-bolder font-size-h3">
                    {{ __('Driver Earnings Report Detail') }}
                </th>
            </tr>
            <tr>
                <th class="font-weight-bolder">
                    {{ __('LineHaul Trips') }}
                </th>
            </tr>
        </thead>
    </table>
    <table class="table table-bordered table-striped table-light table-vcenter mt-0">
        <thead>
            <tr>
                <th>Date</th>
                <th>Origin</th>
                <th>Destination</th>
                <th class="text-right">Miles</th>
                <th class="text-right">Pay Rate</th>
                <th class="text-right">Payroll Value</th>
            </tr>
        </thead>
        <tbody>
            @if (count($trips))
            @foreach ($trips as $trip)
            <tr>
                <td>{{ $trip['date'] }}</td>
                <td>{{ $trip['origin'] }}</td>
                <td>{{ $trip['destination'] }}</td>
                <td class="text-right">{{ number_format($trip['miles'], 2) }}</td>
                <td class="text-right">{{ $trip['pay_rate_unit'] }}{{ number_format($trip['pay_rate'], 2) }}</td>
                <td class="text-right">${{ number_format($trip['value'], 2) }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td class="text-center" colspan="6">No Trips</td>
            </tr>
            @endif
            <tr>
                <td colspan="5" class="font-weight-bolder">Total</td>
                <td  class="text-right font-weight-bolder">
                    ${{ number_format($total_price, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>