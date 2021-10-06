<?php
/**
 * This is the output for meta on the page.
 *
 * @since 4.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
// phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect
// phpcs:disable Generic.WhiteSpace.ScopeIndent.IncorrectExact
$description           = aioseo()->helpers->encodeOutputHtml( aioseo()->meta->description->getDescription() );
$robots                = $this->robots->meta();
$keywords              = $this->keywords->getKeywords();
$canonical             = aioseo()->helpers->canonicalUrl();
$links                 = $this->links->getLinks();
?>
<?php if ( $description ) : ?>
		<meta name="description" content="<?php echo esc_attr( $description ); ?>" />
<?php endif; ?>
<?php if ( $robots != 'max-image-preview:none' ) { ?>
		<meta name="robots" content="<?php echo esc_html( $robots ); ?>" />
<? } ?>
<?php
// Adds the site verification meta for webmaster tools.
foreach ( $this->verification->meta() as $metaName => $value ) :
	?>
		<meta name="<?php echo esc_attr( $metaName ); ?>" content="<?php echo esc_attr( trim( wp_strip_all_tags( $value ) ) ); ?>" />
<?php
endforeach;
/*if ( ! empty( $keywords ) ) :
	?>
		<meta name="keywords" content="<?php echo esc_attr( $keywords ); ?>" />
<?php
endif;*/
if ( ! empty( $canonical ) ) :
	?>
		<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>" />
<?php
endif;
if ( ! empty( $links['prev'] ) ) :
	?>
		<link rel="prev" href="<?php echo esc_url( $links['prev'] ); ?>" />
<?php
endif;
if ( ! empty( $links['next'] ) ) :
	?>
		<link rel="next" href="<?php echo esc_url( $links['next'] ); ?>" />
<?php
endif;

// This adds the miscellaneous verification to the head tag inside our comments.
// @TODO: [V4+] Maybe move this out of meta? Better idea would be to have a global wp_head where meta gets
// attached as well as other things like this:
if ( aioseo()->options->webmasterTools->miscellaneousVerification ) {
	echo "\n\t\t" . aioseo()->helpers->decodeHtmlEntities( aioseo()->options->webmasterTools->miscellaneousVerification ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
