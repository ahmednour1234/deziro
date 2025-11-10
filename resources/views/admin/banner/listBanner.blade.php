@extends('admin.layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Banners</h4>


    <div id="success_message"></div>

    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-4  mx-3">
                <form action="" method="get" id="searchForm">
                    <div class="d-flex gap-3">
                        <div class=" col-lg-3 input-group input-group-merge">
                            <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>
                        </div>
                    </div>
                </form>
            </div>
            <div class="m-3 d-flex gap-2">
                <ul class="pagination    ">
                    <li class="">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">{{ currentLimit() }}</button>
                            <ul class="dropdown-menu" style="min-width: auto;">
                                @foreach (limits() as $limit)
                                    <li><a class="dropdown-item {{ $limit['active'] ? 'active' : '' }}"
                                            href="{{ $limit['url'] }}">{{ $limit['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="table-responsive text-nowrap">
            <nav class="nav-pagination" aria-label="Page navigation">
                <div class="row mb-0">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listBanner->firstItem() }} to {{ $listBanner->lastItem() }}
                            of total {{ $listBanner->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                        <th>
                            Created at
                        </th>

                        <th>
                            Name
                        </th>

                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach ($listBanner as $key => $banner)
                        <tr>
                            <td>{{ $banner->created_at->format('Y-m-d ') }}</td>
                            <td>{{ $banner->name }} </td>


                            <td>
                                @if ($banner->id == 1)
                                    <a href="/bannerImage/{{ $banner->id }}" value="{{ $banner->id }}"
                                        class="edit_banner  btn btn-warning editbtn btn-sm ">Manage Images</a>
                                @else
                                    <a href="/bannerImage/{{ $banner->id }}" value="{{ $banner->id }}"
                                        class="edit_banner  btn btn-warning editbtn btn-sm ">Manage Images</a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listBanner->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection
