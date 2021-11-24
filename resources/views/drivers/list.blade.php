<x-app-layout>
<style type="text/css">
    .badge {
        font-size: 100%;
    }
    .badge-violet {
        color: #fff;
        background-color: #7F00FF;
    }
</style>
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-users text-primary"></i>
                </span>
                <span class="">Drivers</span>
            </h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Drivers List</h3>
                </div>
                <div class="block-content">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <p class="mb-0"><i class="fa fa-fw fa-info-circle"></i> {!! session('success') !!}</p>
                        </div>
                    @endif
                    <form action="/drivers/remove-bulk" method="POST">
                    @csrf
                    <div class="table-responsive push text-right">
                        <button type="button" class="btn btn-dark" id="add-driver">
                            <i class="fa fa-plus"></i> Add Driver
                        </button>
                        <button type="button" class="btn btn-success" id="add-bulk-drivers">
                            <i class="fa fa-upload"></i> Add Bulk Drivers
                        </button>
                        <button type="submit" class="btn btn-danger" id="remove-bulk-drivers">
                            <i class="fa fa-trash"></i> Remove Bulk Drivers
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-dark table-vcenter" id="drivers-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center" style="width: 20px;">
                                        <input type="checkbox" name="all-check" id="all-check">
                                    </th>
                                    <th class="text-center" style="width: 80px;">#</th>
                                    <th class="text-center" style="width: 10%;">Driver ID</th>
                                    <th class="text-center" style="width: 25%;">Name</th>
                                    <th class="text-center" style="width: 10%;">Phone</th>
                                    <th class="text-center" style="width: 10%;">License</th>
                                    <th class="text-center">Address</th>
                                    <th class="d-none d-md-table-cell text-center" style="width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (isset($drivers) && count($drivers) > 0)
                            @foreach ($drivers as $idx => $driver)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="checked-drivers[]" value="{{ $driver->id }}" />
                                    </td>
                                    <td class="font-w600 font-size-sm text-center">{{ $idx + 1 }}</td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <strong class="text-primary">{{ $driver->driver_id }}</strong>
                                    </td>
                                    <td class="font-w600 font-size-sm text-left">
                                        {{ $driver->driver_name }}
                                        @if ($driver->work_status == 0)
                                        <span class="font-w600 badge badge-pill badge-danger">No longer working</span>
                                        @endif
                                    </td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <span class="badge badge-pill badge-violet">{{ $driver->phone ? '+'.$driver->phone : '' }}</span>
                                    </td>
                                    <td class="font-w600 font-size-sm text-center">
                                        <span class="badge badge-success">{{ $driver->license }}</span>
                                    </td>
                                    <td class="font-w600 font-size-sm text-left">{{ $driver->address }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-dark view-driver" title="View Driver" data-id="{{ $driver->id }}">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark edit-driver" title="Edit Driver" data-id="{{ $driver->id }}">
                                                <i class="fa fa-fw fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-dark remove-driver" title="Remove Driver" data-id="{{ $driver->id }}">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="font-w600 font-size-sm text-center">No Drivers</td>
                                </tr>
                            @endif    
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
                <!-- Driver Info: Pop Out Block Modal -->
                <div class="modal fade" id="modal-driver-info" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Driver Info</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-dark table-vcenter" id="driver-table">
                                            <tbody>
                                                <tr>
                                                    <td class="font-w600 text-right">Driver ID : </td>
                                                    <td class="text-left text-primary" id="t_driver_id"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Driver Name : </td>
                                                    <td class="text-left" id="t_driver_name"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Phone # : </td>
                                                    <td class="text-left" id="t_phone"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">License : </td>
                                                    <td class="text-left" id="t_license"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Address : </td>
                                                    <td class="text-left" id="t_address"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Fxied Rate : </td>
                                                    <td class="text-left" id="t_fixed_rate"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Price Per Mile : </td>
                                                    <td class="text-left" id="t_price_per_mile"></td>
                                                </tr>
                                                <tr>
                                                    <td class="font-w600 text-right">Work Status : </td>
                                                    <td class="text-left" id="t_work_status"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-sm btn-dark" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Pop Out Block Modal -->
                <!-- Upload Form: Pop Out Block Modal -->
                <div class="modal fade" id="modal-upload-form" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Upload Form</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content font-size-sm">
                                    <div class="row push">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 alert alert-info alert-dismissable" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <p style="margin-bottom: 0;"><i class="fa fa-fw fa-info-circle"></i> While uploading data, old data will be updated !</p>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <p class="font-size-sm text-muted text-right">
                                                Bulk Drivers File:
                                            </p>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-12 overflow-hidden">
                                            <form action="/drivers/upload" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="file" id="upload-file" name="upload-file" accept=".xlsx,.xls,.csv">
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-upload text-mute"></i> Upload
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-sm btn-dark" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Pop Out Block Modal -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    $(document).ready(function() {
        function inital_modal() {
            $('#driver-table').removeClass('d-none');
            $('#modal-driver-info .table-responsive .alert').remove();

            $('#t_driver_id').html('');
            $('#t_driver_name').html('');
            $('#t_phone').html('');
            $('#t_license').html('');
            $('#t_address').html('');
            $('#t_fixed_rate').html('');
            $('#t_price_per_mile').html('');
            $('#t_work_status').html('');
        }
        $('button.view-driver').click(function() {
            var id = $(this).data('id');
            $.ajax({
				url:        "/drivers/get",
				dataType:   "json",
				type:       "post",
				data:       {
                                _token: "{{ csrf_token() }}",
                                id: id
                            },
				success:    function( data ) {
                    if (data.type == 'success') {
                        inital_modal();
                        
                        $('#t_driver_id').html(data.driver.driver_id);
                        $('#t_driver_name').html(data.driver.driver_name);
                        $('#t_phone').html('<span class="badge badge-pill badge-violet">' + (data.driver.phone ? '+' + data.driver.phone : '' )+ '</span>');
                        $('#t_license').html('<span class="badge badge-success">' + (data.driver.license ? data.driver.license : '') + '</span>');
                        $('#t_address').html(data.driver.address);
                        $('#t_fixed_rate').html(data.driver.fixed_rate);
                        $('#t_price_per_mile').html(data.driver.price_per_mile);
                        $('#t_work_status').html(data.driver.work_status ? 'Working' : '<span class="font-w600 text-danger">No longer working</span>');
                    } else {
                        inital_modal();
                        
                        $('#driver-table').addClass('d-none');
                        $('#modal-driver-info .table-responsive').append(
                                '<div class="alert alert-danger alert-dismissable" role="alert">' + 
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' + 
                                        '<span aria-hidden="true">×</span>' +
                                    '</button>' +
                                    '<p class="mb-0">' + data.message + '</p>' +
                                '</div>');
                    }
                    $('#modal-driver-info').modal('show');
				}
            });
        });
        $('button.edit-driver').click(function() {
            var id = $(this).data('id');
            location.href = '/drivers/edit/' + id;
        });
        $('button.remove-driver').click(function() {
            var id = $(this).data('id');
            location.href = '/drivers/remove/' + id;
        });
        $('button#add-driver').click(function() {
            location.href = '/drivers/add';
        });
        $('button#add-bulk-drivers').click(function() {
            $('#modal-upload-form').modal('show');
        });
        $('input[type=checkbox][name=all-check]').click(function() {
            var drivers = $('input[type=checkbox][name="checked-drivers[]"]');
            for (let i = 0; i < drivers.length; i++) {
                drivers[i].checked = this.checked;
            }
        });
    });
});
</script>
</x-app-layout>