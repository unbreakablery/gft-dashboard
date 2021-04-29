<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-csv text-primary"></i>
                </span>
                <span class="">Payroll</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Search Form</h3>
        </div>
        <div class="block-content">
            <form action="/payroll" method="POST" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col-lg-5 col-md-5">
                        <div class="form-group">
                            <label for="year-num">Year <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="year-num" name="year-num" required>
                                @for ($i = 2019; $i <= Date("Y"); $i++)
                                    <option value="{{ $i }}" @if ($i == $year_num) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <div class="form-group">
                            <label for="week-num">Week <span class="text-danger">*</span> :</label>
                            <select class="form-control" id="week-num" name="week-num" required>
                                @for ($i = 1; $i <= 52; $i++)
                                    <option value="{{ $i }}" @if ($i == $week_num) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label for="view-payroll">&nbsp;</label>
                            <button class="form-control btn btn-dark ml-auto mr-3" id="view-payroll" name="view-payroll">View Payroll</button>
                        </div>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">
                            <strong><i class="far fa-user"></i></strong>
                        </th>
                        <th class="text-center"><strong>Driver Name</strong></th>
                        <th class="d-none d-md-table-cell text-center" style="width: 10%;"><strong>Fixed Rate</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 20%;"><strong>Number of Trips for FR</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 10%;"><strong>Price Per Mile</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 15%;"><strong>Miles excl. FR</strong></th>
                        <th class="text-center" style="width: 15%;"><strong>Total Pay</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drivers as $idx => $driver)
                    <tr>
                        <td class="text-center">
                            {{ $idx + 1 }}
                        </td>
                        <td class="font-w600 font-size-sm text-left">
                            <a href="/payroll/get/{{ $driver->id }}/{{ $year_num }}/{{ $week_num }}">{{ $driver->driver_name }}</a>
                            @if ($driver->work_status == 0)
                                <span class="badge badge-pill badge-danger">No longer working</span>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            $ {{ number_format($driver->fixed_rate, 2) }}
                        </td>
                        <td class="d-none d-sm-table-cell text-right">
                            <span class="badge badge-primary">&nbsp;{{ $driver->cnt_trips_fix_rate }}&nbsp;</strong>
                        </td>
                        <td class="d-none d-sm-table-cell text-right">
                            $ {{ number_format($driver->price_per_mile, 2) }}
                        </td>
                        <td class="d-none d-sm-table-cell text-right">
                            {{ number_format($driver->miles_other, 2) }}
                        </td>
                        <td class="text-right text-warning">
                            <strong>$ {{ number_format($driver->total_payroll, 2) }}</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="text-right">
                <strong class="text-success">Total Payroll Amount: <span class="text-danger">$ {{ number_format($total, 2) }}</span></strong>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        
    });
});
</script>
</x-app-layout>
