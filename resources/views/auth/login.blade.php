@extends('layouts/app')

@section('contents')
<div class="main_content_iner" style="min-height: calc(100vh - 200px); display: flex; align-items: center;">
    <div class="container-fluid p-0" style="width: 100%;">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class=" mb_30">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <!-- sign_in  -->
                            <div class="modal-content cs_modal">
                                <div class="modal-header justify-content-center theme_bg_1">
                                    <h5 class="modal-title text_white">Log in</h5>
                                </div>
                                <div class="modal-body">
                                    <form id="loginForm">
                                        <div class="">
                                            <input type="text" id="userEmail" class="form-control" placeholder="Enter your email">
                                        </div>
                                        <div class="mt-3">
                                            <input type="password" id="userPassword" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" id="loginBtn" class="btn_1 full_width text-center">Log in</button>                                        </div>
                                        <p class="mt-3">Need an account? <a href="{{route('register')}}"> Sign Up</a></p>
                                        <div class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#forgot_password" data-bs-dismiss="modal" class="pass_forget_btn">Forget Password?</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- footer part -->
<div class="footer_part">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer_iner text-center">
                    <p>2020 © Influence - Designed by <a href="#"> <i class="ti-heart"></i> </a><a href="#"> Dashboard</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                const email = $('#userEmail').val();
                const password = $('#userPassword').val();
                console.log('Email:', email);

                $.ajax({
                    url: '{{ route("login") }}',
                    method: 'POST',
                    data: {
                        email: email,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Handle successful login (e.g., redirect to dashboard)
                        window.location.href = '{{ route("dashboard") }}';
                    },
                    error: function(xhr) {
                        // Handle login error (e.g., show error message)
                        alert('Login failed. Please check your credentials and try again.');
                    }
                });
            });
        });
    </script>
    

    
@endpush
@endsection