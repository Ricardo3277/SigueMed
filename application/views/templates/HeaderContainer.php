<div class="app-content content container-fluid">
      <div class="content-wrapper">
          <div class="content-header row">
            <div class="content-header-left col-md-6 col-xs-12 mb-1">
              <h4 class="content-header-title"><?php echo $title;?></h4>
            </div>
            <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-xs-12">
              <div class="breadcrumb-wrapper col-xs-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo site_url();?>"><i class='icon-ios-speedometer-outline'></i>Tablero Principal</a></li>
                  <li class="breadcrumb-item active"><?php echo $title;?></li>
                </ol>
              </div>
            </div>
         </div>
        <div class="content-body">
<?php
        if (isset($swal) && $swal == true)
        {
          $action ="<script>";
          $action .= "Swal.fire({".$swalMessage."})";
          if (isset($swalAction))
          {
            $action .= $swalAction;
          }
          else {
            $action .=';';
          }
          $action .= '</script>';
          echo $action;

        }
        if (!$this->session->has_userdata('logged_in'))
            {

                redirect(base_url());
            }

    ?>
