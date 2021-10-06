<?php
/**
 * Post trigger abstract
 *
 * @package notification
 */

namespace BracketSpace\Notification\Defaults\Trigger\Post;

use BracketSpace\Notification\Abstracts;
use BracketSpace\Notification\Defaults\MergeTag;
use BracketSpace\Notification\Traits;

/**
 * Post trigger class
 */
abstract class PostTrigger extends Abstracts\Trigger {

	use Traits\Cache;

	/**
	 * Post Type slug
	 *
	 * @var string
	 */
	protected $post_type;

	/**
	 * Constructor
	 *
	 * @param array $params trigger configuration params.
	 */
	public function __construct( $params = [] ) {

		if ( ! isset( $params['post_type'], $params['slug'], $params['name'] ) ) {
			trigger_error( 'PostTrigger requires post_type, slug and name params.', E_USER_ERROR );
		}

		$this->post_type = $params['post_type'];

		parent::__construct( $params['slug'], $params['name'] );

		$this->set_group( $this->get_current_post_type_name() );

	}

	/**
	 * Postponed action
	 *
	 * @since  5.3.0
	 * @param  mixed $post_id Post ID or string if is a revision.
	 * @return mixed          void or false
	 */
	public function postponed_action( $post_id ) {

		// Bail if different post type, like post revision.
		if ( get_post_type( $post_id ) !== $this->post_type ) {
			return false;
		}

	}

	/**
	 * Registers attached merge tags
	 *
	 * @return void
	 */
	public function merge_tags() {

		$post_name = $this->get_current_post_type_name();

		$this->add_merge_tag( new MergeTag\Post\PostID( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostPermalink( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostTitle( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostSlug( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostContent( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostContentHtml( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostExcerpt( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\PostStatus( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\ThumbnailUrl( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\FeaturedImageUrl( [
			'post_type' => $this->post_type,
		] ) );

		$this->add_merge_tag( new MergeTag\Post\FeaturedImageId( [
			'post_type' => $this->post_type,
		] ) );

		if ( 'post' === $this->post_type ) {

			$this->add_merge_tag( new MergeTag\StringTag( [
				'slug'     => $this->post_type . '_sticky',
				// translators: singular post name.
				'name'     => sprintf( __( '%s sticky status', 'notification' ), $post_name ),
				'resolver' => function( $trigger ) {
					if ( is_admin() ) {
						return isset( $_POST['sticky'] ) && ! empty( $_POST['sticky'] ) ? __( 'Sticky', 'notification' ) : __( 'Not sticky', 'notification' ); // phpcs:ignore
					} else {
						return is_sticky( $trigger->{ $this->post_type }->ID ) ? __( 'Sticky', 'notification' ) : __( 'Not sticky', 'notification' );
					}
				},
				'group'    => $this->get_current_post_type_name(),
			] ) );

		}

		$taxonomies = get_object_taxonomies( $this->post_type, 'objects' );

		if ( ! empty( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {

				// Post format special treatment.
				if ( 'post_format' === $taxonomy->name ) {
					$group = $this->get_current_post_type_name();
				} else {
					$group = __( 'Taxonomies', 'notification' );
				}

				$this->add_merge_tag( new MergeTag\Post\PostTerms( [
					'post_type' => $this->post_type,
					'taxonomy'  => $taxonomy,
					'group'     => $group,
				] ) );

			}
		}

		$this->add_merge_tag( new MergeTag\DateTime\DateTime( [
			'slug' => $this->post_type . '_creation_datetime',
			// translators: singular post name.
			'name' => sprintf( __( '%s creation date and time', 'notification' ), $post_name ),
		] ) );

		$this->add_merge_tag( new MergeTag\DateTime\DateTime( [
			'slug' => $this->post_type . '_modification_datetime',
			// translators: singular post name.
			'name' => sprintf( __( '%s modification date and time', 'notification' ), $post_name ),
		] ) );

		// Author.
		$this->add_merge_tag( new MergeTag\User\UserID( [
			'slug'          => $this->post_type . '_author_user_ID',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user ID', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserLogin( [
			'slug'          => $this->post_type . '_author_user_login',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user login', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserEmail( [
			'slug'          => $this->post_type . '_author_user_email',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user email', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserNicename( [
			'slug'          => $this->post_type . '_author_user_nicename',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user nicename', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserDisplayName( [
			'slug'          => $this->post_type . '_author_user_display_name',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user display name', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserFirstName( [
			'slug'          => $this->post_type . '_author_user_firstname',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user first name', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserLastName( [
			'slug'          => $this->post_type . '_author_user_lastname',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user last name', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\Avatar( [
			'slug'          => $this->post_type . '_author_user_avatar',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user avatar', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserRole( [
			'slug'          => $this->post_type . '_author_user_role',
			// translators: singular post name.
			'name'          => sprintf( __( '%s author user role', 'notification' ), $post_name ),
			'property_name' => 'author',
			'group'         => __( 'Author', 'notification' ),
		] ) );

		// Last updated by.
		$this->add_merge_tag( new MergeTag\User\UserID( [
			'slug'          => $this->post_type . '_last_editor_ID',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor ID', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserLogin( [
			'slug'          => $this->post_type . '_last_editor_login',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor login', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserEmail( [
			'slug'          => $this->post_type . '_last_editor_email',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor email', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserNicename( [
			'slug'          => $this->post_type . '_last_editor_nicename',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor nicename', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserDisplayName( [
			'slug'          => $this->post_type . '_last_editor_display_name',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor display name', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserFirstName( [
			'slug'          => $this->post_type . '_last_editor_firstname',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor first name', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserLastName( [
			'slug'          => $this->post_type . '_last_editor_lastname',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor last name', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\Avatar( [
			'slug'          => $this->post_type . '_last_editor_avatar',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor avatar', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );

		$this->add_merge_tag( new MergeTag\User\UserRole( [
			'slug'          => $this->post_type . '_last_editor_role',
			// translators: singular post name.
			'name'          => sprintf( __( '%s last editor role', 'notification' ), $post_name ),
			'property_name' => 'last_editor',
			'group'         => __( 'Last editor', 'notification' ),
		] ) );



        if ( 'recall' === $this->post_type ) {

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'comment',
                'name'        => __( 'Comment', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'comment', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );


            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'contact',
                'name'        => __( 'Contact', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'contact', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'name_client',
                'name'        => __( 'Name client', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'name_client', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

        }

        if ( 'leads' === $this->post_type ) {

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'status',
                'name'        => __( 'Status', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'status', true)->post_title;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'from_country',
                'name'        => __( 'From Country', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'from_country', true)->post_title;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'from_city',
                'name'        => __( 'From City', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'from_city', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'to_city',
                'name'        => __( 'To City', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'to_city', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'from_storage',
                'name'        => __( 'From storage', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'from_storage', true)->post_title;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'delivery_route',
                'name'        => __( 'Delivery Route', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'delivery_route', true)->post_title;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'estimated_shipping_cost',
                'name'        => __( 'Estimated shipping cost', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'estimated_shipping_cost', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'client_name',
                'name'        => __( 'Client Name', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'client_name', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'phone',
                'name'        => __( 'Phone', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'phone_text', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'email',
                'name'        => __( 'Email', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'email', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'cargo_volume',
                'name'        => __( 'Cargo volume', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'cargo_volume', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'cargo_weight',
                'name'        => __( 'Cargo weight', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'cargo_weight', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'cargo_type',
                'name'        => __( 'Cargo type', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'cargo_type', true)->post_title;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'cargo_type_client',
                'name'        => __( 'Cargo type of client', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'cargo_type_client', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'name_variable',
                'name'        => __( 'Name variable', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'name_variable', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'count_variable',
                'name'        => __( 'Count variable', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'count_variable', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'need_container',
                'name'        => __( 'Need container?', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'need_container', true) ? 'Да' : 'Нет';
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'referer',
                'name'        => __( 'Referer', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'referer', true);
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

        }

        if ( 'manager_break' === $this->post_type ) {

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'email_manager',
                'name'        => __( 'Email Manager', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'manager', true)->user_email;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'manager_name',
                'name'        => __( 'Manager name', 'notification' ),
                'resolver' => function( $trigger ) {
                    return get_post_meta($trigger->{ $trigger->get_post_type() }->ID, 'manager', true)->display_name;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

        }

        if ( 'statuses_for_leads' === $this->post_type ) {

            $this->add_merge_tag( new MergeTag\StringTag( [
                'slug'        => 'current_manager_name',
                'name'        => __( 'Current manager name', 'notification' ),
                'resolver' => function( $trigger ) {
                    $user = get_user_by('ID',get_option('current_manager'));
                    return $user->display_name;
                },
                'group'    => $this->get_current_post_type_name(),
            ] ) );

        }

	}

}
