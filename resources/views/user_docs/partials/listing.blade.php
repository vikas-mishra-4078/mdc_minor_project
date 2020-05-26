
<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div class="row">
        <table id="listingTable" class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-align-center">SN.</th>
                    <th>File</th>
                    <th>User Name</th>
                    <th>File Type</th>
                    <th class="text-align-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;?>
                @forelse($results as $value)
                    <tr id="RecordID_{{$value->id}}">
                        <td class="text-align-center"><?php echo $count++?></td>
                        <td class="text-align-center"><span class="imageTag"><img src="{{ getImage($value->doc_file, 'users/'.$value->user_id.'/docs') }}" width="100"></span></td>
                        <td>{{ $value->user->name }}</td>
                        <td>{{ $value->doc_type }}</td>
                        <td>{{ $value->doc_type }}</td>

                        <td class="text-align-center">
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('user-doc-view', ['user_id' => $value->user_id, 'id' => $value->id]) }}" title="View"><i class="fas fa-eye"></i></a>
                            <a class="btn btn-danger btn-circle btn-sm deleteRecord" href="javascript:;" data-href="{{ route('user-doc-delete') }}" title="Delete" onclick="deleteRecord('{{$value->id}}');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="center">Record not found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
