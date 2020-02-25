@extends('admin.main')
@section('title')
    <title>Message Inbox</title>
@endsection

@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
@endpush

@section('content')
<div class="section">
<div class="section-header">
    <h1>Message Inbox</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Message Inbox</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Message Inbox List</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="inbox">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Message</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div> 

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Detail Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
        <div class="modal-body"> 
          <div class="card-body">
            <div class="form-group">
              <label>Name</label>
              <input disabled type="text" class="form-control" name="name" id="name">
            </div>
            <div class="form-group">
              <label>Email</label>
                <input disabled type="email" class="form-control" name="email" id="email">
            </div>
            <div class="form-group">
              <label>Message</label>
                <textarea id="description" class="summernote-simple" name="message"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
        </div>
      </form>
    </div>
  </div>
</div> 


<!-- modal -->
@endsection

@push('scripts')
<script>
$( document ).ready(function() {
  $('#dec').summernote('disable');
});
</script>
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
@endpush

@push('scripts')
<script>
  $(function() {
    
      let url = "{{ route('inbox.getdata') }}";
  
        $('#inbox').DataTable({
          'processing': true,
          'serverSide': true,
          "deferRender": true,
          "info": true,
          "autoWidth": false,
          columnDefs: [
            { width: "150px", targets: 3 }
          ],
          searchDelay: 600,
          ajax: url,
          columns: [
              { data: 'id',name:'id',render: function (data, type, row, meta) 
                {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
              },
              { data: 'name',name: 'name'},
              { data: 'email',name: 'email'},
              { data: 'data',name: 'data',"searchable": false},
              { data: 'created_at',name: 'created_at',"searchable": false},
              { data: 'updated_at',name: 'updated_at',"searchable": false},
              { data: 'id',name: 'id',"searchable": false,
                render: function(data) { 
                  return '<a href="#" class="btn btn-primary" onclick="getData(`'+data+'`)">Detail</a>'
  
                  },
              },
          ]
      });
  });
  $(function(){
    var dtable=$('#inbox').dataTable();
  
  $(".dataTables_filter input")
      .unbind() 
      .bind("input", function(e) { 
   
          if(this.value.length >= 3 || e.keyCode == 13) {
            
              dtable.api().search(this.value).draw();
          }
          
          if(this.value == "") {
              dtable.api().search("").draw();
          }
          return;
      })
      .bind("keyup", function(e) { 
         
          if(e.keyCode == 13) {
              dtable.api().search(this.value).draw();
          }
   
          if(this.value == "") {
              dtable.api().search("").draw();
          }
          return;
      });
  });
  
  //get detail
  function getData(id){

    let url = "{{ route('inbox.show',[':id']) }}";
          url = url.replace(':id', id);
  
          $.ajax({
          url: url,
          dataType: "JSON",
          type: 'get',
          beforeSend: function () {
              $(".overlay").show();
            },
            complete: function () {
              $(".overlay").hide();
            },
          success: function(response){ 
            $('#description').summernote('disable');
            $("#name").val(response.name);
            $("#email").val(response.email);
            $('#description').summernote('code', response.data);

            // Display Modal
            $('#detailModal').modal('show'); 
          }
        });
  
  }
</script>
@endpush