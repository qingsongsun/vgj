<div style="<?php echo $width; ?>; max-height:<?php echo $height;?>px " class="<?php echo $nav_on_hover; ?> journal-simple-slider box <?php echo implode(' ', $disable_on_classes); ?>">
    <div id="journal-simple-slider-<?php echo $module; ?>">
        <?php foreach ($slides as $slide): ?>
            <?php if ($preload_images): ?>
                <?php if ($slide['link']): ?>
                    <div class="item"><a href="<?php echo $slide['link']; ?>" <?php echo $slide['target']; ?>><img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="lazyOwl" data-src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['name']; ?>"/></a></div>
                <?php else: ?>
                    <div class="item">
                        <img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" class="lazyOwl" data-src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['name']; ?>"/>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($slide['link']): ?>
                    <div class="item"><a href="<?php echo $slide['link']; ?>" <?php echo $slide['target']; ?>><img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['name']; ?>"/></a></div>
                <?php else: ?>
                    <div class="item">
                        <img width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" src="<?php echo $slide['image']; ?>" alt="<?php echo $slide['name']; ?>"/>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <script>
        (function () {
            var opts = $.parseJSON('<?php echo json_encode($js_options); ?>');
            opts.singleItem = true;
            opts.navigationText = false;
            jQuery('#journal-simple-slider-<?php echo $module; ?>').owlCarousel(opts);
        })();
    </script>
</div>

