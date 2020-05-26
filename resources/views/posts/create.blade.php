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
                        <select id="user_id" name="user_id" class="form-control"  required>
                            @foreach($users as $id=>$name)
                            <option value="{{ $id }}" {{ (old('user_id', $rowInfo->user_id)==$id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-6 mb-3">
                        <input type="text" class="form-control" name="title" placeholder="Post title" value="{{old('title', $rowInfo->title)}}"  required>
                        @if($errors->has('title'))
                        <small class="error-message">
                            {{$errors->first('title')}}
                        </small>
                        @endif
                    </div>
                    
                    <div class="col-sm-6 mb-3">
                    <textarea rows="5" cols="50" input type="text" class="form-control" name="description" placeholder="Description" value="{{old('description', $rowInfo->description)}}" required></textarea>
                        @if($errors->has('description'))
                        <small class="error-message">
                            {{$errors->first('description')}}
                        </small>
                        @endif
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