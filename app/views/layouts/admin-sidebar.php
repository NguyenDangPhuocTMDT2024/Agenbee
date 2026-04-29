<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?php echo _HOST_URL ?>/admin/" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="<?php echo _HOST_URL_PUBLIC; ?>/img/logo.jpg"
        alt="Agenbee"
        class="brand-image opacity-75 shadow rounded-circle" />
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
        <li class="nav-item">
          <a href="<?php echo _HOST_URL ?>/admin/" class="nav-link <?php echo ($activeMenu === 'Dashboard') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo _HOST_URL ?>/admin/package" class="nav-link <?php echo ($activeMenu === 'Packages') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-box"></i>
            <p>
              Packages
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo _HOST_URL ?>/admin/order" class="nav-link <?php echo ($activeMenu === 'Orders') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-clipboard"></i>
            <p>
              Orders
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo _HOST_URL ?>/admin/contact" class="nav-link <?php echo ($activeMenu === 'Contacts') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-phone"></i>
            <p>
              Contacts
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo _HOST_URL; ?>/admin/user" class="nav-link <?php echo ($activeMenu === 'Users') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-people"></i>
            <p>
              User manager
            </p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->