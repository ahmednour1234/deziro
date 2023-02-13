
          <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar"
        >
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            {{-- <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input
                  type="text"
                  name="query"
                  class="form-control border-0 shadow-none"
                  placeholder="Search..."
                  aria-label="Search..."
                />
              </div>
            </div> --}}
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->
              {{-- <li class="nav-item lh-1 me-3">
                <a
                  class="github-button"
                  href="https://github.com/themeselection/sneat-html-admin-template-free"
                  data-icon="octicon-star"
                  data-size="large"
                  data-show-count="true"
                  aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"
                  >Star</a
                >
              </li> --}}

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="data:image/jpeg;base64,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" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="data:image/jpeg;base64,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" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block"></span>
                          <small class="text-muted">{{(Auth()->user()->first_name.' '.Auth()->user()->last_name) }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>

                  <li>
                   <div class="text-center">
                      {{-- <i class="bx bx-power-off me-2"></i> --}}
                      <a class="align-middle text-center text-primary" href={{route('logout')}}>Logout</a>
                    </div>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>
