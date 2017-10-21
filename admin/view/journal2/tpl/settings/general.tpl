<div class="sticky">
<div class="module-header">
    <div class='module-name'>Settings<span>General</span></div>

    <skin-manager data-url="settings/general"></skin-manager>

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

<div class="module-body">
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
                    <span class="module-create-title">Body Background Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.body_bg_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Body Background Image</span>
                    <span class="module-create-option">
                        <j-opt-background data-ng-model="settings.body_bg_image"></j-opt-background>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>

                <li>
                    <span class="module-create-title">General Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.body_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">General Links Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.general_links_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">General Links Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.general_links_hover"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>

            </ul>
         </accordion-group>
        <!--  Layout -->
        <accordion-group is-open="accordion.accordions[1]">
                <accordion-heading>
                    <div class="accordion-bar bar-level-0">Layout</div>
                </accordion-heading>
                <ul class="module-create-options">

                    <li>
                        <span class="module-create-title">Site Width <small>Content Container</small></span>
                                <span class="module-create-option site-width">
                                    <j-opt-slider data-ng-model="settings.site_width" data-range="1024,1920" data-step="2"></j-opt-slider>
                                </span>
                        <a href="http://journal.digital-atelier.com/tips/general_layout_1.jpg" target="_blank" class="journal-tip"></a>
                    </li>
                    <li>
                        <span class="module-create-title">Responsive Layout</span>
                                <span class="module-create-option">
                                    <switch data-ng-model="settings.responsive_design">
                                        <switch-option key="1">ON</switch-option>
                                        <switch-option key="0">OFF</switch-option>
                                    </switch>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>


                    <li>
                        <span class="module-create-title">Site Layout</span>
                                <span class="module-create-option">
                                    <switch data-ng-model="settings.extended_layout">
                                        <switch-option key="0">Boxed</switch-option>
                                        <switch-option key="1">Fullwidth &nbsp;&nbsp;</switch-option>
                                    </switch>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>


                    <li  data-ng-show="settings.extended_layout === '0'">
                        <span class="module-create-title">Content Background Color <small>Boxed Layout</small></span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.content_bg_color"></j-opt-color>
                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li  data-ng-show="settings.extended_layout === '0'">
                        <span class="module-create-title">Content Background Image <small>Boxed Layout</small></span>
                    <span class="module-create-option">
                        <j-opt-background data-ng-model="settings.content_bg_image"></j-opt-background>
                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li  data-ng-hide="settings.extended_layout === '1'">
                        <span class="module-create-title">Container Border Radius <small>Home Page</small></span>
                                <span class="module-create-option">
                                    <j-opt-border data-ng-model="settings.container_border" editor="hide-style"></j-opt-border>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li  data-ng-show="settings.extended_layout === '1'">
                        <span class="module-create-title">Content Background Color  <small>Fullwidth Layout</small></span>
                                <span class="module-create-option">
                                    <j-opt-color data-ng-model="settings.extended_layout_bg_color"></j-opt-color>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li  data-ng-show="settings.extended_layout === '1'">
                        <span class="module-create-title">Content Background Image <small>Fullwidth Layout</small></span>
                                <span class="module-create-option">
                                    <j-opt-background data-ng-model="settings.extended_layout_bg_image"></j-opt-background>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li  data-ng-show="settings.extended_layout === '1'">
                        <span class="module-create-title">Top Margin</span>
                                <span class="module-create-option">
                                    <switch data-ng-model="settings.extended_layout_top_spacing">
                                        <switch-option key="on">ON</switch-option>
                                        <switch-option key="off">OFF</switch-option>
                                    </switch>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li  data-ng-show="settings.extended_layout === '1'">
                        <span class="module-create-title">Side Modules Margin</span>
                                <span class="module-create-option">
                                    <switch data-ng-model="settings.extended_layout_side_spacing">
                                        <switch-option key="on">ON</switch-option>
                                        <switch-option key="off">OFF</switch-option>
                                    </switch>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                </ul>
            </accordion-group>
        <!--Headings-->
        <accordion-group is-open="accordion.accordions[2]">
                <accordion-heading>
                    <div class="accordion-bar bar-level-0">Headings</div>
                </accordion-heading>
                <ul class="module-create-options">

                    <li>
                        <span class="module-create-title">Main Title Bar Font</span>
                                    <span class="module-create-option">
                                        <j-opt-font data-ng-model="settings.main_title_font"></j-opt-font>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li>
                        <span class="module-create-title">Main Title Background</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.main_title_bg"></j-opt-color>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li>
                        <span class="module-create-title">Main Title Border Settings</span>
                                    <span class="module-create-option">
                                        <j-opt-border data-ng-model="settings.main_title_border"></j-opt-border>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li>
                        <span class="module-create-title">Main Title Line Height <small>Vertical Centering</small></span>
                                    <span class="module-create-option">
                                        <j-opt-text data-ng-model="settings.main_title_line_height" class="journal-number-field"></j-opt-text>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li>
                        <span class="module-create-title">Main Title Padding <small>Left - Right</small></span>
                            <span class="module-create-option">
                                <j-opt-text data-ng-model="settings.main_title_padding_left" class="journal-sort"></j-opt-text> -
                                <j-opt-text data-ng-model="settings.main_title_padding_right" class="journal-sort"></j-opt-text>
                            </span>
                        <a href="#" target="_blank" class="journal-tip"></a>
                    </li>
                    <li>
                        <span class="module-create-title">Main Title Text Align</span>
                                    <span class="module-create-option">
                                        <switch data-ng-model="settings.main_title_align">
                                            <switch-option key="left">Left</switch-option>
                                            <switch-option key="center">Center</switch-option>
                                            <switch-option key="right">Right</switch-option>
                                        </switch>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li>
                        <span class="module-create-title">Secondary Title Font</span>
                                    <span class="module-create-option">
                                        <j-opt-font data-ng-model="settings.secondary_title_font"></j-opt-font>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li>
                        <span class="module-create-title">Secondary Title Background</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.secondary_title_bg"></j-opt-color>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li>
                        <span class="module-create-title">Secondary Title Border</span>
                                    <span class="module-create-option">
                                        <j-opt-border data-ng-model="settings.secondary_title_border"></j-opt-border>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                    <li>
                        <span class="module-create-title">Secondary Title Padding <small>Top-Bottom / Left-Right</small></span>
                                <span class="module-create-option">
                                    <j-opt-text data-ng-model="settings.secondary_title_padding_tb" class="journal-number-field"></j-opt-text> x
                                    <j-opt-text data-ng-model="settings.secondary_title_padding_lr" class="journal-number-field"></j-opt-text>
                                </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>
                    <li>
                        <span class="module-create-title">Secondary Title Text Align</span>
                                    <span class="module-create-option">
                                        <switch data-ng-model="settings.secondary_title_align">
                                            <switch-option key="left">Left</switch-option>
                                            <switch-option key="center">Center</switch-option>
                                            <switch-option key="right">Right</switch-option>
                                        </switch>
                                    </span>
                        <a href="#" target="_blank" class="journal-tip"> </a>
                    </li>

                </ul>

            </accordion-group>
        <!--Button Settings-->
        <accordion-group is-open="accordion.accordions[3]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Buttons</div>
            </accordion-heading>
            <ul class="module-create-options">

                <li>
                    <span class="module-create-title">Font</span>
                                    <span class="module-create-option">
                                        <j-opt-font data-ng-model="settings.general_button_font"></j-opt-font>
                                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Font Hover Color</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.general_button_font_hover"></j-opt-color>
                                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>


                <li>
                    <span class="module-create-title">Background Color</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.general_button_bg"></j-opt-color>
                                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>


                <li>
                    <span class="module-create-title">Background Hover Color</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.general_button_bg_hover"></j-opt-color>
                                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Border Settings</span>
                        <span class="module-create-option">
                            <j-opt-border data-ng-model="settings.general_button_border"></j-opt-border>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Border Hover Color</span>
                                    <span class="module-create-option">
                                        <j-opt-color data-ng-model="settings.general_button_border_hover"></j-opt-color>
                                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Button Width <small>Padding Left / Right</small></span>
                                <span class="module-create-option">
                                    <j-opt-text data-ng-model="settings.general_button_width" class="journal-number-field"></j-opt-text>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Button Height <small>Line Height</small></span>
                                <span class="module-create-option">
                                    <j-opt-text data-ng-model="settings.general_button_height" class="journal-number-field"></j-opt-text>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Inner Shadow <small>Push Effect</small></span>
                                <span class="module-create-option">
                                    <switch data-ng-model="settings.general_button_active_shadow">
                                        <switch-option key="inset 0 1px 10px rgba(0, 0, 0, 0.8)">ON</switch-option>
                                        <switch-option key="none">OFF</switch-option>
                                    </switch>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>


            </ul>
        </accordion-group>
        <!--Breadcrumb-->
        <accordion-group is-open="accordion.accordions[4]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Breadcrumbs</div>
            </accordion-heading>
            <ul class="module-create-options">
                <li>
                    <span class="module-create-title">Status</span>
                        <span class="module-create-option">
                            <switch data-ng-model="settings.breadcrumb_status">
                                <switch-option key="block">ON</switch-option>
                                <switch-option key="none">OFF</switch-option>
                            </switch>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.breadcrumb_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Font Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.breadcrumb_hover"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Active Link Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.breadcrumb_active_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Active Link Font Hover</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.breadcrumb_active_hover"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Background Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.breadcrumb_bg_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Fullwidth Background Color</span>
                    <span class="module-create-option">
                        <j-opt-color data-ng-model="settings.breadcrumb_full_bg_color"></j-opt-color>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
                <li>
                    <span class="module-create-title">Border Settings</span>
                        <span class="module-create-option">
                            <j-opt-border data-ng-model="settings.breadcrumb_border"></j-opt-border>
                        </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Padding </span>
                                <span class="module-create-option">
                                    <j-opt-text data-ng-model="settings.breadcrumb_padding" class="journal-number-field"></j-opt-text>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Line Height <small>Vertical Centering</small> </span>
                                <span class="module-create-option">
                                    <j-opt-text data-ng-model="settings.breadcrumb_line_height" class="journal-number-field"></j-opt-text>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

                <li>
                    <span class="module-create-title">Text Align</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.breadcrumb_align">
                            <switch-option key="left">Left</switch-option>
                            <switch-option key="center">Center</switch-option>
                            <switch-option key="right">Right</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>

            </ul>
        </accordion-group>
        <!--Loader-->
        <accordion-group is-open="accordion.accordions[5]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Loader</div>
            </accordion-heading>
            <ul class="module-create-options">
                <li>
                    <span class="module-create-title">Loading Image</span>
                    <span class="module-create-option">
                        <j-opt-image data-ng-model="settings.ajax_loader"></j-opt-image>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
            </ul>
        </accordion-group>

        <!--Scroll to Top-->
        <accordion-group is-open="accordion.accordions[6]">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Scroll to Top</div>
            </accordion-heading>
            <ul class="module-create-options">
                <li>
                    <span class="module-create-title">Scroll to Top when Adding to Cart/Wishlist/Compare</span>
                    <span class="module-create-option">
                        <switch data-ng-model="settings.scroll_to_top">
                            <switch-option key="1">ON</switch-option>
                            <switch-option key="0">OFF</switch-option>
                        </switch>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>

                <li>
                    <span class="module-create-title">Scroll to Top Button</span>
                                <span class="module-create-option">
                                    <switch data-ng-model="settings.scroll_top">
                                        <switch-option key="1">ON</switch-option>
                                        <switch-option key="0">OFF</switch-option>
                                    </switch>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>

                <li data-ng-show="settings.scroll_top == 1">
                    <span class="module-create-title">Scroll to Top Icon</span>
                                <span class="module-create-option">
                                    <j-opt-icon data-ng-model="settings.scroll_top_icon"></j-opt-icon>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>

                <li data-ng-show="settings.scroll_top == 1">
                    <span class="module-create-title">Icon Hover</span>
                                <span class="module-create-option">
                                    <j-opt-color data-ng-model="settings.scroll_top_icon_hover"></j-opt-color>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
            </ul>
        </accordion-group>
        <!--Leading Element-->
        <accordion-group is-open="false">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Optional Leading Element</div>
            </accordion-heading>
            <ul class="module-create-options">
                <li>
                    <span class="module-create-title">Element Image<small>Visible under transparent sliders</small></span>
                    <span class="module-create-option">
                        <j-opt-image data-ng-model="settings.leading_element"></j-opt-image>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Offset Positioning<small>Left - Top</small></span>
                                <span class="module-create-option">
                                    <j-opt-text data-ng-model="settings.lead_offset_left" class="journal-sort"></j-opt-text> -
                                    <j-opt-text data-ng-model="settings.lead_offset_top" class="journal-sort"></j-opt-text>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
            </ul>
        </accordion-group>

        <!-- old browser notice -->
        <accordion-group is-open="false">
            <accordion-heading>
                <div class="accordion-bar bar-level-0">Old Browser Notice</div>
            </accordion-heading>
            <ul class="module-create-options">
                <li>
                    <span class="module-create-title">Message<small>For users with IE8 or older.</small></span>
                    <span class="module-create-option">
                        <j-opt-text-lang data-ng-model="settings.old_browser_message"></j-opt-text-lang>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li>
                    <span class="module-create-title">Font</span>
                    <span class="module-create-option">
                        <j-opt-font data-ng-model="settings.old_browser_font"></j-opt-font>
                    </span>
                    <a href="#" target="_blank" class="journal-tip"></a>
                </li>
                <li data-ng-show="settings.scroll_top == 1">
                    <span class="module-create-title">Background Color</span>
                                <span class="module-create-option">
                                    <j-opt-color data-ng-model="settings.old_browser_bg"></j-opt-color>
                                </span>
                    <a href="#" target="_blank" class="journal-tip"> </a>
                </li>
            </ul>
        </accordion-group>
    </accordion>
</div>
