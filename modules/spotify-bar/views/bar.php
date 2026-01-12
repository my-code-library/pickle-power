<?php
/**
 * Default Spotify bar template.
 *
 * Can be overridden by placing a copy in:
 * wp-content/themes/your-theme/picklepower/spotify-bar/bar.php
 *
 * Available variable:
 * - $spotify_url (string)
 */

if ( empty( $spotify_url ) ) {
    return;
}
?>

<div class="picklepower-spotify-bar">
    <div class="picklepower-spotify-bar__inner">
        <span class="picklepower-spotify-bar__label">
            🎧 Latest release on Spotify
        </span>
        <a href="<?php echo esc_url( $spotify_url ); ?>"
           target="_blank"
           rel="noopener"
           class="picklepower-spotify-bar__button">
            Listen now
        </a>
    </div>
</div>
