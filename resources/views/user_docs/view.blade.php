@extends('layouts.admin')

@section('content')
            
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <a href="{{ $backUrl }}">
                {{$sub_title}} for ({{$rowInfo->user->name}})
            </a>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
                
            <div class="form-group">

                <div class="col-sm-6 mb-3">
                    <strong>User Name:</strong> {{$rowInfo->user->name}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Document Type:</strong> {{$rowInfo->doc_type}}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Document File</strong><br>
                    <span class="imageTag"><img src="{{ getImage($rowInfo->doc_file, 'users/'.$rowInfo->user_id.'/docs') }}"></span>
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Created:</strong> {{ formatedDate($rowInfo->created_at) }}
                </div>

                <div class="col-sm-6 mb-3">
                    <strong>Updated:</strong> {{ formatedDate($rowInfo->updated_at) }}
                </div>

            </div>

        </div>
    </div>
</div>

@stop