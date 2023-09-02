<?php

use Sunlight\Page\Page;
use Sunlight\WebState;

return function (array $args) {
    global $_index, $_page;

    if (
        (
            $_index->type === WebState::PAGE
            && isset(Page::TYPES[$_page['type']])
            && $this->getConfig()['in_' . Page::TYPES[$_page['type']]]
        )
        || ($_index->type === WebState::PLUGIN && $this->getConfig()['in_plugin'])
        || ($_index->type === WebState::MODULE && $this->getConfig()['in_module'])
    ) {
        $args['css'][] = $this->getAssetPath('public/styles/' . $this->getConfig()['style'] . '.css');
        $args['js'][] = $this->getAssetPath('public/highlight.pack.js');
        $args['js_after'] .= "\n<script>$(document).ready(function(){ $('span.pre').each(function(i, block) {hljs.highlightBlock(block);});});</script>";
    }
};