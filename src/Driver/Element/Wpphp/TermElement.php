<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpphp;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use UnexpectedValueException;

/**
 * WP-API driver element for taxonomy terms.
 */
class TermElement extends BaseElement
{
    /**
     * Create an item for this element.
     *
     * @param array $args Data used to create an object.
     *
     * @throws \UnexpectedValueException
     *
     * @return \WP_Term The new item.
     */
    public function create(array $args): \WP_Term
    {
        $args = wp_slash($args);
        $term = wp_insert_term($args['term'], $args['taxonomy'], $args);

        if (is_wordpress_error($term)) {
            throw new UnexpectedValueException(sprintf('Failed creating a new term: %s', $term->get_error_message()));
        }

        return $this->get($term['term_id']);
    }

    /**
     * Retrieve an item for this element.
     *
     * @param int $id Object ID.
     * @param array $args Optional data used to fetch an object.
     *
     * @throws \UnexpectedValueException
     *
     * @return \WP_Term The item.
     */
    public function get(int $id, array $args = []): \WP_Term
    {
        $term = get_term($id, $args['taxonomy']);

        if (! $term) {
            throw new UnexpectedValueException(sprintf('Could not find term with ID %d', $id));
        } elseif (is_wp_error($term)) {
            throw new UnexpectedValueException(
                sprintf('Could not find term with ID %d: %s', $id, $term->get_error_message())
            );
        }

        return $term;
    }

    /**
     * Delete an item for this element.
     *
     * @param int $id Object ID.
     * @param array $args Optional data used to delete an object.
     *
     * @throws \UnexpectedValueException
     */
    public function delete(int $id, array $args = [])
    {
        $result = wp_delete_term($id, $args['taxonomy']);

        if (is_wordpress_error($result)) {
            throw new UnexpectedValueException(
                sprintf('Failed deleting a new term: %s', $result->get_error_message())
            );
        }
    }
}
