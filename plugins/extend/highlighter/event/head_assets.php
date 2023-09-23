<?php

use Sunlight\Page\Page;
use Sunlight\WebState;

return function (array $args) {
    global $_index, $_page;

    $config = $this->getConfig();

    if (
        (
            $_index->type === WebState::PAGE
            && isset(Page::TYPES[$_page['type']])
            && isset($config['in_' . Page::TYPES[$_page['type']]])
            && $config['in_' . Page::TYPES[$_page['type']]]
        )
        || ($_index->type === WebState::PLUGIN && $config['in_plugin'])
        || ($_index->type === WebState::MODULE && $config['in_module'])
    ) {
        $args['css'][] = $this->getAssetPath('public/styles/' . $config['style'] . '.css');
        $args['js'][] = $this->getAssetPath('public/highlight.pack.js');
        $args['js_after'] .= "\n<script>$(document).ready(function(){ $('span.pre').each(function(i, block) {hljs.highlightBlock(block);});});</script>";
    }
};