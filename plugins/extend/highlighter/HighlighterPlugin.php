<?php

namespace SunlightExtend\Highlighter;

use Sunlight\Plugin\ExtendPlugin;

/**
 * Highlighter plugin
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class HighlighterPlugin extends ExtendPlugin
{

    /** @var array */
    private $types = array(
        _page_section => 'section',
        _page_category => 'category',
        _page_book => 'book',
        _page_group => 'group',
        _page_forum => 'forum',
        _page_plugin => 'plugin',
    );

    protected function getConfigDefaults()
    {
        return array(
            // stranky
            'in_section' => false,
            'in_category' => false,
            'in_book' => false,
            'in_group' => false,
            'in_forum' => true,
            'in_plugin' => false,
            'in_module' => false,

        );
    }

    /**
     * @param array $args
     */
    public function onHead(array $args)
    {
        global $_index, $_page;

        if ($_index['is_page'] && isset($this->types[$_page['type']]) && $this->getConfig()->offsetGet('in_' . $this->types[$_page['type']])
            || ($_index['is_plugin'] && $this->getConfig()->offsetGet('in_plugin'))
            || ($_index['is_module'] && $this->getConfig()->offsetGet('in_module'))
        ) {
            $args['css'][] = $this->getWebPath() . '/Resources/styles/default.css';
            $args['js'][] = $this->getWebPath() . '/Resources/highlight.pack.js';
            $args['js_after'] .= "\n<script>$(document).ready(function(){ $('span.pre').each(function(i, block) {hljs.highlightBlock(block);});});</script>";
        }
    }
}
