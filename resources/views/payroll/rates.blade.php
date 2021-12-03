<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
</style>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-cogs text-primary"></i>
                </span>
                <span class="">Rates</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="block block-themed">
        <div class="block-header">
            <h3 class="block-title">Drivers List</h3>
        </div>
        <div class="block-content">
            @if (session('status'))
                <div class="alert alert-success alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <p><i class="fa fa-fw fa-info-circle"></i> {!! session('status') !!}</p>
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
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                    <p class="font-size-sm text-muted">
                        <i class="fa fa-info-circle"></i> You can set fixed rates per mileage <strong>(it will affect globally.)</strong> and prices per mile for each driver.
                    </p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-right">
                    <button type="button" class="btn btn-success fixed-rates-setting"><i class="fa fa-cog"></i> Fixed Rate Setting</button>
                </div>
            </div>
            
            <table class="table table-bordered table-striped table-vcenter table-dark">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 100px;">
                            <strong><i class="far fa-user"></i></strong>
                        </th>
                        <th class="d-none d-md-table-cell text-center" style="width: 20%;"><strong>Driver ID</strong></th>
                        <th class="text-center"><strong>Driver Name</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 20%;"><strong>Work Status</strong></th>
                        <th class="d-none d-sm-table-cell text-center" style="width: 20%;"><strong>Price Per Mile</strong></th>
                        <th class="text-center" style="width: 100px;"><strong>Actions</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rates as $idx => $rate)
                    <tr>
                        <td class="text-center">
                            {{ $idx + 1 }}
                        </td>
                        <td class="d-none d-md-table-cell text-center">
                            {{ $rate->driver_id }}
                        </td>
                        <td class="font-w600 font-size-sm text-left">
                            {{ $rate->driver_name }}
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-center">
                            @if ($rate->work_status == 0)
                                <span class="badge badge-pill badge-danger">No longer working</span>
                            @else
                                <span class="badge badge-pill badge-success">Working now</span>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm text-right">
                            $ {{ number_format($rate->price_per_mile, 2) }}
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-dark edit-rate" data-toggle="tooltip" title="Edit Rate" data-id="{{ $rate->id }}">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark remove-rate" data-toggle="tooltip" title="Delete Rate" data-id="{{ $rate->id }}">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-dark change-workstatus" data-toggle="tooltip" title="Change Work Status" data-id="{{ $rate->id }}">
                                    <i class="fa fa-exchange-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        $("button.edit-rate").click(function() {
            let id = $(this).data("id");
            window.location.href = "/payroll/rate/" + id;
        });

        $("button.remove-rate").click(function() {
            let id = $(this).data("id");
            window.location.href = "/payroll/rate/remove/" + id;
        });
        
        $("button.fixed-rates-setting").click(function() {
            window.location.href = "/payroll/fixed-rates";
        });

        $("button.change-workstatus").click(function() {
            let id = $(this).data("id");
            window.location.href = "/payroll/work-status/save/" + id;
        });
    });
});
</script>
</x-app-layout>
