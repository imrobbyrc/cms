@extends('admin.main')
@section('title')
    <title>Header Content</title>
@endsection

@section('content')
<div class="section">
<div class="section-header">
    <h1>Header Content</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Homepage</a></div>
      <div class="breadcrumb-item">Header Content</div>
    </div>
</div>
  <div class="card">
    <div class="card-header">
      <h4 class="d-inline">Header List</h4>
      <div class="card-header-action">
        <a class="btn btn-primary" href="{{route('homepage.create',request()->segment(3))}}">Add New</a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table " id="slider">
          <thead>
            <tr>
              <th>#</th>
              <th>Browser Icon</th>
              <th>Header Logo</th>
              <th>Content Left</th>
              <th>Content Right</th>
              <th>Title</th>
              <!--<th>Description</th>-->
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
        <div class="modal-body">
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
            { width: "100px", targets: 1 },
            { width: "100px", targets: 2 },
            { width: "200px", targets: 4 },
            { width: "200px", targets: 5 }
        ],
        searchDelay: 600,
        ajax: url,
        columns: [
            { data: 'idHeader',name:'idHeader',render: function (data, type, row, meta) 
              {
                  return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            { data: 'browserIcon',name: 'browserIcon',"searchable": false},
            { data: 'headerLogo',name: 'headerLogo',"searchable": false},
            { data: 'contentLeft',name: 'contentLeft',"searchable": false},
            { data: 'contentRight',name: 'contentRight',"searchable": false},
            { data: 'browserTitle',name: 'browserTitle'},
            // { data: 'metaDescription',name: 'metaDescription'},
            { data: 'created_at',name: 'created_at',"searchable": false},
            { data: 'updated_at',name: 'updated_at',"searchable": false},
            { data: 'idHeader',name: 'idHeader',"searchable": false,
              render: function(data) { 
                  let link = "{{ route('homepage.edit',[':alias',':id'])}}";
                      link = link.replace(':alias', alias);
                      link = link.replace(':id',data);
                return '<a class="btn btn-primary btn-action mr-1" href="'+link+'"><i class="fas fa-pencil-alt"></i></a> <a class="btn btn-danger btn-action trigger--fire-modal-1" onclick="performDelete('+data+')"><i class="fas fa-trash"></i></a>'

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