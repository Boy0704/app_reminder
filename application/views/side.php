<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="image/user/<?php echo $this->session->userdata('foto') ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('nama'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <?php 
        if ($this->session->userdata('level') == 'admin') {
         ?>
        
        <li><a href="app"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
        
        
        <li><a href="Customer"><i class="fa fa-circle-o"></i> <span>Data Customer</span></a></li>
        <li><a href="cabang"><i class="fa fa-circle-o"></i> <span>Data Cabang</span></a></li>
        <li><a href="reminder"><i class="fa fa-send"></i> <span>Reminder</span></a></li>
        <li><a href="user"><i class="fa fa-users"></i> <span>Manajemen User</span></a></li>
      <?php } elseif ($this->session->userdata('level') == 'psr') { ?>
         <li><a href="app"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
        
        
        <li><a href="mahasiswa"><i class="fa fa-circle-o"></i> <span>Data Customer</span></a></li>
        <li><a href="app/cetak"><i class="fa fa-print"></i> <span>Cetak Customer</span></a></li>
      

      <?php } ?>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Tentang Aplikasi</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Bantuan</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>