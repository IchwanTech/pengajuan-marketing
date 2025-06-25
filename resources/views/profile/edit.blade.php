@extends('layouts.parent-layout')

@section('page-title', 'Profile')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Bagian Edit Profile -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-center align-items-center">
                            <p class="mt-2 mb-2">Edit Profile</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <!-- Username -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $user->name) }}" required>
                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}" required>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary btn-sm w-100 mt-4 mb-0">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <p class="mt-2 mb-2">Update Password</p>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label class="form-control-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-4 mb-0">Update Password</button>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <p class="mt-2 mb-2 text-danger">Delete Account</p>
                    </div>
                    <div class="card-body">
                        <p class="text-sm">Once your account is deleted, all of its resources and data will be permanently
                            deleted.</p>
                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')
                            <div class="form-group">
                                <label class="form-control-label">Enter Password to Confirm</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100 mt-4 mb-0">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
