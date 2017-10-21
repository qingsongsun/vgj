<header class="journal-header-default nocart-nosearch">

    <div class="journal-top-header j-min"></div>
    <div id="header" class="journal-header">
        <div class="journal-logo j-tall xs-100 sm-100 md-25 lg-25 xl-25">
            <?php if ($logo) { ?>
            <div id="logo">
                <a href="<?php echo str_replace('index.php?route=common/home', '', $home); ?>">
                    <?php echo Journal2Utils::getLogo($this->config); ?>
                </a>
            </div>
            <?php } ?>
        </div>

        <div class="journal-links j-min xs-100 sm-100 md-75 lg-75 xl-75">
            <div class="links j-min">
                <?php echo $this->journal2->settings->get('config_primary_menu'); ?>
            </div>
        </div>

        <div class="row journal-login j-min xs-100 sm-100 md-75 lg-75 xl-75">
            <div class="journal-language">
                <?php echo $language; ?>
            </div>
            <div class="journal-currency">
                <?php echo $currency; ?>
            </div>
            <div class="journal-secondary">
                <?php echo $this->journal2->settings->get('config_secondary_menu'); ?>
            </div>
        </div>

        <div class="journal-menu j-min xs-100 sm-100 md-75 lg-75 xl-75">
            <?php echo $this->journal2->settings->get('config_mega_menu'); ?>
        </div>
    </div>
</header>