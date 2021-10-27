@extends('layouts.app');

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @if(Session()->has('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{Session()->get('status')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Report</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <form action="/report/show" method="GET">
            <div class="col-md-12">
                <label>Choose date from report</label>
                <div class="form-group">
                    <div class="input-group date" id="date-start" data-target-input="nearest">
                        <input type="text" name="dateStart" class="form-control datetimepicker-input" data-target="#date-start"/>
                        <div class="input-group-append" data-target="#date-start" data-toggle="datetimepicker">
                            <div class="input-group-text">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group date" id="date-end" data-target-input="nearest">
                        <input type="text" name="dateEnd" class="form-control datetimepicker-input" data-target="#date-end"/>
                        <div class="input-group-append" data-target="#date-end" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary mb-2" value="Show Report" >
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#date-start').datetimepicker({
            format: 'L'
        });
        $('#date-end').datetimepicker({
            format: 'L',
            useCurrent: false
        });
        $("#date-start").on("change.datetimepicker", function (e) {
            $('#date-end').datetimepicker('minDate', e.date);
        });
        $("#date-end").on("change.datetimepicker", function (e) {
            $('#date-start').datetimepicker('maxDate', e.date);
        });
    });
</script>

@endsection
