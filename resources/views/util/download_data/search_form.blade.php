<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-file-excel text-primary"></i>
                </span>
                <span class="">Download Historical Data</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header">
                    <h3 class="block-title">Search Form</h3>
                </div>
                <div class="block-content">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{!! session('status') !!}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <div class="container">
                        <form id="search-form" action="{{ route('util.download-data.download') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="table-responsive" style="overflow-x: hidden;">
                                <table class="table table-striped table-vcenter" id="download-form-table">
                                    <tbody>
                                        <tr>
                                            <td class="font-w800 text-right">From <span class="text-danger">*</span> : </td>
                                            <td class="text-left">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select class="form-control" id="from-year-num" name="from-year-num" required>
                                                            @for ($i = 2019; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}" {{ (isset($from_year_num)) ? ($i == $from_year_num ? "selected" : "") : ($i == Date("Y") ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-control" id="from-week-num" name="from-week-num" required>
                                                            @for ($i = 1; $i <= 52; $i++)
                                                            <option value="{{ $i }}" {{ (isset($from_week_num)) ? ($i == $from_week_num ? "selected" : "") : ($i == Date("W") - 1 ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-w800 text-right">To <span class="text-danger">*</span> : </td>
                                            <td class="text-left">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select class="form-control" id="to-year-num" name="to-year-num" required>
                                                            @for ($i = 2019; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}" {{ (isset($to_year_num)) ? ($i == $to_year_num ? "selected" : "") : ($i == Date("Y") ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-control" id="to-week-num" name="to-week-num" required>
                                                            @for ($i = 1; $i <= 52; $i++)
                                                            <option value="{{ $i }}" {{ (isset($to_week_num)) ? ($i == $to_week_num ? "selected" : "") : ($i == Date("W") - 1 ? "selected" : "")}}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="font-w800 text-right">Key Metrics<span class="text-danger">*</span> : </td>
                                            <td class="text-left">
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="revenue" id="revenue" name="key-metrics[]">
                                                    <label class="form-check-label" for="revenue">Revenue</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="miles-total" id="miles-total" name="key-metrics[]">
                                                    <label class="form-check-label" for="miles-total">Total Miles</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="miles-driver" id="miles-driver" name="key-metrics[]">
                                                    <label class="form-check-label" for="miles-driver">Miles by driver</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="miles-vehicle" id="miles-vehicle" name="key-metrics[]">
                                                    <label class="form-check-label" for="miles-vehicle">Miles by vehicle</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="mpg-vehicle" id="mpg-vehicle" name="key-metrics[]">
                                                    <label class="form-check-label" for="mpg-vehicle">MPG by vehicle</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="trips-driver" id="trips-driver" name="key-metrics[]">
                                                    <label class="form-check-label" for="trips-driver">Trips by driver</label>
                                                </div>
                                                <div class="form-check push">
                                                    <input class="form-check-input" type="checkbox" value="fuelcost-total" id="fuelcost-total" name="key-metrics[]">
                                                    <label class="form-check-label" for="fuelcost-total">Total Fuelcost</label>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="font-w800 text-right"></td>
                                            <td class="text-left form-group">
                                                <button type="button" class="btn btn-primary" id="download-data">
                                                    <i class="fa fa-download text-mute"></i> Donwload Data
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $('button#download-data').click(function() {
            let from_year_num = $('#from-year-num').val();
            let to_year_num = $('#to-year-num').val();
            let from_week_num = $('#from-week-num').val();
            let to_week_num = $('#to-week-num').val();

            from_week_num = (from_week_num < 10) ? '0' + from_week_num : from_week_num;
            to_week_num = (to_week_num < 10) ? '0' + to_week_num : to_week_num;

            let from = from_year_num + from_week_num;
            let to = to_year_num + to_week_num;


            if (from > to) {
                alert("Invalid time period !");
                $('#from-year-num').focus();
                return;
            }

            let hasKeyMetrics = false;
            $("input[name='key-metrics[]']").each(function() {
                hasKeyMetrics ||= this.checked;
            });

            if (!hasKeyMetrics) {
                alert("Invalid Key Metrics !");
                return;
            }

            $('form#search-form').submit();
        });
    });
});
</script>
</x-app-layout>
