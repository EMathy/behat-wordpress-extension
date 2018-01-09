<?php
namespace PaulGibbs\WordpressBehatExtension\Driver\Element\Wpphp;

use PaulGibbs\WordpressBehatExtension\Driver\Element\BaseElement;
use UnexpectedValueException;

/**
 * WP-API driver element for themes.
 */
class ThemeElement extends BaseElement
{
    /**
     * Switch active theme.
     *
     * @param string $id Theme name to switch to.
     * @param array $args Not used.
     *
     * @throws \UnexpectedValueException
     */
    public function update(string $id, array $args = [])
    {
        $theme = wp_get_theme($id);

        if (! $theme->exists()) {
            throw new UnexpectedValueException(sprintf('Could not find theme %s', $id));
        }

        switch_theme($theme->get_template());
    }


    /*
     * Convenience methods.
     */

    /**
     * Alias of update().
     *
     * @see update()
     *
     * @param string $id   Theme name to switch to.
     * @param array  $args Not used.
     */
    public function change(string $id, array $args = [])
    {
        $this->update($id, $args);
    }
}
