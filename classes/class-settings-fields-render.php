<?php

namespace AMCUST\Classes;

/**
 * Class related to rendering of settings fields on the admin page
 *
 * @since 1.0.0
 */
class Settings_Fields_Render {

	/**
	 * Render sortable menu field
	 *
	 * @since 2.0.0
	 */
	function render_sortable_menu( $args ) {

		?>
		<div class="instruction">Drag and drop menu items to the desired position. Optionally change item titles or hide  some items until "Show All" at the bottom of the admin menu is clicked.</div>
		<ul id="custom-admin-menu" class="menu ui-sortable">
		<?php

		global $menu;
		global $submenu;
		$common_methods = new Common_Methods;
		$options = get_option( AMCUST_SLUG_U, array() );

		// Set menu items to be excluded from title renaming. These are from WordPress core.
		$renaming_not_allowed = array( 'menu-dashboard', 'menu-pages', 'menu-posts', 'menu-media', 'menu-comments', 'menu-appearance', 'menu-plugins', 'menu-users', 'menu-tools', 'menu-settings' );

		// Get custom menu item titles
		if ( array_key_exists( 'custom_menu_titles', $options ) ) {
			$custom_menu_titles = $options['custom_menu_titles'];
			$custom_menu_titles = explode( ',', $custom_menu_titles );
		} else {
			$custom_menu_titles = array();
		}	

		// Get hidden menu items
		if ( array_key_exists( 'custom_menu_hidden', $options ) ) {
			$hidden_menu = $options['custom_menu_hidden'];
			$hidden_menu = explode( ',', $hidden_menu );
		} else {
			$hidden_menu = array();
		}

		$i = 1;

		// Check if there's an existing custom menu order data stored in options

		if ( array_key_exists( 'custom_menu_order', $options ) ) {

			$custom_menu = $options['custom_menu_order'];
			$custom_menu = explode( ',', $custom_menu );

			$menu_key_in_use = array();

			// Render sortables with data in custom menu order

			foreach ( $custom_menu as $custom_menu_item ) {

				foreach ( $menu as $menu_key => $menu_info ) {

					if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
						$menu_item_id = $menu_info[2];
					} else {
						$menu_item_id = $menu_info[5];
					}

					if ( $custom_menu_item == $menu_item_id ) {

						?>
						<li id="<?php echo esc_attr( $menu_item_id ); ?>" class="menu-item menu-item-depth-0">
							<div class="menu-item-bar">
								<div class="menu-item-handle ui-sortable-handle">
									<div class="item-title">
										<span class="menu-item-title">
						<?php

						if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
							$separator_name = $menu_info[2];
							$separator_name = str_replace( 'separator', 'Separator-', $separator_name );
							$separator_name = str_replace( '--last', '-Last', $separator_name );
							echo '~~ ' . esc_html( $separator_name ) . ' ~~';
						} else {
							if ( in_array( $menu_item_id, $renaming_not_allowed ) ) {
								echo wp_kses_post( $common_methods->strip_html_tags_and_content( $menu_info[0] ) );
							} else {

								// Get defaul/custom menu item title
								foreach ( $custom_menu_titles as $custom_menu_title ) {

									// At this point, $custom_menu_title value looks like toplevel_page_snippets__Code Snippets

									$custom_menu_title = explode( '__', $custom_menu_title );

									if ( $custom_menu_title[0] == $menu_item_id ) {										$menu_item_title = $common_methods->strip_html_tags_and_content( $custom_menu_title[1] ); // e.g. Code Snippets
										break; // stop foreach loop so $menu_item_title is not overwritten in the next iteration
									} else {
										$menu_item_title = $common_methods->strip_html_tags_and_content( $menu_info[0] );
									}

								}

								?>
								<input type="text" value="<?php echo wp_kses_post( $menu_item_title ); ?>" class="menu-item-custom-title" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>">
								<?php
							}
						}

						?>
										</span>
										<label class="menu-item-checkbox-label">
											<?php
												if ( in_array( $custom_menu_item, $hidden_menu ) ) {
												?>
											<input type="checkbox" class="menu-item-checkbox" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>" checked>
											<span>Hide</span>
												<?php
												} else {
												?>
											<input type="checkbox" class="menu-item-checkbox" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>">
											<span>Hide</span>
												<?php
												}
											?>
										</label>
									</div>
								</div>
							</div>
						<?php

						$i = 1;

						if ( array_key_exists( $menu_info[2], $submenu ) && @is_countable( $submenu[$menu_info[2]] ) && @sizeof( $submenu[$menu_info[2]] ) > 0 ) {
							?>
							<div class="menu-item-settings wp-clearfix" style="display:none;">
							<?php

							foreach ( $submenu[$menu_info[2]] as $submenu_item ) {

								$i++;

								// echo $submenu_item[0];

							}
							?>
							</div>
							<?php

						}
						?>
						</li>

						<?php

						$menu_key_in_use[] = $menu_key;

					}

				}

			}

			// Render the rest of the current menu towards the end of the sortables

			foreach ( $menu as $menu_key => $menu_info ) {

				if ( ! in_array( $menu_key, $menu_key_in_use ) ) {

					$menu_item_id = $menu_info[5];
					$menu_item_title = $menu_info[0];

					// Exclude Show All/Less toggles

					if ( false === strpos( $menu_item_id, 'toplevel_page_amcust_' ) ) {

						?>
						<li id="<?php echo esc_attr( $menu_item_id ); ?>" class="menu-item menu-item-depth-0">
							<div class="menu-item-bar">
								<div class="menu-item-handle ui-sortable-handle">
									<div class="item-title">
										<span class="menu-item-title">
											<input type="text" value="<?php echo wp_kses_post( $menu_item_title ); ?>" class="menu-item-custom-title" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>">
										</span>
										<label class="menu-item-checkbox-label">
											<input type="checkbox" class="menu-item-checkbox" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>">
											<span>Hide</span>
										</label>
									</div>
								</div>
							</div>
						<?php

					}

					$i = 1;

					if ( array_key_exists( $menu_info[2], $submenu ) && @is_countable( $submenu[$menu_info[2]] ) && @sizeof( $submenu[$menu_info[2]] ) > 0 ) {
						?>
						<div class="menu-item-settings wp-clearfix" style="display:none;">
						<?php

						foreach ( $submenu[$menu_info[2]] as $submenu_item ) {

							$i++;

							// echo $submenu_item[0];

						}
						?>
						</div>
						<?php

					}
					?>
					</li>

					<?php

				}

			}

		} else {

			// Render sortables with existing items in the admin menu

			foreach ( $menu as $menu_key => $menu_info ) {

				if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
					$menu_item_id = $menu_info[2];
				} else {
					$menu_item_id = $menu_info[5];
				}

				?>
				<li id="<?php echo esc_attr( $menu_item_id ); ?>" class="menu-item menu-item-depth-0">
					<div class="menu-item-bar">
						<div class="menu-item-handle ui-sortable-handle">
							<div class="item-title">
								<span class="menu-item-title">
				<?php

				if ( false !== strpos( $menu_info[4], 'wp-menu-separator' ) ) {
					$separator_name = $menu_info[2];
					$separator_name = str_replace( 'separator', 'Separator-', $separator_name );
					$separator_name = str_replace( '--last', '-Last', $separator_name );
					echo '~~ ' . esc_html( $separator_name ) . ' ~~';
				} else {
					if ( in_array( $menu_item_id, $renaming_not_allowed ) ) {
							echo wp_kses_post( $common_methods->strip_html_tags_and_content( $menu_info[0] ) );
					} else {
						?>
						<input type="text" value="<?php echo wp_kses_post( $common_methods->strip_html_tags_and_content( $menu_info[0] ) ); ?>" class="menu-item-custom-title" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>">
						<?php
					}
				}

				?>
								</span>
								<label class="menu-item-checkbox-label">
									<input type="checkbox" class="menu-item-checkbox" data-menu-item-id="<?php echo esc_attr( $menu_item_id ); ?>">
									<span>Hide</span>
								</label>
							</div>
						</div>
					</div>
				<?php

				$i = 1;

				if ( array_key_exists( $menu_info[2], $submenu ) && @is_countable( $submenu[$menu_info[2]] ) && @sizeof( $submenu[$menu_info[2]] ) > 0 ) {
					?>
					<div class="menu-item-settings wp-clearfix" style="display:none;">
					<?php

					foreach ( $submenu[$menu_info[2]] as $submenu_item ) {

						$i++;

						// echo $submenu_item[0];

					}
					?>
					</div>
					<?php

				}
				?>
				</li>

				<?php

			}


		}


		?>
		</ul>
		<?php

		$field_id = $args['field_id'];
		$field_name = $args['field_name'];
		$field_description = $args['field_description'];
		$field_option_value = ( isset( $options[$args['field_id']] ) ) ? $options[$args['field_id']] : '';

		// Hidden input field to store custom menu order (from options as is, or sortupdate) upon clicking Save Changes. 
		echo '<input type="hidden" id="' . esc_attr( $field_name ) . '" class="amcust-subfield-text" name="' . esc_attr( $field_name ) . '" value="' . esc_attr( $field_option_value ) . '">';

		$field_id = 'custom_menu_titles';
		$field_name = AMCUST_SLUG_U . '['. $field_id .']';
		$field_option_value = ( isset( $options[$field_id] ) ) ? $options[$field_id] : '';

		// Hidden input field to store custom menu titles (from options as is, or custom values entered on each non-WP-default menu items.
		echo '<input type="hidden" id="' . esc_attr( $field_name ) . '" class="amcust-subfield-text" name="' . esc_attr( $field_name ) . '" value="' . esc_attr( $field_option_value ) . '">';

		$field_id = 'custom_menu_hidden';
		$field_name = AMCUST_SLUG_U . '['. $field_id .']';
		$field_option_value = ( isset( $options[$field_id] ) ) ? $options[$field_id] : '';

		// Hidden input field to store hidden menu itmes (from options as is, or 'Hide' checkbox clicks) upon clicking Save Changes.
		echo '<input type="hidden" id="' . esc_attr( $field_name ) . '" class="amcust-subfield-text" name="' . esc_attr( $field_name ) . '" value="' . esc_attr( $field_option_value ) . '">';

	}

}