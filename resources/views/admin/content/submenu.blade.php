@extends('admin.main')
@section('title')
    <title>Sub Menu</title>
@endsection

@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
@endpush

@section('content')
<div class="section">
<div class="section-header">
    <h1>Sub Menu</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Sub Menu</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Sub Menu List</h4>
      <div class="card-header-action">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add New</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="submenu">
          <thead>
            <tr>
              <th>#</th>
              <th>Menu</th>
              <th>Sub Menu</th>
              <th>Title</th>
              <th>Description</th>
              <th>Link</th>
              <th>Image</th>
              <th>Status</th>
              <th>Layout</th>
              <th>Priority</th>
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
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('content.store',request()->segment(3))}}" enctype="multipart/form-data">
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
              <label>Menu</label>
              <select class="form-control" required="" name="menuId" onchange="cekMenu()">
                <option value="" disabled selected>Pilih Menu</option>
                @foreach ($data as $row)
                  <option value="{{$row->idMenus}}">{{$row->menu}}</option>
                @endforeach
              </select>
              <div class="invalid-feedback">
                Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Sub Menu</label>
              <input type="text" class="form-control" required="" name="submenus" value="{{ old('submenus') }}">
              <div class="invalid-feedback">
                Sub Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" required="" name="title" value="{{ old('title') }}">
              <div class="invalid-feedback">
                Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Description</label>
                <textarea id="dec" class="summernote-simple" required="" name="description" value="{{ old('description') }}"></textarea>
              <div class="invalid-feedback">
               Description Required
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
              <label>Image</label>
              <input type="file" accept="image/*" onchange="newFile(event)" name="image" id="image" required class="form-control">
              <div class="invalid-feedback">
                Image Required
              </div>
              <br>
              <img style="width:100%" src="" id="output"/>
              <script>
                var newFile = function(event) {
                    var newfile = document.getElementById('output');
                    newfile.src = URL.createObjectURL(event.target.files[0]);
                };
              </script>
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
              <label>Layout</label>
              <select class="form-control" required="" name="layout">
                <option value="2">2</option>
                <option value="1" selected="selected">1</option>
              </select>
              <div class="invalid-feedback">
                Layout Required
              </div>
            </div>
            <div class="form-group">
              <label>Priority</label>
              <input type="number" class="form-control" required="" name="priority" value="{{ old('priority') }}">
              <div class="invalid-feedback">
                Priority Required
              </div>
            </div>
            <div class="form-group">
              <label>Browser Title</label>
              <input type="text" class="form-control" required="" name="browserTitle" value="{{ old('browserTitle') }}">
              <div class="invalid-feedback">
              Browser Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Meta Description</label>
                <textarea id="metaDec" class="summernote-simple" required="" name="metaDescription" value="{{ old('metaDescription') }}"></textarea>
              <div class="invalid-feedback">
              Meta Description Required
              </div>
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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('content.update',request()->segment(3))}}" enctype="multipart/form-data">
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
              <label>Menu</label>
              <select class="form-control" required="" name="menuId" onchange="cekMenu()" id="menuId">
                <option value="" disabled selected>Pilih Menu</option>
                @foreach ($data as $row)
                  <option value="{{$row->idMenus}}">{{$row->menu}}</option>
                @endforeach
              </select>
              <div class="invalid-feedback">
                Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Sub Menu</label>
              <input type="text" class="form-control" required="" name="submenus" value="{{ old('submenus') }}" id="submenus">
              <div class="invalid-feedback">
                Sub Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" required="" name="title" value="{{ old('title') }}" id="title">
              <div class="invalid-feedback">
                Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Description</label>
                <textarea id="description" class="summernote-simple" required="" name="description" value="{{ old('description') }}"></textarea>
              <div class="invalid-feedback">
               Description Required
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
              <label>Image</label>
              <input type="file" accept="image/*" onchange="newFile(event)" name="image" id="image" class="form-control">
              <div class="invalid-feedback">
                Image Required
              </div>
              <br>
              <img style="width:100%" src="" id="output" class="images"/>
              <script>
                var newFile = function(event) {
                    var newfile = document.getElementById('output');
                    newfile.src = URL.createObjectURL(event.target.files[0]);
                };
              </script>
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
              <label>Layout</label>
              <select class="form-control" required="" name="layout" id="layout">
                <option value="2">2</option>
                <option value="1" selected="selected">1</option>
              </select>
              <div class="invalid-feedback">
                Layout Required
              </div>
            </div>
            <div class="form-group">
              <label>Priority</label>
              <input type="number" class="form-control" required="" name="priority" value="{{ old('priority') }}" id="priority">
              <div class="invalid-feedback">
                Priority Required
              </div>
            </div>
            <div class="form-group">
              <label>Browser Title</label>
              <input type="text" class="form-control" required="" name="browserTitle" value="{{ old('browserTitle') }}" id="browserTitle">
              <div class="invalid-feedback">
              Browser Title Required
              </div>
            </div>
            <div class="form-group">
              <label>Meta Description</label>
                <textarea id="metaDescription" class="summernote-simple" required="" name="metaDescription" value="{{ old('metaDescription') }}"></textarea>
              <div class="invalid-feedback">
              Meta Description Required
              </div>
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
$(document).ready(function () {
    $(":input").not("[name=menuId]")
        .prop("disabled", true);
    $('#metaDec').summernote('disable');
    $('#dec').summernote('disable');
    $('#metaDescription').summernote('disable');
    $('#description').summernote('disable');
});

function cekMenu(){
    $(":input").not("[name=menuId]")
        .prop("disabled", false);
    $('#metaDec').summernote('enable');
    $('#dec').summernote('enable');
    $('#metaDescription').summernote('enable');
    $('#description').summernote('enable');
}
</script>
@endpush

@push('scripts')
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
<script>
  var alias = '{{request()->segment(3)}}';
  $(function() {
    
      let url = "{{ route('content.getdata', ':alias') }}";
          url = url.replace(':alias', alias);
  
        $('#submenu').DataTable({
          'processing': true,
          'serverSide': true,
          "deferRender": true,
          "info": true,
          "autoWidth": false,
          columnDefs: [
            { width: "150px", targets: 6 }
          ],
          searchDelay: 600,
          ajax: url,
          columns: [
              { data: 'idSubmenus',name:'idSubmenus',render: function (data, type, row, meta) 
                {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
              },
              { data: 'menu',name: 'menus.menu'},
              { data: 'submenus',name: 'submenus'},
              { data: 'title',name: 'title'},
              { data: 'description',name: 'description'},
              { data: 'link',name: 'link'},
              { data: 'image',name: 'image',"searchable": false},
              { data: 'status',name: 'status',"searchable": false},
              { data: 'layout',name: 'layout',"searchable": false},
              { data: 'priority',name: 'priority',"searchable": false},
              { data: 'created_at',name: 'created_at',"searchable": false},
              { data: 'updated_at',name: 'updated_at',"searchable": false},
              { data: 'idSubmenus',name: 'idSubmenus',"searchable": false,
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
    let url = "{{ route('content.show',[':alias',':id']) }}";
          url = url.replace(':alias', alias);
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
            let images = "{{asset(':images')}}";
                images = images.replace(':images',response.image);

            $("#idUpdate").val(response.idSubmenus);
            $("#title").val(response.title);
            $("#submenus").val(response.submenus);
            $("#description").summernote('code',response.description);
            $("#link").val(response.link);
            $("#status").val(response.status);
            $("#priority").val(response.priority);
            $("#browserTitle").val(response.browserTitle);
            $("#metaDescription").summernote('code',response.metaDescription);
            $(".images").attr("src", images);
            $("#currentImage").val(response.image)

            
            // Display Modal
            $('#editModal').modal('show'); 
          }
        });
  
  }
  
  function performDelete(id)
  {
    let url = "{{ route('content.destroy', ':alias') }}";
        url = url.replace(':alias', alias);
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
</script>
@endpush