@extends('layouts.admin')

@section('content')
            
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <a href="{{ $backUrl }}">
                Add Form for ({{$userInfo->name}})
            </a>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <form id="addNew" class="addNew" action="{{ $url }}" enctype="multipart/form-data" method="post" autocomplete="off">
                  
                @csrf
                <input type="hidden" name="id" value="{{$rowInfo->id}}">
                <input type="hidden" name="user_id" value="{{$user_id}}">
                
                <div class="form-group">

                    <div class="col-sm-6 mb-3">
                        <select id="doc_type" name="doc_type" class="form-control"  required>
                            <option value="">Select Document Type</option>
                            <?php foreach($docTypes as $key=>$value) { ?>
                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="file" id="doc_file" name="doc_file" class="form-control"><br>
                        <?php if($rowInfo->id) { ?>
                        <span class="imageTag"><img src="{{ getImage($rowInfo->doc_file, 'users/'.$rowInfo->user_id.'/docs') }}"></span>
                        <?php } ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <button type="submit" class="btn btn-primary btn-user btn-block" id="formSubmit">Save</button>
                    </div>
                </div>
              </form>

        </div>
    </div>
</div>

@stop