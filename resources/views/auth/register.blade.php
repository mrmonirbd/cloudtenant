{{-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}



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
                                    <h5 class="modal-title text_white">Create an Account</h5>
                                </div>
                                <div class="modal-body">
                                    <form id="register">
                                        <div class="">
                                            <input type="text" id="userName" class="form-control" placeholder="Enter your name">
                                        </div>
                                        <div class="">
                                            <input type="email" id="userEmail" class="form-control" placeholder="Enter your email">
                                        </div>
                                        <div class="mt-3">
                                            <input type="password" id="userPassword" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" id="registerBtn" class="btn_1 full_width text-center">Register</button>                                        </div>
                                        <p class="mt-3"><a href="{{route('login')}}"> Already registered?</a></p>
                                        {{-- <div class="text-center">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#forgot_password" data-bs-dismiss="modal" class="pass_forget_btn">Forget Password?</a>
                                        </div> --}}
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

    <script>
        $(document).ready(function() {

            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "5000"
            };

            // input এ value দিলে is-invalid remove হবে
            $('#userName, #userEmail, #userPassword').on('input', function() {
                if($(this).val().trim() !== '') {
                    $(this).removeClass('is-invalid');
                }
            });

            $('#register').on('submit', function(e) {
                e.preventDefault();

                // Validation
                if(!$('#userName').val().trim()){
                    $('#userName').addClass('is-invalid');
                    toastr.error('Name is required.');
                    return;
                }

                if(!$('#userEmail').val().trim()){
                    $('#userEmail').addClass('is-invalid');
                    toastr.error('Email is required.');
                    return;
                }

                if(!$('#userPassword').val().trim()){
                    $('#userPassword').addClass('is-invalid');
                    toastr.error('Password is required.');
                    return;
                }

                // AJAX
                $.ajax({
                    url: '{{ route("register") }}',
                    type: 'POST',
                    data: {
                        name: $('#userName').val(),
                        email: $('#userEmail').val(),
                        password: $('#userPassword').val(),
                        password_confirmation: $('#userPassword').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#register input').val(''); // placeholder থাকবে
                        toastr.success('Registration successful! Waiting for admin approval.');
                    },
                    error: function(xhr) {
                        if(xhr.status === 422){
                            toastr.error('Validation error. Please check your inputs.');
                        } else {
                            toastr.error('Registration failed. Please try again.');
                        }
                    }
                });

            });

        });
        </script>
    

    
@endpush
@endsection
