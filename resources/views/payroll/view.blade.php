<x-app-layout>

<div class="hero-static d-flex align-items-center">
    <div class="w-100">
        <div class="bg-white">
            <div class="content content-full">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-4 py-4">
                        <div class="text-center mb-5">
                            <p class="mb-2">
                                <i class="fa fa-2x fa-money-check text-primary"></i>
                            </p>
                            <h1 class="h3 mb-1 font-w600 text-uppercase">
                                Payroll For Driver
                            </h1>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-lg btn-alt-primary" href="javascript:window.history.back(-1);">
                                <i class="fa fa-arrow-left mr-1"></i> Payroll List
                            </a>
                            <a class="btn btn-lg btn-alt-success" href="/payroll/rate/{{ $payroll->id }}">
                                <i class="fa fa-cogs"></i> <span class="d-none d-sm-inline-block ml-1">Rate Setting</span>
                            </a>
                        </div>
                        <hr>
                        <div class="alert alert-success" role="alert">
                            <p class="mb-0"><strong>Miles For Fixed Rate</strong>: Between <strong>{{ $from_m_fr }}</strong> and <strong>{{ $to_m_fr }}</strong></p>
                        </div>
                        <div class="alert alert-info" role="alert">
                            <p class="mb-0">You can set the fixed rate and price per mile for driver in "Rate Setting".</p>
                        </div>
                        <ul class="list-group push">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver ID:
                                <span class="badge badge-success">{{ $payroll->driver_id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Driver Name:
                                <span class="font-w600"><strong>{{ $payroll->driver_name }}</strong></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                Fixed Rate:
                                <span class="badge badge-pill badge-primary"><i class="fa fa-dollar-sign"></i> {{ number_format($payroll->fixed_rate, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Number Of Trips For Fixed Rate:
                                <span class="font-w600">{{ $payroll->cnt_trips_fix_rate }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Miles For Fixed Rate:
                                <span class="font-w600">{{ number_format($payroll->miles_fix_rate, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Payroll For Fixed Rate:
                                <span class="font-w600 text-warning"><i class="fa fa-dollar-sign"></i> <em>{{ number_format($payroll->payroll_fix_rate, 2) }}</em></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                Price Per Mile:
                                <span class="badge badge-pill badge-primary"><i class="fa fa-dollar-sign"></i> {{ number_format($payroll->price_per_mile, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Miles For Price Per Mile:
                                <span class="font-w600">{{ number_format($payroll->miles_other, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Payroll For Price Per Mile:
                                <span class="font-w600 text-warning"><i class="fa fa-dollar-sign"></i> <em>{{ number_format($payroll->payroll_per_mile, 2) }}</em></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-gray-light">
                                Total Miles For Driver:
                                <span class="font-w600 text-danger">{{ number_format($payroll->total_miles, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Total Payroll:</strong>
                                <span class="font-w600 text-primary"><strong><i class="fa fa-dollar-sign"></i> {{ number_format($payroll->total_payroll, 2) }}</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
