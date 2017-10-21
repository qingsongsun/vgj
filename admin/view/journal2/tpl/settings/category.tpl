<div class="sticky">
<div class="module-header">
    <div class='module-name'>Settings<span>Category Page</span></div>

    <skin-manager data-url="settings/category"></skin-manager>

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
<div class="module-body category">
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
                    <span class="module-create-title">Default View</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.product_view">
                            <switch-option key="grid">Grid</switch-option>
                            <switch-option key="list">List</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Show Category Image</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.hide_category_image">
                            <switch-option key="1">ON</switch-option>
                            <switch-option key="0">OFF</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li data-ng-show="settings.hide_category_image == '1'">
                    <span class="module-create-title">Image Border Settings</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.main_category_image_border"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Description Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.category_description_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Description Line Height</span>
                    <span class="module-create-option">
                        <j-opt-text data-ng-model="settings.category_description_line_height" class="journal-number-field"></j-opt-text>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Products per Row</span>
                    <span class="module-create-option">
                        <j-opt-items-per-row data-range="1,10" data-step="1" data-ng-model="settings.category_page_products_per_row"></j-opt-items-per-row>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <!--Product Page Title-->
                <accordion-group is-open="false">
                    <accordion-heading>
                        <div class="accordion-bar bar-level-1">Page Title</div>
                    </accordion-heading>
                    <ul>

                        <li>
                            <span class="module-create-title">Page Title Font</span>
                                    <span class="module-create-option">
                                        <j-opt-font data-ng-model="settings.category_page_title_font"></j-opt-font>
                                    </span>
                            <a href="#" target="_blank" class="journal-tip"> </a>
                        </li>

                        <li>
                            <span class="module-create-title">Page Title Background</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.category_page_title_bg"></j-opt-color>
                                    </span>
                            <a href="#" target="_blank" class="journal-tip"> </a>
                        </li>

                        <li>
                            <span class="module-create-title">Border Settings</span>
                                    <span class="module-create-option">
                                        <j-opt-border data-ng-model="settings.category_page_title_border"></j-opt-border>
                                    </span>
                            <a href="#" target="_blank" class="journal-tip"> </a>
                        </li>

                        <li>
                            <span class="module-create-title">Line Height <small>Vertical Centering</small></span>
                                    <span class="module-create-option">
                                        <j-opt-text data-ng-model="settings.category_page_title_line_height" class="journal-number-field"></j-opt-text>
                                    </span>
                            <a href="#" target="_blank" class="journal-tip"> </a>
                        </li>

                        <li>
                            <span class="module-create-title">Padding <small>Left - Right</small></span>
                            <span class="module-create-option">
                                <j-opt-text data-ng-model="settings.category_page_title_padding_left" class="journal-sort"></j-opt-text> -
                                <j-opt-text data-ng-model="settings.category_page_title_padding_right" class="journal-sort"></j-opt-text>
                            </span>
                            <a href="#" target="_blank" class="journal-tip"></a>
                        </li>
                        <li>
                            <span class="module-create-title">Title Overflow <small>Keep long names on the same line</small></span>
                            <span class="module-create-option">
                                <switch data-ng-model="settings.category_page_title_overflow">
                                    <switch-option key="on">ON</switch-option>
                                    <switch-option key="off">OFF</switch-option>
                                </switch>
                            </span>
                            <a href="#" target="_blank" class="journal-tip"> </a>
                        </li>

                        <li>
                            <span class="module-create-title">Title Align</span>
                                    <span class="module-create-option">
                                        <switch data-ng-model="settings.category_page_title_align">
                                            <switch-option key="left">Left</switch-option>
                                            <switch-option key="center">Center</switch-option>
                                            <switch-option key="right">Right</switch-option>
                                        </switch>
                                    </span>
                            <a href="#" target="_blank" class="journal-tip"> </a>
                        </li>

                    </ul>
                </accordion-group>


            </ul>
        </accordion-group>
        <accordion-group is-open="accordion.accordions[1]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Subcategories</div>
            </accordion-heading>
            <ul class="module-create-options">

                <li>
                    <span class="module-create-title">Display</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.refine_category">
                            <switch-option key="text">Text</switch-option>
                            <switch-option key="grid">Grid</switch-option>
                            <switch-option key="carousel">Carousel</switch-option>
                            <switch-option key="none">None</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Subcategory Image Size</span>
                    <span class="module-create-option">
                        <j-opt-text data-ng-model="settings.refine_image_width" class="journal-number-field"></j-opt-text> x
                        <j-opt-text data-ng-model="settings.refine_image_height" class="journal-number-field"></j-opt-text>
                        <switch data-ng-model="settings.refine_image_type">
                            <switch-option key="fit">Fit</switch-option>
                            <switch-option key="crop">Crop&nbsp;&nbsp;&nbsp;</switch-option>
                        </switch>
                    </span>
                </li>

                <li data-ng-show="settings.refine_category === 'carousel'">
                    <span class="module-create-title">Carousel Autoplay</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.refine_carousel_autoplay">
                            <switch-option key="1">ON</switch-option>
                            <switch-option key="0">OFF</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li data-ng-show="settings.refine_category === 'carousel' && settings.refine_carousel_autoplay === '1'">
                    <span class="module-create-title">Carousel Pause on Hover</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.refine_carousel_pause_on_hover">
                            <switch-option key="1">ON</switch-option>
                            <switch-option key="0">OFF</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li data-ng-show="settings.refine_category === 'carousel' && settings.refine_carousel_autoplay === '1'">
                    <span class="module-create-title">Carousel Touch Drag</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.refine_carousel_touchdrag">
                            <switch-option key="1">ON</switch-option>
                            <switch-option key="0">OFF</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li data-ng-hide="settings.refine_category == 'none'">
                    <span class="module-create-title">Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.refine_image_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-hide="settings.refine_category == 'none'">
                    <span class="module-create-title">Font Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.refine_image_font_hover"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'text'">
                    <span class="module-create-title">Link Divider</span>
                        <span class="module-create-option">
                            <j-opt-text data-ng-model="settings.refine_link_divider" class="journal-number-field"></j-opt-text>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'text'">
                    <span class="module-create-title">Link Divider Color</span>
                        <span class="module-create-option">
                            <j-opt-color data-ng-model="settings.refine_link_divider_color"></j-opt-color>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'text'">
                    <span class="module-create-title">Background Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.refine_bar_bg"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'text'">
                    <span class="module-create-title">Border Radius</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.refine_bar_radius" data-editor="hide-style"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'grid' || settings.refine_category == 'carousel'">
                    <span class="module-create-title">Item Background Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.refine_image_bg"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'grid' || settings.refine_category == 'carousel'">
                    <span class="module-create-title">Background Color Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.refine_image_bg_hover"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li data-ng-show="settings.refine_category == 'grid' || settings.refine_category == 'carousel'">
                    <span class="module-create-title">Item Border Settings</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.refine_image_border_radius"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>


                <li data-ng-show="settings.refine_category == 'grid' || settings.refine_category == 'carousel'">
                    <span class="module-create-title">Image Border Settings</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.refine_image_border"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>


                <li data-ng-show="settings.refine_category == 'grid' || settings.refine_category == 'carousel'">
                    <span class="module-create-title">Item Padding</span>
                    <span class="module-create-option">
                        <j-opt-text data-ng-model="settings.refine_image_padding" class="journal-number-field"></j-opt-text>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>


                <li data-ng-show="settings.refine_category == 'grid' || settings.refine_category == 'carousel'">
                    <span class="module-create-title">Items per Row</span>
                    <span class="module-create-option">
                        <j-opt-items-per-row data-range="1,10" data-step="1" data-ng-model="settings.refine_category_images_per_row"></j-opt-items-per-row>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

            </ul>
        </accordion-group>
        <accordion-group is-open="accordion.accordions[2]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Grid / List Bar</div>
            </accordion-heading>
            <ul class="module-create-options">
                <li>
                    <span class="module-create-title">Grid Icon</span>
                    <span class="module-create-option">
                        <j-opt-icon data-ng-model="settings.category_grid_view_icon"></j-opt-icon>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">List Icon</span>
                    <span class="module-create-option">
                        <j-opt-icon data-ng-model="settings.category_list_view_icon"></j-opt-icon>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Icon Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.category_view_icon_hover"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Background Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.product_filter_bg"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Bar Border Settings</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.product_filter_radius"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.product_filter_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Product Compare Link</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.product_compare_link_status">
                            <switch-option key="on">ON</switch-option>
                            <switch-option key="off">OFF</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Compare Icon</span>
                    <span class="module-create-option">
                        <j-opt-icon data-ng-model="settings.product_compare_icon"></j-opt-icon>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Compare Link Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.product_compare_hover_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
            </ul>
        </accordion-group>
        <!--Pagination-->
        <accordion-group is-open="accordion.accordions[3]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Pagination</div>
            </accordion-heading>
            <ul class="module-create-options">

                <li>
                    <span class="module-create-title">Link Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.pagination_link_font_new"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Link Background</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.pagination_link_bg"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Hover / Active Font Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.pagination_link_hover_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Hover / Active Background</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.pagination_link_hover_bg_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Link Radius</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.pagination_radius" editor="hide-style"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Results Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.pagination_text_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Bar Background</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.pagination_bar_bg"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Bar Border Settings</span>
                    <span class="module-create-option">
                        <j-opt-border data-ng-model="settings.pagination_bar_border"></j-opt-border>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Soft Shadow</span>
                        <span class="module-create-option">
                            <switch data-ng-model="settings.pagination_soft_shadow">
                                <switch-option key="1px 1px 0px rgba(0,0,0,.04)">ON</switch-option>
                                <switch-option key="none">OFF</switch-option>
                            </switch>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
            </ul>
        </accordion-group>
    </accordion>
</div>
