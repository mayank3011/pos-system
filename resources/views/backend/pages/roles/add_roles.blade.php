@extends('admin_dashboard')

@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<div class="content">

<!-- Start Content-->
<div class="container-fluid">

<!-- Start Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Add Roles</a></li>
                    </ol>
                </div>
                <h4 class="page-title">Add Roles</h4>
            </div>
        </div>
    </div>     
<!-- End Page Title -->

<div class="row">
  <div class="col-lg-8 col-xl-8">
    <div class="card">
        <div class="card-body">

            <div class="tab-pane" id="settings">
                <form id="myForm" method="post" action="{{ route('roles.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Add Roles</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="role_name" class="form-label">Role Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                    </div> <!-- end row -->

                    <div class="text-end">
                        <button type="submit" class="btn btn-success waves-effect waves-light mt-2">
                            <i class="mdi mdi-content-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div> <!-- end card-->
  </div> <!-- end col -->
</div>
<!-- End Row -->

</div> <!-- Container -->

</div> <!-- Content -->

<script type="text/javascript">
$(document).ready(function (){
    $('#myForm').validate({
        rules: {
            name: {
                required: true,
            }, 
        },
        messages: {
            name: {
                required: 'Please Enter Role Name',
            }, 
        },
        errorElement: 'span', 
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});
</script>

@endsection
