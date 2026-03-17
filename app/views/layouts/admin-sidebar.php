<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="./index.html" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="../../dist/assets/img/AdminLTELogo.png"
        alt="AdminLTE Logo"
        class="brand-image opacity-75 shadow" />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">Agenbee</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false">
        <li class="nav-item menu-open">
          <a href="<?php echo _HOST_URL ?>/admin/" class="nav-link active">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo _HOST_URL ?>/admin/package" class="nav-link">
            <i class="nav-icon bi bi-box-seam-fill"></i>
            <p>
              Package
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-clipboard-fill"></i>
            <p>
              Orders
              <span class="nav-badge badge text-bg-secondary me-3">6</span>
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./layout/unfixed-sidebar.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Order List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./layout/fixed-sidebar.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Details</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-clipboard-fill"></i>
            <p>
              Tasks
              <span class="nav-badge badge text-bg-secondary me-3">6</span>
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./layout/unfixed-sidebar.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Task List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./layout/fixed-sidebar.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Details</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-tree-fill"></i>
            <p>
              User manager
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="./UI/general.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>User List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./UI/icons.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Create User</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="./UI/timeline.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Details</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->