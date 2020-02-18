@extends('admin.main')
@section('title')
    <title>Menu Content</title>
@endsection

@push('css') 
<link rel="stylesheet" href="{{ asset('admin_assets/modules/summernote/summernote-bs4.css')}}"> 
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
    <h1>Menu Content</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Menu Content</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Menu Content List</h4>
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
              <th>Sub Menu</th>
              <th>Menu Content</th>
              <th>Title</th>
              <th>Description</th>
              <th>Link</th>
              <th>Image</th>
              <th>Status</th>
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
<div class="modal fade" id="createModal" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
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
              <label>Sub Menu</label>
              <br>
              <select style="width:100%;" class="form-control js-example-basic-single" required="" name="submenuId"> 
              </select>
              <div class="invalid-feedback">
                Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Menu Content</label>
              <input type="text" class="form-control" required="" name="contents" value="{{ old('contents') }}">
              <div class="invalid-feedback">
                Menu Content Required
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
                <textarea class="summernote-simple" required="" name="description" value="{{ old('description') }}"></textarea>
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
              <input type="file" accept="image/*" onchange="newFile(event)" name="image" required class="form-control">
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
                <textarea class="summernote-simple" required="" name="metaDescription" value="{{ old('metaDescription') }}"></textarea>
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

<div class="modal fade" id="editModal" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
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
              <label>Sub Menu</label>
              <br>
              <select style="width:100%;" class="form-control js-example-basic-single" required="" name="submenuId" id="submenu"> 
              </select>
              <div class="invalid-feedback">
                Menu Required
              </div>
            </div>
            <div class="form-group">
              <label>Menu Content</label>
              <input type="text" class="form-control" required="" name="contents" value="{{ old('contents') }}" id="contents">
              <div class="invalid-feedback">
                Menu Content Required
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
                <textarea class="summernote-simple" required="" name="description" value="{{ old('description') }}" id="description"></textarea>
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
              <input type="file" accept="image/*" onchange="loadFile(event)" name="image" id="image" class="form-control">
              <div class="invalid-feedback">
                Image Required
              </div>
              <br>
              <img style="width:100%" src="" id="output_edit" class="images"/>
              <script>
                var loadFile = function(event) {
                    var file = document.getElementById('output_edit');
                    file.src = URL.createObjectURL(event.target.files[0]);
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
                <textarea class="summernote-simple" required="" name="metaDescription" value="{{ old('metaDescription') }}" id="metaDescription" ></textarea>
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
  function formatState (state) {
    if (!state.id) { return state.text; }

    let images = "{{asset(':images')}}";
        images = images.replace(':images',state.html);

    var $state = $(
    '<span ><img sytle="display: inline-block;" src="' + images + '"  style="width:10%"/> ' + state.text + '</span>'
    );

    return $state;
  }

  $('#submenu').select2({ 
    templateResult: formatState,
    allowClear: true,
    maximumSelectionLength: 6,
      ajax: {
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
        url: '{{route("ajax_get_all_submenu")}}',
        type: 'POST',
        dataType: 'json',
           data: function (params) {
              return {
                q: params.term
              };
           },
           processResults: function (response) { 
              return {
                 results: response
              }; 
           },
        cache: true
      }
  }); 
</script>
@endpush

@push('scripts')
<script src="{{ asset('admin_assets/modules/summernote/summernote-bs4.js')}}"></script>
<script>
  var alias = '{{request()->segment(3)}}';
  $(function() {
    
      let url = "{{ route('content.getdata', ':alias') }}";
          url = url.replace(':alias', alias);
  
        $('#menuContent').DataTable({
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
              { data: 'idContents',name:'idContents',render: function (data, type, row, meta) 
                {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
              },
              { data: 'submenus',name: 'submenus.submenus'},
              { data: 'contents',name: 'contents'},
              { data: 'title',name: 'title'},
              { data: 'description',name: 'description'},
              { data: 'link',name: 'link'},
              { data: 'image',name: 'image',"searchable": false},
              { data: 'status',name: 'status',"searchable": false},
              { data: 'priority',name: 'priority',"searchable": false},
              { data: 'created_at',name: 'created_at',"searchable": false},
              { data: 'updated_at',name: 'updated_at',"searchable": false},
              { data: 'idContents',name: 'idContents',"searchable": false,
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

            var menuSelect = $('#submenu');
            let images = "{{asset(':images')}}";
                images = images.replace(':images',response.image);


            $("#idUpdate").val(response.idContents);
            $("#title").val(response.title);
            $("#contents").val(response.contents);
            $("#description").summernote('code',response.description);
            $("#link").val(response.link);
            $("#status").val(response.status);
            $("#priority").val(response.priority);
            $("#browserTitle").val(response.browserTitle);
            $("#metaDescription").summernote('code',response.metaDescription);
            $(".images").attr("src", images);
            $("#currentImage").val(response.image);

            // create the option and append to Select2
            var option = new Option(response.submenus, response.submenuId, true, true);
            console.log(option)
            menuSelect.append(option).trigger('change');

            
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