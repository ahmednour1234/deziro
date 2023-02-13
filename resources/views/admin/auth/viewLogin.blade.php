@include('admin.layouts.css')
@include('admin.layouts.js')


<section class="vh-100">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 ">



                        <form action="home" method="POST">
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

                            <div class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" value="{{ old('password') }}"
                                    class="form-control form-control-lg password " />
                                    <span class="text-danger">
                                        @error('password')
                                        {{$message}}
                                        @enderror
                                    </span>
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
