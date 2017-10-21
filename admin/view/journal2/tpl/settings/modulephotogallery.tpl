<div class="sticky">
<div class="module-header">
    <div class='module-name'>Modules<span>Photo Gallery</span></div>

    <skin-manager data-url="settings/modulephotogallery"></skin-manager>

    <div class="module-buttons">
        <?php if (defined('J2ENV')): ?>
        <a class="btn blue" data-ng-show="skin_id < 100" data-ng-click="saveDefault($event)">Export</a>
        <?php endif; ?>
        <!--<a class="btn blue" data-ng-click="multiStore($event)">MultiStore</a>-->
        <a class="btn blue" data-ng-click="saveAs($event)">Save As</a>
        <a class="btn green" data-ng-click="save($event)">Save</a>
        <a class="btn red" data-ng-show="skin_id < 100" data-ng-click="reset($event)">Reset</a>
        <a class="btn red" data-ng-show="skin_id >= 100" data-ng-click="delete($event)">Delete</a>
    </div>
</div>
</div>
<div class="module-body custom-code">
    <div class="accordion-bar bar-level-0 bar-expand" >
        <a data-ng-click="toggleAccordion(true)" class="hint--top" data-hint="Expand All"><i class="expand-icon"></i></a>  <a data-ng-click="toggleAccordion(false)" class="hint--top" data-hint="Collapse All"><i class="collapse-icon"></i></a>
        <label class="close-others hint--top" data-hint="Close Others"><input type="checkbox" data-ng-model="accordion.close_others" /></label>
    </div>
    <accordion id="main-accordion" close-others="accordion.close_others">
        <accordion-group is-open="accordion.accordions[0]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">General</div>
            </accordion-heading>
            <ul class="module-create-options">

                <li>
                    <span class="module-create-title">Thumb Overlay Color</span>
                        <span class="module-create-option">
                            <j-opt-color data-ng-model="settings.gallery_overlay_color"></j-opt-color>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
<!--                 <li>
                    <span class="module-create-title">Thumb Overlay Opacity</span>
                        <span class="module-create-option">
                            <j-opt-text data-ng-model="settings.gallery_overlay_opacity" class="journal-number-field"></j-opt-text>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li> -->
                <li>
                    <span class="module-create-title">Thumb Overlay Icon</span>
                        <span class="module-create-option">
                            <j-opt-icon data-ng-model="settings.gallery_overlay_icon"></j-opt-icon>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Gallery Overlay Color</span>
                        <span class="module-create-option">
                            <j-opt-color data-ng-model="settings.gallery_overlay_bg"></j-opt-color>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Top / Bottom Bars Color</span>
                        <span class="module-create-option">
                            <j-opt-color data-ng-model="settings.gallery_bars_bg"></j-opt-color>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Image Title Font</span>
                        <span class="module-create-option">
                            <j-opt-font data-ng-model="settings.gallery_name_font"></j-opt-font>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Close Button Icon</span>
                        <span class="module-create-option">
                            <j-opt-icon data-ng-model="settings.gallery_close"></j-opt-icon>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Close Button Hover</span>
                        <span class="module-create-option">
                            <j-opt-color data-ng-model="settings.gallery_close_hover"></j-opt-color>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Left Arrow Icon</span>
                        <span class="module-create-option">
                            <j-opt-icon data-ng-model="settings.gallery_left"></j-opt-icon>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Right Arrow Icon</span>
                        <span class="module-create-option">
                            <j-opt-icon data-ng-model="settings.gallery_right"></j-opt-icon>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Arrows Hover</span>
                        <span class="module-create-option">
                            <j-opt-color data-ng-model="settings.gallery_nav_hover"></j-opt-color>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Image Border Settings</span>
                        <span class="module-create-option">
                            <j-opt-border data-ng-model="settings.gallery_image_border"></j-opt-border>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Side Column Padding</span>
                        <span class="module-create-option">
                            <j-opt-text data-ng-model="settings.side_gallery_padding" class="journal-number-field"></j-opt-text>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
            </ul>
        </accordion-group>

    </accordion>
</div>
