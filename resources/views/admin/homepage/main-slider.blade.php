@extends('admin.main')
@section('title')
    <title>main-slider</title>
@endsection

@section('content')
<div class="section">
<div class="section-header">
    <h1>Main Slider</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Main Slider</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Slider List</h4>
      <div class="card-header-action">
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add New</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="slider">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Title</th>
              <th>Link</th>
              <th>Priority</th>
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

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('homepage.store',request()->segment(3))}}" enctype="multipart/form-data">
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
              <label>Priority</label>
              <input type="number" class="form-control" required="" name="priority" value="{{ old('priority') }}">
              <div class="invalid-feedback">
                Priority Required
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
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Modal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="needs-validation" novalidate="" method="post" action="{{ route('homepage.update',request()->segment(3))}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" class="form-control" required="" name="idUpdate" value="" id="idUpdate" readonly>
        <input type="hidden" class="form-control" required="" name="currentImage" value="" id="currentImage" readonly>
        <div class="modal-body">
          <div class="card-body">
            <div class="form-group">
              <label>Title</label>
              <input type="text" class="form-control" required="" name="title" value="" id="title">
            </div>
            <div class="form-group">
              <label>Link</label>
              <input type="text" class="form-control" required="" name="link" value="" id="link">
            </div>
            <div class="form-group">
              <label>Priority</label>
              <input type="number" class="form-control" required="" name="priority" value="" id="priority">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" required="" name="status" id="status">
                <option value="active">Active</option>
                <option value="inactive" selected="selected">Inactive</option>
              </select>
            </div>
            <div class="form-group">
              <label>Image</label>
              <input type="file" accept="image/*" onchange="loadFile(event)" name="image" class="form-control">
              <br>
              <img id="output_edit" style="width:100%" src="" class="images"/>
              <script>
                var loadFile = function(event) {
                    var output = document.getElementById('output_edit');
                    output.src = URL.createObjectURL(event.target.files[0]);
                };
              </script>
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
var alias = '{{request()->segment(3)}}';
$(function() {
  
    let url = "{{ route('homepage.getdata', ':alias') }}";
        url = url.replace(':alias', alias);

      $('#slider').DataTable({
        'processing': true,
        'serverSide': true,
        "deferRender": true,
        "info": true,
        "autoWidth": false,
        columnDefs: [
            { width: "150px", targets: 1 }
        ],
        searchDelay: 600,
        ajax: url,
        columns: [
            { data: 'idMainSlider',name:'idMainSlider',render: function (data, type, row, meta) 
              {
                  return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            { data: 'image',name: 'image',"searchable": false},
            { data: 'title',name: 'title'},
            { data: 'link',name: 'link'},
            { data: 'priority',name: 'priority',"searchable": false},
            { data: 'status',name: 'status',"searchable": false},
            { data: 'created_at',name: 'created_at',"searchable": false},
            { data: 'updated_at',name: 'updated_at',"searchable": false},
            { data: 'idMainSlider',name: 'idMainSlider',"searchable": false,
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
  let url = "{{ route('homepage.show',[':alias',':id']) }}";
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
          $("#idUpdate").val(response.idMainSlider)
          $("#title").val(response.title);
          $("#link").val(response.link);
          $("#priority").val(response.priority);
          $("#status").val(response.status);
          $(".images").attr("src", images);
          $("#currentImage").val(response.image)
          
          // Display Modal
          $('#editModal').modal('show'); 
        }
      });

}

function performDelete(id)
{
  let url = "{{ route('homepage.destroy', ':alias') }}";
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