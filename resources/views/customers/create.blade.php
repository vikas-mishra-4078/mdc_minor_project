@extends('layouts.admin')

@section('content')
            
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add/Edit Form</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">

            <form id="addNew" class="addNew" action="{{ $url }}" enctype="multipart/form-data" method="post" autocomplete="off">
                  
                @csrf
                <input type="hidden" name="id" value="{{$rowInfo->id}}">
                
                <div class="form-group">

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Display Name" value="{{old('name', $rowInfo->name)}}" required>
                        @if($errors->has('name'))
                        <small class="error-message">
                            {{$errors->first('name')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{old('email', $rowInfo->email)}}" <?=($rowInfo->id)?'readonly':''?> required>
                        @if($errors->has('email'))
                        <small class="error-message">
                            {{$errors->first('email')}}
                        </small>
                        @endif
                    </div>

                   
                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="mobile" placeholder="Mobile" value="{{old('mobile', $rowInfo->mobile)}}" required>
                        @if($errors->has('mobile'))
                        <small class="error-message">
                            {{$errors->first('mobile')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="room_no" placeholder="Room number" value="{{old('room_no', $rowInfo->room_no)}}" required>
                        @if($errors->has('room_no'))
                        <small class="error-message">
                            {{$errors->first('room_no')}}
                        </small>
                        @endif
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="address" placeholder="Address" value="{{old('address', $rowInfo->address)}}" required>
                        @if($errors->has('address'))
                        <small class="error-message">
                            {{$errors->first('address')}}
                        </small>
                        @endif
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                        <input type="file" id="image" name="image" class="form-control"><br>
                        <?php if($rowInfo->id) { ?>
                        <span class="imageTag"><img src="{{ getImage($rowInfo->image, 'users/'.$rowInfo->id.'/medium') }}"></span>
                        <?php } ?>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <select id="status" name="status" class="form-control"  required>
                            <option value="0" {{ $rowInfo->status==0 ? 'selected' : '' }}>Disable</option>
                            <option value="1" {{ $rowInfo->status==1 || $rowInfo->id==null ? 'selected' : '' }}>Enable</option>
                        </select>
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