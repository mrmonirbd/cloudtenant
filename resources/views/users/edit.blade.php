@extends('layouts/app_auth')

@section('contents')
<div class="col-12">
    <div class="QA_section">

        <!-- Flash messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true">Basic Info</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab" aria-controls="education" aria-selected="false">Education</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">Skills</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab" aria-controls="permissions" aria-selected="false">Permissions</button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabContent">
            <!-- Basic Info -->
           <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                <div class="card p-3">
                    <h5>Update Staff Information</h5>
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Picture -->
                        <div class="mb-4 d-flex align-items-center">
                            <div class="me-3 position-relative">
                                <img id="profilePreview" 
                                    src="{{ $user->profile_photo_url ?? asset('assets/img/image.png') }}" 
                                    alt="Profile Picture" 
                                    class="rounded-circle" 
                                    width="100" 
                                    height="100"
                                    style="object-fit: cover;">
                                {{-- <span class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-25 text-white rounded-circle opacity-0 hover-overlay" 
                                    style="cursor:pointer;">
                                    Change Photo
                                </span> --}}
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" class="form-control d-none" id="profile_photo" name="profile_photo" accept="image/*">
                            </div>
                        </div>



                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                         <div class="mb-3">
                                            <label for="employee_id" class="form-label">Employee ID</label>
                                            <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ $user->employee_id }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                         <div class="mb-3">
                                            <label for="designation" class="form-label">Designation</label>
                                            <input type="text" class="form-control" id="designation" name="designation" value="{{ $user->designation }}">
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">

                                    </div>
                                    <div class="col-md-6">

                                    </div> --}}
                                </div>
                                     
                                   
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control" id="department" name="department" value="{{ $user->department }}">
                                </div>
                                <div class="mb-3">
                                     {{-- <div class="row mb-3">
                                        <label class="form-label col-sm-3 col-form-label text-end f_w_500 f_s_15">Default</label>
                                        <div class="col-xl-5 col-sm-9">
                                        <div class="input-group common_date_picker">
                                            <input class="datepicker-here  digits" type="text" data-language="en">
                                        </div>
                                        </div>
                                    </div> --}}
                                    <label for="joining_date" class="form-label">Joining Date</label>
                                    <input type="text" class="form-control datepicker-here" id="joining_date" name="joining_date" value="{{ $user->joining_date }}">
                                </div>
                                <div class="mb-3">
                                    <label for="current_address" class="form-label">Current Address</label>
                                    <textarea class="form-control" id="current_address" name="current_address">{{ $user->current_address }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="permanent_address" class="form-label">Permanent Address</label>
                                    <textarea class="form-control" id="permanent_address" name="permanent_address">{{ $user->permanent_address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6>Emergency Contact</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emergency_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="emergency_name" name="emergency_name" value="{{ $user->emergency_name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="emergency_relation" class="form-label">Relation</label>
                                    <input type="text" class="form-control" id="emergency_relation" name="emergency_relation" value="{{ $user->emergency_relation }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emergency_phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" value="{{ $user->emergency_phone }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6>Bank Details (Optional)</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $user->bank_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="account_number" class="form-label">Account Number</label>
                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $user->account_number }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="{{ $user->ifsc_code }}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </form>
                </div>
            </div>
            <!-- Education -->
            <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                <div class="card p-3">
                    <h5>Education</h5>
                    {{-- @forelse($user->educations as $edu)
                        <p><strong>{{ $edu->degree }}</strong> from {{ $edu->institution }} ({{ $edu->year }})</p>
                    @empty
                        <p>No education records found.</p>
                    @endforelse --}}
                </div>
            </div>

            <!-- Skills -->
            <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                <div class="card p-3">
                    <h5>Skills</h5>
                    {{-- @if($user->skills && count($user->skills) > 0)
                        <ul>
                            @foreach($user->skills as $skill)
                                <li>{{ $skill->name }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>No skills added yet.</p>
                    @endif --}}
                </div>
            </div>

            <!-- Permissions -->
            <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                <div class="card p-3">
                   <h5>Set Permissions for {{ $user->name }}</h5>
                  <form action="" method="POST">
    @csrf
    @method('PUT')

    <ul>
        @php
            // dd($user->roles);
        @endphp

         @foreach(getmenu() as $menu)
            @if($menu->section == 'header')
                <li class="menu-header">{{ $menu->header_text }}</li>
            @else
                <li class="{{ $menu->children->count() > 0 ? 'has-submenu' : '' }}">
                    <div>
                        <input type="checkbox" name="permissions[]" value="{{ $menu->id }}"
                            @if(in_array($user->roles, ['owner', 'superadmin']))
                                checked
                            @else
                                {{ $user->permissions && $user->permissions->contains($menu->id) ? 'checked' : '' }}
                            @endif
                        >
                        @if($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endif
                        <span>{{ $menu->name }}</span>
                    </div>

                    @if($menu->children->count() > 0)
                        <ul style="margin-left: 20px;">
                            @foreach($menu->children as $child)
                                <li>
                                    <div>
                                        <input type="checkbox" name="permissions[]" value="{{ $child->id }}"
                                            @if(in_array($user->roles, ['owner', 'superadmin']))
                                                checked
                                            @else
                                                {{ $user->permissions && $user->permissions->contains($child->id) ? 'checked' : '' }}
                                            @endif
                                        >
                                        @if($child->icon)
                                            <i class="{{ $child->icon }}"></i>
                                        @endif
                                        <span>{{ $child->name }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
    </ul>

    <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
</form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: make the first tab active on page load (Bootstrap handles this by default)
    var triggerTabList = [].slice.call(document.querySelectorAll('#profileTab button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>
@endpush