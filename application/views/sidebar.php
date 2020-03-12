<?php
$username     = ucwords($this->session->userdata('username'));
$ceklevel     = $this->session->userdata('level');
$level        = ucwords(str_replace('_', ' ', $ceklevel));
$ceklevelasli = $this->session->userdata('level_asli');
?>

<aside class="app-aside app-aside-expand-md app-aside-light">
   <div class="aside-content">
      <header class="aside-header d-block d-md-none">
         <button
            class="btn-account"
            type="button"
            data-toggle="collapse"
            data-target="#dropdown-aside"
         >
            <span class="user-avatar user-avatar-lg">
               <img
                  src="<?=base_url('assets/images/avatars/profile.jpg');?>"
                  alt=""
               >
            </span>
            <span class="account-icon">
               <span class="fa fa-caret-down fa-lg"></span>
            </span>
            <span class="account-summary">
               <span class="account-name"><?=$username;?></span>
               <span class="account-description"><?=$level;?></span>
            </span>
         </button>

         <div
            id="dropdown-aside"
            class="dropdown-aside collapse"
         >
            <div class="pb-3">
               <?php if ($ceklevelasli == 'author_reviewer'): ?>
               <?php if ($ceklevel == 'author'): ?>
               <a
                  class="dropdown-item"
                  href="<?=base_url('auth/multilevel/reviewer');?>"
               >
                  <span class="dropdown-icon fa fa-sign-in-alt"></span> Masuk sebagai Reviewer</a>
               <?php else: ?>
               <a
                  class="dropdown-item"
                  href="<?=base_url('auth/multilevel/author');?>"
               >
                  <span class="dropdown-icon fa fa-sign-in-alt"></span> Masuk sebagai Author</a>
               <?php endif;?>
               <hr>
               <?php endif;?>

               <a
                  class="dropdown-item"
                  href="<?=base_url('user/changepassword/' . $this->session->userdata('user_id'));?>"
               >
                  <span class="dropdown-icon fa fa-cog"></span> Ganti Password</a>
               <a
                  class="dropdown-item"
                  href="<?=base_url('auth/logout');?>"
               >
                  <span class="dropdown-icon oi oi-account-logout"></span> Logout</a>
            </div>
         </div>
      </header>

      <div class="aside-menu overflow-hidden">
         <nav
            id="stacked-menu"
            class="stacked-menu"
         >
            <ul class="menu">
               <li class="menu-item <?=($pages == 'home') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url();?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fas fa-home"></span>
                     <span class="menu-text">Dashboard</span>
                  </a>
               </li>
               <li class="menu-header">Penerbitan </li>
               <li class="menu-item <?=($pages == 'draft') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('draft');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-paperclip"></span>
                     <span class="menu-text">Draft Usulan</span>
                  </a>
               </li>
               <?php if ($ceklevel == 'editor' || $ceklevel == 'layouter' || $ceklevel == 'superadmin' || $ceklevel == 'admin_penerbitan'): ?>
               <li class="menu-item <?=($pages == 'worksheet') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('worksheet');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon oi oi-pencil"></span>
                     <span class="menu-text">Lembar Kerja</span>
                  </a>
               </li>
               <?php endif;?>
               <?php if ($ceklevel == 'superadmin' || $ceklevel == 'admin_penerbitan'): ?>
               <li class="menu-item <?=($pages == 'book') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('book');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-book"></span>
                     <span class="menu-text">Buku</span>
                  </a>
               </li>
               <?php endif;?>

               <!-- jika bukan superadmin / admin penerbitan maka tampil draft sama buku doang -->
               <?php if ($ceklevel == 'superadmin' || $ceklevel == 'admin_penerbitan'): ?>
               <li class="menu-item <?=($pages == 'author') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('author');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-user-tie"></span>
                     <span class="menu-text">Penulis</span>
                  </a>
               </li>
               <li class="menu-item <?=($pages == 'reviewer') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('reviewer');?>"
                     class="menu-link "
                  >
                     <span class="menu-icon fa fa-user-graduate"></span>
                     <span class="menu-text">Reviewer</span>
                  </a>
               </li>
               <?php if ($ceklevelasli == 'editor'): ?>
               <li class="menu-item <?=($pages == 'worksheet') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('worksheet');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon oi oi-pencil"></span>
                     <span class="menu-text">Lembar Kerja</span>
                  </a>
               </li>
               <?php endif;?>
               <li
                  class="menu-item has-child <?=($pages == 'category' || $pages == 'work_unit' || $pages == 'theme' || $pages == 'faculty' || $pages == 'work_unit' || $pages == 'institute') ? 'has-active' : '';?>">
                  <a
                     href="#"
                     class="menu-link "
                  >
                     <span class="menu-icon oi oi-puzzle-piece"></span>
                     <span class="menu-text">Data Pendukung</span>
                  </a>
                  <ul class="menu">
                     <li class="menu-item <?=($pages == 'category') ? 'has-active' : '';?>">
                        <a
                           href="<?=base_url('category');?>"
                           class="menu-link"
                        >Data Kategori</a>
                     </li>
                     <li class="menu-item <?=($pages == 'theme') ? 'has-active' : '';?>">
                        <a
                           href="<?=base_url('theme');?>"
                           class="menu-link"
                        >Data Tema</a>
                     </li>
                     <li class="menu-item <?=($pages == 'faculty') ? 'has-active' : '';?>">
                        <a
                           href="<?=base_url('faculty');?>"
                           class="menu-link"
                        >Data Fakultas</a>
                     </li>
                     <li class="menu-item <?=($pages == 'work_unit') ? 'has-active' : '';?>">
                        <a
                           href="<?=base_url('work_unit');?>"
                           class="menu-link"
                        >Data Unit Kerja </a>
                     </li>
                     <li class="menu-item <?=($pages == 'institute') ? 'has-active' : '';?>">
                        <a
                           href="<?=base_url('institute');?>"
                           class="menu-link"
                        >Data Institusi</a>
                     </li>
                  </ul>
               </li>
               <!-- if superadmin halaman user -->
               <?php if ($ceklevel == 'superadmin'): ?>
               <li class="menu-header">LAPORAN </li>
               <li class="menu-item <?=($pages == 'reporting') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('reporting');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-chart-bar"></span>
                     <span class="menu-text">Laporan</span>
                  </a>
               </li>
               <li class="menu-item <?=($pages == 'performance') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('performance');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-walking"></span>
                     <span class="menu-text">Performa</span>
                  </a>
               </li>
               <li class="menu-header">Pengaturan </li>
               <li class="menu-item <?=($pages == 'document') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('document');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-file"></span>
                     <span class="menu-text">Dokumen</span>
                  </a>
               </li>
               <li class="menu-item <?=($pages == 'user') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('user');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-users"></span>
                     <span class="menu-text">Akun User</span>
                  </a>
               </li>
               <li class="menu-item <?=($pages == 'setting') ? 'has-active' : '';?>">
                  <a
                     href="<?=base_url('setting');?>"
                     class="menu-link"
                  >
                     <span class="menu-icon fa fa-cog"></span>
                     <span class="menu-text">Setting</span>
                  </a>
               </li>
               <?php endif;?>
               <!-- endif superadmin halaman user dan setting -->
               <?php endif;?>
            </ul>
         </nav>
      </div>

      <footer class="aside-footer border-top p-3">
         <button
            class="btn btn-light btn-block text-primary"
            data-toggle="skin"
         >Night mode <i class="fas fa-moon ml-1"></i></button>
      </footer>
   </div>
</aside>