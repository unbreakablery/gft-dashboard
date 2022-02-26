<x-app-layout>

<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="d-flex flex-sm-fill h3 my-2 text-primary align-items-center font-w700">
                <span class="item item-circle bg-primary-lighter mr-sm-3">
                    <i class="fa fa-cog text-primary"></i>
                </span>
                <span class="">Payroll Setting</span>
            </h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-default-darker">
                    <h3 class="block-title">Payroll Setting Form</h3>
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
                            <p>{!! session('error') !!}</p>
                        </div>
                    @endif
                    <form class="js-validation" action="/payroll/save-setting" method="POST" id="setting-form" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="table-responsive push text-right">
                            <button type="submit" class="btn btn-primary save-setting">
                                <i class="fa fa-save"></i> Save Setting
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter" id="setting-table">
                                <tbody>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Sending Email Method<span class="text-danger">*</span> : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="sending-method" id="auto-method" value="1" @if(isset($sending_method) && $sending_method->value == '1') {{ 'checked' }}@endif required/>
                                                <label class="form-check-label" for="auto-method">Automatically</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="sending-method" id="manual-method" value="0" @if(isset($sending_method) && $sending_method->value == '0') {{ 'checked' }}@endif required/>
                                                <label class="form-check-label" for="manual-method">Manually</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-w800 text-right" style="width: 20%;">Delivery Date : </td>
                                        <td class="text-left" style="width: 30%;">
                                            <select name="delivery-date" id="delivery-date" class="form-control">
                                                <option value="Monday" @if(isset($delivery_date) && $delivery_date->value == 'Monday'){{ 'selected' }}@endif>Monday</option>
                                                <option value="Tuesday" @if(isset($delivery_date) && $delivery_date->value == 'Tuesday'){{ 'selected' }}@endif>Tuesday</option>
                                                <option value="Wednesday" @if(isset($delivery_date) && $delivery_date->value == 'Wednesday'){{ 'selected' }}@endif>Wednesday</option>
                                                <option value="Thursday" @if(isset($delivery_date) && $delivery_date->value == 'Thursday'){{ 'selected' }}@endif>Thursday</option>
                                                <option value="Friday" @if(isset($delivery_date) && $delivery_date->value == 'Friday'){{ 'selected' }}@endif>Friday</option>
                                                <option value="Saturday" @if(isset($delivery_date) && $delivery_date->value == 'Saturday'){{ 'selected' }}@endif>Saturday</option>
                                                <option value="Sunday" @if(isset($delivery_date) && $delivery_date->value == 'Sunday'){{ 'selected' }}@endif>Sunday</option>
                                            </select>
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
<script type="text/javascript">
    jQuery(function($){
        $(document).ready(function() {
            
        });
    });
</script>
</x-app-layout>