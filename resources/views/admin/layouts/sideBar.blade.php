    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
            <a href="{{ route('admin.home.listHome') }}" class="app-brand-link">
                <h3 class="mx-5">Deziro</h3>
                {{--
                <div class="text-center mx-5 "> <img src="{{ asset('admin/assets/img/logo_img/1672497704.png') }}"
                        alt="" width="100"></div> --}}
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item   {{ request()->is('home') ? 'active' : '' }}">
                <a href="{{ route('admin.home.listHome') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-home"></i>
                    <div data-i18n="Analytics">Home</div>
                </a>
            </li>

            <!-- Admin -->
            <li class="menu-item  {{ request()->is('adminn') ? 'active' : '' }} ">
                <a href="{{ route('admin.admin.listAdmin') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-user-circle"></i>
                    <div data-i18n="Analytics">Admin</div>
                </a>
            </li>

            {{-- Stores --}}
            <li
                class="menu-item {{ request()->is('store') ||
                request()->is('createStore') ||
                request()->is('store') ||
                request()->is('user') ||
                request()->is('requestStore') ||
                request()->is('rejectedStore')
                    ? 'active'
                    : '' }}">
                <a class="menu-link menu-toggle ">
                    <i class="menu-icon tf-icons bx bxs-user-account"></i>
                    <div data-i18n="Layouts">Users</div>

                </a>

                <ul
                    class=" {{ request()->is('store') ||
                    request()->is('createStore') ||
                    request()->is('store') ||
                    request()->is('user') ||
                    request()->is('requestStore') ||
                    request()->is('rejectedStore')
                        ? 'active'
                        : 'menu-sub' }}">

                    <li class="menu-item {{ request()->is('requestStore') ? 'active' : '' }}">
                        <a href="{{ route('admin.store.listRequestStore') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Request Store</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('rejectedStore') ? 'active' : '' }}">
                        <a href="{{ route('admin.store.listRejectedStore') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Rejected Store</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('store') || request()->is('createStore') ? 'active' : '' }}">
                        <a href="{{ route('admin.store.listStore') }}" class="menu-link">
                            <div data-i18n="Container">List Store </div>
                        </a>
                    </li>


                    <li class="menu-item {{ request()->is('user') ? 'active' : '' }} ">
                        <a href="{{ route('admin.user.listUser') }}" class="menu-link">
                            <div data-i18n="Fluid">List User</div>
                        </a>
                    </li>

                </ul>
            </li>




            {{-- Category --}}
            <li class="menu-item   {{ request()->is('category') ? 'active' : '' }}">
                <a href="{{ route('admin.category.listCategory') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-category"></i>
                    <div data-i18n="Analytics">Category</div>
                </a>
            </li>
            {{-- End Categorye --}}

            <li class="menu-item  {{ request()->is('requesttochangecategories') ? 'active' : '' }} ">
                <a href="{{ route('admin.category.listrequesttochangecategories') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-category"></i>
                    <div data-i18n="Analytics">List Request to change category</div>
                </a>
            </li>

            <li class="menu-item   {{ request()->is('brand') ? 'active' : '' }}">
                <a href="{{ route('admin.brand.listBrand') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-category"></i>
                    <div data-i18n="Analytics">Brand</div>
                </a>
            </li>


            <li class="menu-item   {{ request()->is('banner') ? 'active' : '' }}">
                <a href="{{ route('admin.banner.listBanner') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-image-add"></i>
                    <div data-i18n="Analytics">Banner</div>
                </a>
            </li>


            <li class="menu-item   {{ request()->is('notification') ? 'active' : '' }}">
                <a href="{{ route('admin.notification.listNotification') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bxs-bell-ring"></i>
                    <div data-i18n="Analytics">Notification</div>
                </a>
            </li>


            {{--  Products --}}
            <li
                class="menu-item {{ request()->is('storeProduct') || request()->is('featuredProducts') ? 'active' : '' }}">
                <a class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxl-product-hunt"></i>
                    <div data-i18n="Layouts"> Products</div>
                </a>

                <ul
                    class="{{ request()->is('storeProduct') || request()->is('featuredProducts') ? 'active' : 'active menu-sub' }}">

                    <li class="menu-item {{ request()->is('storeProduct') ? 'active' : '' }} ">
                        <a href="{{ route('admin.storeProduct.listStoreProduct') }}" class="menu-link">
                            <div data-i18n="Container">List Store Products </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('featuredProducts') ? 'active' : '' }} ">
                        <a href="{{ route('admin.product.listFeaturedProducts') }}" class="menu-link">
                            <div data-i18n="Container">List Featured Products </div>
                        </a>
                    </li>






                </ul>
            </li>


            {{--  Orders --}}
            <li class="menu-item {{ request()->is('order') ? 'active' : '' }}">
                <a class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-cart-download"></i>
                    <div data-i18n="Layouts"> Orders</div>
                </a>

                <ul class="{{ request()->is('order') ? 'active' : 'active menu-sub' }}">
                    <li class="menu-item {{ request()->is('order') ? 'active' : '' }} ">
                        <a href="{{ route('admin.order.listOrder') }}" class="menu-link">
                            <div data-i18n="Container">List Orders </div>
                        </a>
                    </li>

                </ul>
            </li>

            {{-- <li class="menu-item {{ request()->is('category')  ? 'active' : '' }} ">
                    <a href="{{ route('admin.category.listCategory') }}" class="menu-link">
                        <div data-i18n="Without navbar">List Sub Categories </div>
                    </a>
                </li> --}}

            {{-- </ul>
        </li> --}}


            {{--  Individual
            <li
                class="menu-item {{ request()->is('activeIndividuals') || request()->is('inactiveIndividuals') ? 'active' : '' }}">
                <a class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div data-i18n="Layouts">Individuals</div>
                </a>

                <ul
                    class="{{ request()->is('activeIndividuals') || request()->is('inactiveIndividuals') ? 'active' : 'menu-sub' }}">
                    <li class="menu-item {{ request()->is('activeIndividuals') ? 'active' : '' }}">
                        <a href="{{ route('admin.individual.listActiveIndividuals') }}" class="menu-link">
                            <div data-i18n="Without menu">List Individual Active</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('inactiveIndividuals') ? 'active' : '' }}">
                        <a href="{{ route('admin.individual.listinactiveIndividuals') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Individual Inactive</div>
                        </a>
                    </li>

                </ul>
            </li>







            <!-- Sub Category -->
            <li class="menu-item   {{ request()->is('category') ? 'active' : '' }}">
                <a href="{{ route('admin.category.listCategory') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-user-circle"></i>
                    <div data-i18n="Analytics">Sub Category</div>
                </a>
            </li>

             Individual Products
            <li
                class="menu-item {{ request()->is('product') ||
                request()->is('rejectedProduct') ||
                request()->is('sellingProduct') ||
                request()->is('bidProduct') ||
                request()->is('swapProduct')
                    ? 'active'
                    : '' }}">
                <a class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">Individual Products</div>
                </a>

                <ul
                    class="{{ request()->is('product') ||
                    request()->is('rejectedProduct') ||
                    request()->is('sellingProduct') ||
                    request()->is('bidProduct') ||
                    request()->is('swapProduct')
                        ? 'active'
                        : 'active menu-sub' }}">

                    <li class="menu-item {{ request()->is('product') ? 'active' : '' }} ">
                        <a href="{{ route('admin.product.listRequestProduct') }}" class="menu-link">
                            <div data-i18n="Without menu">List Product Request</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('rejectedProduct') ? 'active' : '' }} ">
                        <a href="{{ route('admin.product.listRejectedProduct') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Product Rejected</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('sellingProduct') ? 'active' : '' }} ">
                        <a href="{{ route('admin.product.listSellingProduct') }}" class="menu-link">
                            <div data-i18n="Container">List Selling Product </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('bidProduct') ? 'active' : '' }} ">
                        <a href="{{ route('admin.product.listBidProduct') }}" class="menu-link">
                            <div data-i18n="Fluid">List Bid Product </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('swapProduct') ? 'active' : '' }} ">
                        <a href="{{ route('admin.product.listSwapProduct') }}" class="menu-link">
                            <div data-i18n="Fluid">List Swap Product </div>
                        </a>
                    </li>

                </ul>
            </li>


             Orders
            <li
                class="menu-item {{ request()->is('pendingOrder') ||
                request()->is('shippingOrder') ||
                request()->is('canceledOrder') ||
                request()->is('deliverydOrder')
                    ? 'active'
                    : '' }}">
                <a class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">Orders</div>
                </a>

                <ul
                    class="{{ request()->is('pendingOrder') ||
                    request()->is('shippingOrder') ||
                    request()->is('canceledOrder') ||
                    request()->is('deliverydOrder')
                        ? 'active'
                        : 'active menu-sub' }}">

                    <li class="menu-item {{ request()->is('pendingOrder') ? 'active' : '' }} ">
                        <a href="{{ route('admin.order.listPendingOrder') }}" class="menu-link">
                            <div data-i18n="Without menu">List Pending Order </div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('shippingOrder') ? 'active' : '' }} ">
                        <a href="{{ route('admin.order.listShippingOrder') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Shipping Order </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('deliverydOrder') ? 'active' : '' }} ">
                        <a href="{{ route('admin.order.listDeliverydOrder') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Delivered Order </div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('canceledOrder') ? 'active' : '' }} ">
                        <a href="{{ route('admin.order.listCanceledOrder') }}" class="menu-link">
                            <div data-i18n="Without navbar">List Canceled Order </div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Boost -->
            <li class="menu-item   {{ request()->is('') ? 'active' : '' }}">
                <a href="" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Boost</div>
                </a>
            </li>









            <!-- setting -->
            <li class="menu-item   {{ request()->is('setting') ? 'active' : '' }}">
                <a href="{{ route('admin.setting.listSetting') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Setting</div>
                </a>
            </li>

            <!-- review -->
            <li class="menu-item   {{ request()->is('review') ? 'active' : '' }}">
                <a href="{{ route('admin.review.listReview') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Review</div>
                </a>
            </li>

            <!-- reports -->
            <li class="menu-item   {{ request()->is('report') ? 'active' : '' }}">
                <a href="" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Reports</div>
                </a>
            </li>



             Notification
            <li class="menu-item   {{ request()->is('notification') ? 'active' : '' }}">
                <a href="{{ route('admin.notification.listNotification') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">Notifications</div>
                </a>
            </li>


            <!-- info -->
            <li class="menu-item   {{ request()->is('info') ? 'active' : '' }}">
                <a href="{{ route('admin.info.listInfo') }}" class="menu-link ">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div data-i18n="Analytics">info</div>
                </a>
            </li> --}}




    </aside>
