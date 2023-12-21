@include('admin.layouts.css')
@include('admin.layouts.js')


<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 ">



                        @if (Session::has('success'))
                            <div class="alert alert-success">{{ Session::get('success') }}</div>
                        @endif
                        @if (Session::has('fail'))
                            <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                        @endif



                        <form action="{{ route('auth.login.perform') }}" method="POST">
                            @csrf
                            <h3 class="mb-5 text-center">Sign in</h3>

                            <div class="form-outline mb-4">
                                <label class="form-label" for="email">email</label>
                                <input type="text" name="email" id="email" value="{{ old('email') }}"
                                    class="form-control form-control-lg email" />
                                    <span class="text-danger">
                                        @error('email')
                                        {{$message}}
                                        @enderror
                                    </span>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control  form-control-lg " name="password" value="{{ old('password') }}"  autocomplete="current-password" required/>
                                    <span class="input-group-text cursor-pointer" id="toggle-password"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

</script>
