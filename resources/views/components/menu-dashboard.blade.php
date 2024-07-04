<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{ route('dashboard') }}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Tableau de bord
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-folder-open"></i>
              <p>
                Documents
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('guide.index') }}" class="nav-link">
                  <i class="nav-icon far fa-folder-open"></i>
                  <p>Guides</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('type-document.index') }}" class="nav-link">
                  <i class="nav-icon far fa-folder-open"></i>
                  <p>Documents type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('model-acte.index') }}" class="nav-link">
                  <i class="nav-icon far fa-folder-open"></i>
                  <p>Modèles actes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('compte-rendu.index') }}" class="nav-link">
                  <i class="nav-icon far fa-folder-open"></i>
                  <p>Compte rendus</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('conclusion.index') }}" class="nav-link">
                  <i class="nav-icon far fa-folder-open"></i>
                  <p>Conclusion</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('activite.index') }}" class="nav-link">
              <i class="nav-icon fas fa-file-word"></i>
              <p>Activités</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('evenement.index') }}" class="nav-link">
              <i class="nav-icon fas fa-calendar"></i>
              <p>Evènements</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('faq.index') }}" class="nav-link">
              <i class="nav-icon fas fa-question"></i>
              <p>FAQs</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('information.index') }}" class="nav-link">
              <i class="nav-icon fas fa-paper-plane"></i>
              <p>
                Informations
              </p>
            </a>
          </li>
          <hr>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Déconnexion
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>