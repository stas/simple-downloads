<?php wp_nonce_field( 'sdw', 'sdw_settings_nonce' ); ?>

<p>
    <label for="file_id">
        <strong><?php _e( 'File to download', 'sdw' ); ?></strong>
    </label>
    <?php if( !$file_ids ): ?>
        <br/>
        <em>
            <?php _e( 'To attach a file for this download page, upload some files and hit', 'sdw' )?>
            "<?php _e( 'Save Draft' ); ?>"
        </em>
    <?php else: ?>
        <select id="file_id" name="file_id" class="widefat">
            <option value="" <?php selected( '', $file_id ); ?>>
                <?php _e( 'None', 'sdw' ); ?>
            </option>
            <?php foreach ( $file_ids as $fid ): ?>
                <option value="<?php echo $fid->ID; ?>" <?php selected( $fid->ID, $file_id ); ?>>
                    <?php echo basename( $fid->guid ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</p>

<p>
    <label for="restrict_to">
        <strong><?php _e( 'User level', 'sdw' ); ?></strong>
    </label>
    <select id="restrict_to" name="restrict_to" class="widefat">
        <option value="" <?php selected( '', $restrict_to ); ?>>
            <?php _e( 'None', 'sdw' ); ?>
        </option>
        <?php wp_dropdown_roles( $restrict_to ); ?>
    </select>
</p>
