@extends('layouts.admin')

@section('content')
            
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Change Password Form</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <form id="update-password" class="update-password" action="{{ $url }}" method="post">
                  
                @csrf
                <input type="hidden" name="id" value="{{$rowInfo->id}}">
                
                <div class="form-group">

                    <div class="col-sm-6 mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" required>
                        @if($errors->has('password'))
                        <small class="error-message">
                            {{$errors->first('password')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" value="" required>
                        @if($errors->has('confirm_password'))
                        <small class="error-message">
                            {{$errors->first('confirm_password')}}
                        </small>
                        @endif
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