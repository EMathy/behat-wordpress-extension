<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpcli;

use RuntimeException;
use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;

/**
 * WP-CLI driver element for manipulating the database directly.
 */
class DatabaseElement extends BaseElement
{
    /**
     * Export site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     *
     * @return string Path to the database dump.
     */
    public function get(int $id, array $args = []): string
    {
        $wpcli_args = ['--porcelain', '--add-drop-table'];

        if (! empty($args['path'])) {
            $file = tempnam($args['path'], 'wordhat');
            if ($file) {
                array_unshift($wpcli_args, $file);
            }
        };

        // Protect against WP-CLI changing the filename.
        $path = $this->drivers->getDriver()->wpcli('db', 'export', $wpcli_args)['stdout'];
        if (! $path) {
            throw new RuntimeException('Could not export database.');
        }

        return $path;
    }

    /**
     * Import site database.
     *
     * @param int   $id   Not used.
     * @param array $args
     */
    public function update(int $id, array $args = [])
    {
        $this->drivers->getDriver()->wpcli('db', 'import', [$args['path']]);

        /*
         * The WPPHP driver needs the WP cache flushed at this point. However
         * WPCLI appears to function without it. This is a note to remind us
         * so that if we see any strange behaviour with caching which is WPCLI
         * specific we might catch it. There's a discussion about it here:
         *
         * https://github.com/paulgibbs/behat-wordpress-extension/pull/150
         */
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of get().
     *
     * @see get()
     *
     * @param int   $id   Not used.
     * @param array $args
     *
     * @return string Path to the export file.
     */
    public function export(int $id, array $args = []): string
    {
        return $this->get($id, $args);
    }

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param int   $id   Not used.
     * @param array $args
     */
    public function import(int $id, array $args = [])
    {
        $this->update($id, $args);
    }
}
