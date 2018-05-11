<?php
?>
<header>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header pull-left">
      <a class="navbar-brand" href="/">
          <img src="<?php print $youmove_url;?>/images/wemove/logo.png">
      </a>
    </div>
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse-1">
         <span class="sr-only">Toggle navigation</span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
         <ul class="nav navbar-nav" id="main-menu">
            <li><a href="/you"><?php print t('YOUMOVE CAMPAIGNS'); ?></a></li>
            <li><a href="/you/create"><?php print t('START A CAMPAIGN'); ?></a></li>
            <li><a href="/civicrm/contribute/transact?reset=1&amp;id=1"><?php print t('DONATE'); ?></a></li>
            <?php if (user_is_logged_in()){ ?>
                <li><a href="/your-campaigns"><?php print t('MY CAMPAIGNS'); ?></a></li>
                <li><a href="/user/logout?destination=you"><?php print t('LOG-OUT'); ?></a></li>
            <?php } else { ?>
                <li><a href="/user"><?php print t('LOG-IN'); ?></a></li>
            <?php } ?>
          </ul>
    </div><!-- /.navbar-collapse -->

    <?php if (!empty($page['navigation'])): ?>
         <?php print render($page['navigation']); ?>
    <?php endif; ?>

  </div><!-- /.container-fluid -->
 </nav>
</header>

<div class="main-container container">

  <div class="row">

    <?php if (!empty($page['sidebar_first'])): ?>
      <aside class="col-sm-3" role="complementary">
        <?php print render($page['sidebar_first']); ?>
      </aside>  <!-- /#sidebar-first -->
    <?php endif; ?>

    <section<?php print $content_column_class; ?>>
      <?php if (!empty($page['highlighted'])): ?>
        <div class="highlighted jumbotron"><?php print render($page['highlighted']); ?></div>
      <?php endif; ?>

      <a id="main-content"></a>

         <?php print render($title_prefix); ?>
         <?php if (!empty($title)): ?>
          <h1 class="page-header"><?php print $title; ?></h1>
         <?php endif; ?>
         <?php print render($title_suffix); ?>

      <?php print $messages; ?>
      <?php if (!empty($tabs)): ?>
        <?php print render($tabs); ?>
      <?php endif; ?>
      <?php if (!empty($page['help'])): ?>
        <?php print render($page['help']); ?>
      <?php endif; ?>
      <?php if (!empty($action_links)): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
    </section>

    <?php if (!empty($page['sidebar_second'])): ?>
      <aside class="col-sm-3" role="complementary">
        <?php print render($page['sidebar_second']); ?>
      </aside>  <!-- /#sidebar-second -->
    <?php endif; ?>

  </div>
</div>

<div id="footer" style="background: #EDEDED;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-3">
        <div style="padding: 20px 0">
          <img src="<?php print $youmove_url;?>/images/wemove/logo.png" style="width: 190px">
        </div>
      </div>
      <div class="col-sm-9">
        <div class="row" style="margin-top: 20px">
          <p id="disclaimer"><?php print t('Petitions on you.wemove.eu are started and run by civil society groups and members of the public. WeMove.EU hosts these petitions and they are in line with our values but they are not necessarily endorsed by WeMove.EU.'); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>
