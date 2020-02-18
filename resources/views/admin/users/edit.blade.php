@extends('admin.main')

@section('title')
    <title>Edit Users</title>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
      <h1>Edit Users</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="#">Home</a></div>
        <div class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></div>
        <div class="breadcrumb-item active"><a href="#">Edit</a></div>
      </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" name="name" 
                                    value="{{ $user->name }}"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid':'' }}" required>
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" 
                                    value="{{ $user->email }}"
                                    class="form-control {{ $errors->has('email') ? 'is-invalid':'' }}" 
                                    required readonly>
                                <p class="text-danger">{{ $errors->first('email') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" 
                                    class="form-control {{ $errors->has('password') ? 'is-invalid':'' }}">
                                <p class="text-danger">{{ $errors->first('password') }}</p>
                                <p class="text-warning">Biarkan kosong, jika tidak ingin mengganti password</p>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-sm">
                                    <i class="fa fa-send"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection