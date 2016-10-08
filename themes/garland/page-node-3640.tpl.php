<?php
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
  </head>
  <?php
  	$left = null;
  ?>
  <body<?php print phptemplate_body_class($left, $right); ?>>

<!-- Layout -->
  <div id="header-region" class="clear-block"><?php print $header; ?></div>

    <div id="wrapper" style="max-width: 100%;padding: 0;">
    <div id="wrapper-up" style="background: url(<?php  print base_path(); ?>themes/garland/images/bannerindogas.jpg) no-repeat top center;">
    <div id="container" class="clear-block" style="max-width: 100%;padding: 0;">

      <div id="header">
        <div id="logo-floater">
        <?php
          // Prepare header
          $site_fields = array();
          if ($site_name) {
            $site_fields[] = check_plain($site_name);
          }
          if ($site_slogan) {
            $site_fields[] = check_plain($site_slogan);
          }
          $site_title = implode(' ', $site_fields);
          if ($site_fields) {
            $site_fields[0] = '<span>'. $site_fields[0] .'</span>';
          }
          $site_html = implode(' ', $site_fields);

          /*if ($logo || $site_title) {
            print '<h1><a href="'. check_url($front_page) .'" title="'. $site_title .'">';
            if ($logo) {
              print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';
            }
            print $site_html .'</a></h1>';
          }*/
        ?>
        </div>

        <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
        <?php endif; ?>
        <?php if (isset($secondary_links)) : ?>
          <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')) ?>
        <?php endif; ?>

      </div> <!-- /header -->

      <?php /*if ($left): ?>
        <div id="sidebar-left" class="sidebar">
          <?php if ($search_box): ?><div class="block block-theme"><?php print $search_box ?></div><?php endif; ?>
          <?php print $left ?>
        </div>
      <?php endif;*/ ?>

      <div id="center" style="margin-top: 78px;"><div id="squeeze" style="background:none #FFF;"><div class="right-corner" style="left:0;background:none;"><div class="left-corner" style="background:none;padding: 10px 25px 5em 35px;">
          <?php /*print $breadcrumb;*/ ?>
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
          <?php if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>
          <div class="clear-block">
          	<div id="buttonplace" align="center"><button class="menu_button" onclick="window.location = '<?php print base_path(); ?>eksekutifreport/kinerjaoperasional'">Kinerja Operasional</button>&nbsp;<button class="menu_button" onclick="window.location = '<?php print base_path(); ?>eksekutifreport/monthlyincome'">Penjualan</button>
          	<?php
          		$cabang_rs = db_query("SELECT nid,title FROM node WHERE type='cabang_indogas'");
          		while ($cabang_data = db_fetch_object($cabang_rs)){
          			$namacabang = explode(" ",$cabang_data->title);
          			print '<button onclick="window.location = \''.base_path().'eksekutifreport/detailworkshopdata?idcabang='.$cabang_data->nid.'\'" class="menu_button">'.$namacabang[0].'</button>&nbsp;';
          		}
          	?>
          	</div>
            <?php print $content ?>
          </div>
          <?php print $feed_icons ?>
          <div id="footer"><?php print $footer_message . $footer ?></div>
      </div></div></div></div> <!-- /.left-corner, /.right-corner, /#squeeze, /#center -->

      <?php /*if ($right): ?>
        <div id="sidebar-right" class="sidebar">
          <?php if (!$left && $search_box): ?><div class="block block-theme"><?php print $search_box ?></div><?php endif; ?>
          <?php print $right ?>
        </div>
      <?php endif;*/ ?>

    </div> <!-- /container -->
  </div>
  </div>
<!-- /layout -->

  <?php print $closure ?>
  </body>
</html>
