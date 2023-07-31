<?php declare( strict_types=1 );

namespace WPTurbo\App\Core;

class SEO
{
    public function __construct()
    {
        // do nothing
    }
    public function init()
    {
        $this->setHooks();
    }
    public function setHooks()
    {
        if ( ! is_admin() ) {
            $this->setFrontendHooks();
        }
    }

    public function setFrontendHooks()
    {
        add_filter( 'pre_get_document_title', [$this, 'addCategoriesToProductHeadTitle'], 10000 );
        add_action( 'wp_head', [$this, 'addMetaTitle'], 5 );
        add_action( 'wp_head', [$this, 'addMetaDescription'], 5 );
        add_action( 'wp_head', [$this, 'addSchemaPostMetaToHead'], 5 );
    }

    public function addMetaDescription()
    {
        if (is_singular(['post', 'page', 'product'])) {
            global $post;
            $metaDescription = $post->post_excerpt;
        } else {
            $metaDescription = get_bloginfo( 'description' );
        }

        echo "\n".'<meta name="description" content="' . esc_attr( $metaDescription ) . '" />' . "\n";
    }

    public function addSchemaPostMetaToHead()
    {
        if (is_singular(['post', 'page', 'product'])) {
            $schema = get_post_meta(get_the_ID(), 'schema', true);
            if(!empty($schema)) {
                echo $schema;
            }
        }
    }

    public function addCategoriesToProductHeadTitle( $title ): string
    {
        return $this->getProductCategoriesAsTitlePart()!=='' ? $this->getProductCategoriesAsTitlePart() : $title;
    }

    // function to add meta title into wp_head with product categories name list
    public function addMetaTitle()
    {
        if ($this->getProductCategoriesAsTitlePart()!=='') {
            echo "\n".'<meta name="title" content="' . $this->getProductCategoriesAsTitlePart() . '" />' . "\n";
        }
    }

    public function getProductCategoriesAsTitlePart(): string
    {
        if (is_singular(['product'])) {
            global $post;
            $metaTitle = $post->post_title;
            // get product categories
            $terms = get_the_terms( $post->ID, 'product_cat' );
            if ( $terms && ! is_wp_error( $terms ) ) :
                $cat_names = array();
                foreach ( $terms as $term ) {
                    $cat_names[] = $term->name;
                }
                $metaTitle .= ' '.strtolower(implode(' & ', $cat_names));
            endif;

            return esc_attr( $metaTitle.' | '.get_bloginfo( 'name' ) );
        }

        return '';
    }
}
