@extends('admin.main')
@section('title')
    <title>Partnership</title>
@endsection

@push('css') 
<style> 
.select2-results__options{
    max-height: 300px!important;
    height: 300px!important;
}

.select2-results{
    max-height: 300px!important;
    height: 300px!important;
}
</style>
@endpush

@section('content')
<div class="section">
<div class="section-header">
    <h1>Partnership</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Partnership</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Partnership List</h4>
      <div class="card-header-action">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add New</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="menuContent">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Link</th>
              <th>Image</th>
              <th>Status</th>
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
<div class="modal fade" id="createModal" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('partnership.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
          @endif
          <div class="card-body">
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" required="" name="title" value="{{ old('title') }}">
              <div class="invalid-feedback">
                Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Link</label>
              <input type="text" class="form-control" required="" name="link" value="{{ old('link') }}">
              <div class="invalid-feedback">
                Link Required
              </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select class="form-control" required="" name="status">
                  <option value="active">Active</option>
                  <option value="inactive" selected="selected">Inactive</option>
                </select>
                <div class="invalid-feedback">
                  Status Required
                </div>
              </div>
            <div class="form-group">
              <label>Image</label>
              <input type="file" accept="image/*" name="image" required class="form-control imagesUpload">
              <div class="invalid-feedback">
                Image Required
              </div>
              <br>
              <img style="width:100%" src="" id="output" class="output"/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div> 

<div class="modal fade" id="editModal" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('partnership.update')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" required="" name="idUpdate" value="" id="idUpdate" readonly>
        <input type="hidden" class="form-control" required="" name="currentImage" value="" id="currentImage" readonly>
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
          @endif
          <div class="card-body">

            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" required="" name="title" value="{{ old('title') }}" id="title">
              <div class="invalid-feedback">
                Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Link</label>
              <input type="text" class="form-control" required="" name="link" value="{{ old('link') }}" id="link">
              <div class="invalid-feedback">
                Link Required
              </div>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" required="" name="status" id="status">
                <option value="active">Active</option>
                <option value="inactive" selected="selected">Inactive</option>
              </select>
              <div class="invalid-feedback">
                Status Required
              </div>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" accept="image/*" name="image" id="image" class="form-control imagesUpload">
                <div class="invalid-feedback">
                  Image Required
                </div>
                <br>
                <img style="width:100%" src="" id="output_edit" class="images output"/>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- modal -->
@endsection

@push('scripts')
<script>
  $(function() {
    
      let url = "{{ route('partnership.getdata') }}";
  
        $('#menuContent').DataTable({
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
              { data: 'idPartership',name:'idPartership',render: function (data, type, row, meta) 
                {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
              },
              { data: 'title',name: 'title'},
              { data: 'link',name: 'link'},
              { data: 'image',name: 'image',"searchable": false},
              { data: 'status',name: 'status',"searchable": false},
              { data: 'created_at',name: 'created_at',"searchable": false},
              { data: 'updated_at',name: 'updated_at',"searchable": false},
              { data: 'idPartership',name: 'idPartership',"searchable": false,
                render: function(data) { 
                  return '<a class="btn btn-primary btn-action mr-1" onclick="getData('+data+')"><i class="fas fa-pencil-alt"></i></a> <a class="btn btn-danger btn-action trigger--fire-modal-1" onclick="performDelete('+data+')"><i class="fas fa-trash"></i></a>'
  
                  },
              },
          ]
      });
  });
  $(function(){
    var dtable=$('#slider').dataTable();
  
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
    let url = "{{ route('partnership.show',[':id']) }}";
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

            var menuSelect = $('#submenu');
            let images = "{{asset(':images')}}";
                images = images.replace(':images',response.image);


            $("#idUpdate").val(response.idPartership);
            $("#title").val(response.title);
            $("#link").val(response.link);
            $("#status").val(response.status);
            $(".images").attr("src", images);
            $("#currentImage").val(response.image);
    
            // Display Modal
            $('#editModal').modal('show'); 
          }
        });
  
  }
  
  function performDelete(id)
  {
    let url = "{{ route('partnership.destroy') }}";
    swal({
            title: "Are you sure ??",
            text: "Once Request, you will not be able to revert this record!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            
            $.ajax({
              type:'POST',
              url:url,
              data:{
                "_token": "{{ csrf_token() }}",
                id : id,
              },
              success:function(data){
                location.reload(true);
                swal("Success! Record has been added!", { icon: "success", });
  
              },error: function (xhr, ajaxOptions, thrownError) {
  
                swal("Error adding!", "Please try again", { icon: "success", });
  
              }
            });
  
          } else {
            swal("Operation Canceled!");
          }
        });
  }

//image validation
$(".imagesUpload").change(function(e) {
    var file = this.files[0];
    var preview = $(".output");
    var inputFile = $(".imagesUpload");
    var dimension = [];
        dimension['width'] = 200;
        dimension['height'] = 200;
    image_validation(file,preview,inputFile,dimension)

});

$('.modal').on('hidden.bs.modal', function (e) {
  $(".output").attr("src", '');
})
</script>
@endpush