<!-- Add New User Modal -->
<div class="modal fade" id="addcategory" tabindex="-1" aria-labelledby="addcategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header theme_bg_1">
                <h5 class="modal-title text_white" id="addcategoryModalLabel">
                    <i class="ti-user"></i> Create New User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                
                <div class="modal-body">
                    <!-- Error Alert -->
                    <div class="alert alert-danger d-none" id="modalError"></div>
                    
                    <!-- Success Alert -->
                    <div class="alert alert-success d-none" id="modalSuccess"></div>
                    
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="Enter full name"
                                   required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="Enter email address"
                                   required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>

                        <!-- Password Field -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="ti-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirm password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="ti-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="passwordConfirmError"></div>
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <!-- Role Field -->
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">User Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="user">User</option>
                                <option value="editor">Editor</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password Strength Indicator -->
                    <div class="password-strength mb-3 d-none">
                        <label class="form-label">Password Strength</label>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <small id="passwordStrengthText" class="text-muted"></small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti-close"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="ti-save"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
$(document).ready(function() {
    
    // Toastr Configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "5000"
    };

    // Reset form when modal is closed
    $('#addcategory').on('hidden.bs.modal', function() {
        $('#createUserForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        $('#modalError').addClass('d-none').html('');
        $('#modalSuccess').addClass('d-none').html('');
        $('.password-strength').addClass('d-none');
        $('#passwordStrength').css('width', '0%');
    });

    // Toggle Password Visibility
    $('#togglePassword').click(function() {
        var passwordField = $('#password');
        var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('ti-eye ti-eye-off');
    });

    $('#toggleConfirmPassword').click(function() {
        var passwordField = $('#password_confirmation');
        var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        $(this).find('i').toggleClass('ti-eye ti-eye-off');
    });

    // Password Strength Checker
    $('#password').on('keyup', function() {
        var password = $(this).val();
        
        if(password.length > 0) {
            $('.password-strength').removeClass('d-none');
            
            var strength = 0;
            var strengthText = '';
            var strengthColor = '';
            
            // Length check
            if(password.length >= 8) strength += 25;
            
            // Uppercase check
            if(/[A-Z]/.test(password)) strength += 25;
            
            // Number check
            if(/[0-9]/.test(password)) strength += 25;
            
            // Special character check
            if(/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 25;
            
            if(strength <= 25) {
                strengthText = 'Weak';
                strengthColor = 'danger';
            } else if(strength <= 50) {
                strengthText = 'Fair';
                strengthColor = 'warning';
            } else if(strength <= 75) {
                strengthText = 'Good';
                strengthColor = 'info';
            } else {
                strengthText = 'Strong';
                strengthColor = 'success';
            }
            
            $('#passwordStrength').css('width', strength + '%')
                .removeClass('bg-danger bg-warning bg-info bg-success')
                .addClass('bg-' + strengthColor);
            $('#passwordStrengthText').text('Password strength: ' + strengthText);
            
        } else {
            $('.password-strength').addClass('d-none');
        }
        
        checkPasswordMatch();
    });

    // Password Match Check
    $('#password, #password_confirmation').on('keyup', function() {
        checkPasswordMatch();
    });

    function checkPasswordMatch() {
        var password = $('#password').val();
        var confirm = $('#password_confirmation').val();
        
        if(confirm.length > 0) {
            if(password === confirm) {
                $('#password_confirmation').removeClass('is-invalid').addClass('is-valid');
                $('#passwordConfirmError').html('');
            } else {
                $('#password_confirmation').removeClass('is-valid').addClass('is-invalid');
                $('#passwordConfirmError').html('Passwords do not match');
            }
        }
    }

    // Email Availability Check
    var emailCheckTimer;
    $('#email').on('keyup', function() {
        clearTimeout(emailCheckTimer);
        var email = $(this).val();
        
        if(email.length > 5) {
            emailCheckTimer = setTimeout(function() {
                checkEmailAvailability(email);
            }, 500);
        }
    });

    function checkEmailAvailability(email) {
        $.ajax({
            url: '{{ route("users.check-email") }}',
            type: 'POST',
            data: {
                email: email,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.exists) {
                    $('#email').addClass('is-invalid');
                    $('#emailError').html('This email is already taken');
                } else {
                    $('#email').removeClass('is-invalid');
                    $('#emailError').html('');
                }
            }
        });
    }

    // Form Submission
    $('#createUserForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').html('');
        $('#modalError').addClass('d-none').html('');
        
        // Client-side validation
        var isValid = true;
        var formData = new FormData(this);
        
        var name = $('#name').val().trim();
        var email = $('#email').val().trim();
        var password = $('#password').val();
        var passwordConfirm = $('#password_confirmation').val();
        
        if(!name) {
            $('#name').addClass('is-invalid');
            $('#nameError').html('Name is required');
            isValid = false;
        }
        
        if(!email) {
            $('#email').addClass('is-invalid');
            $('#emailError').html('Email is required');
            isValid = false;
        }
        
        if(!password) {
            $('#password').addClass('is-invalid');
            $('#passwordError').html('Password is required');
            isValid = false;
        }
        
        if(password !== passwordConfirm) {
            $('#password_confirmation').addClass('is-invalid');
            $('#passwordConfirmError').html('Passwords do not match');
            isValid = false;
        }
        
        if(!isValid) {
            return false;
        }
        
        // Show loading state
        $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Creating...');
        
        // AJAX submission
        $.ajax({
            url: '{{ route("users.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modalSuccess').removeClass('d-none').html('User created successfully!');
                $('#createUserForm')[0].reset();
                
                setTimeout(function() {
                    $('#addcategory').modal('hide');
                    toastr.success('User created successfully!');
                    location.reload(); // Refresh the page to show new user
                }, 1000);
            },
            error: function(xhr) {
                $('#submitBtn').prop('disabled', false).html('<i class="ti-save"></i> Create User');
                
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    
                    $.each(errors, function(field, messages) {
                        var input = $('#' + field);
                        input.addClass('is-invalid');
                        $('#' + field + 'Error').html(messages[0]);
                    });
                    
                    toastr.error('Please fix the validation errors');
                    
                } else if(xhr.status === 419) {
                    $('#modalError').removeClass('d-none').html('Session expired. Please refresh the page.');
                } else {
                    var message = 'Error creating user. Please try again.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    $('#modalError').removeClass('d-none').html(message);
                    toastr.error(message);
                }
            }
        });
    });

    // Auto-focus first input when modal opens
    $('#addcategory').on('shown.bs.modal', function() {
        $('#name').focus();
    });

});
</script>
@endpush