<?php

namespace SunlightExtend\Highlighter;

use Sunlight\Action\ActionResult;
use Sunlight\Core;
use Sunlight\Database\Database as DB;
use Sunlight\Page\Page;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Util\Form;
use Sunlight\WebState;

class HighlighterPlugin extends ExtendPlugin
{

    /** @var array */
    private $types = [
        Page::SECTION => 'section',
        Page::CATEGORY => 'category',
        Page::BOOK => 'book',
        Page::GROUP => 'group',
        Page::FORUM => 'forum',
        Page::PLUGIN => 'plugin',
    ];

    public function onHead(array $args): void
    {
        global $_index, $_page;

        if (
            $_index->type === WebState::PAGE
            && isset($this->types[$_index->type])
            && $this->getConfig()->offsetGet('in_' . $this->types[$_index->type]
            )
            || ($_index->type === WebState::PLUGIN && $this->getConfig()->offsetGet('in_plugin'))
            || ($_index->type === WebState::MODULE && $this->getConfig()->offsetGet('in_module'))
        ) {
            $args['css'][] = $this->getWebPath() . '/public/styles/' . $this->getConfig()->offsetGet('style') . '.css';
            $args['js'][] = $this->getWebPath() . '/public/highlight.pack.js';
            $args['js_after'] .= "\n<script>$(document).ready(function(){ $('span.pre').each(function(i, block) {hljs.highlightBlock(block);});});</script>";
        }
    }
}
