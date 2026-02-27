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
{{-- <div class="footer_part">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer_iner text-center">
                    <p>2020 © Influence - Designed by <a href="#"> <i class="ti-heart"></i> </a><a href="#"> Dashboard</a></p>
                </div>
            </div>
        </div>
    </div>
</div> --}}

@push('scripts')

   <!-- Include Toastr CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('Processing...');
        
        // প্রথমে CSRF টোকেন রিফ্রেশ করুন
        refreshCsrfToken().then(function(newToken) {
            // নতুন টোকেন দিয়ে লগইন রিকোয়েস্ট পাঠান
            return submitLoginForm(newToken);
        }).then(function(response) {
            // সফল লগইন
            toastr.success('Login successful! Redirecting...');
            setTimeout(function() {
                window.location.href = response.redirect;
            }, 1000);
        }).catch(function(xhr) {
            // এরর হ্যান্ডলিং
            submitBtn.prop('disabled', false).text('Login');
            handleLoginError(xhr);
        });
    });
    
    // CSRF টোকেন রিফ্রেশ ফাংশন
    function refreshCsrfToken() {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: '/csrf-token-refresh', // নতুন রুট
                method: 'GET',
                success: function(response) {
                    // মেটা ট্যাগ আপডেট করুন
                    $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    
                    // Ajax সেটআপ আপডেট করুন
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': response.csrf_token
                        }
                    });
                    
                    resolve(response.csrf_token);
                },
                error: function(xhr) {
                    reject(xhr);
                }
            });
        });
    }
    
    // লগইন ফর্ম সাবমিট ফাংশন
    function submitLoginForm(csrfToken) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: '{{ route("login") }}',
                method: 'POST',
                data: {
                    email: $('#userEmail').val(),
                    password: $('#userPassword').val()
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr) {
                    reject(xhr);
                }
            });
        });
    }
    
    // এরর হ্যান্ডলিং ফাংশন
    function handleLoginError(xhr) {
        let errorMessage = 'Login failed. Please check your credentials.';
        
        if (xhr.status === 419) {
            errorMessage = 'Session expired. Refreshing token...';
            toastr.warning(errorMessage);
            
            setTimeout(function() {
                $('#loginForm').submit();
            }, 1000);
            return;
        }
        
        if (xhr.responseJSON) {
            if (xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = errors.email ? errors.email[0] : 
                             (errors.password ? errors.password[0] : errorMessage);
            }
        }
        
        toastr.error(errorMessage);
    }
});
</script>
    

    
@endpush
@endsection