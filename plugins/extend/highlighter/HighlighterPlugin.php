<?php

namespace SunlightExtend\Highlighter;

use Sunlight\Action\ActionResult;
use Sunlight\Database\Database as DB;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Util\Form;

/**
 * Highlighter plugin
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class HighlighterPlugin extends ExtendPlugin
{

    /** @var array */
    private $types = [
        _page_section => 'section',
        _page_category => 'category',
        _page_book => 'book',
        _page_group => 'group',
        _page_forum => 'forum',
        _page_plugin => 'plugin',
    ];

    protected function getConfigDefaults(): array
    {
        return [
            // stranky
            'style' => 'default',
            'in_section' => false,
            'in_category' => false,
            'in_book' => false,
            'in_group' => false,
            'in_forum' => true,
            'in_plugin' => false,
            'in_module' => false,
        ];
    }

    /**
     * @param array $args
     */
    public function onHead(array $args): void
    {
        global $_index, $_page;

        if (
            $_index['type'] === _index_page
            && isset($this->types[$_page['type']])
            && $this->getConfig()->offsetGet('in_' . $this->types[$_page['type']]
            )
            || ($_index['type'] === _index_plugin && $this->getConfig()->offsetGet('in_plugin'))
            || ($_index['type'] === _index_module && $this->getConfig()->offsetGet('in_module'))
        ) {
            $args['css'][] = $this->getWebPath() . '/Resources/styles/' . $this->getConfig()->offsetGet('style') . '.css';
            $args['js'][] = $this->getWebPath() . '/Resources/highlight.pack.js';
            $args['js_after'] .= "\n<script>$(document).ready(function(){ $('span.pre').each(function(i, block) {hljs.highlightBlock(block);});});</script>";
        }
    }

    public function getAction(string $name): PluginAction
    {
        if ($name == 'config') {
            return new CustomConfig($this);
        }
        return parent::getAction($name);
    }
}

class CustomConfig extends ConfigAction
{

    protected function execute(): ActionResult
    {
        // automatic increment cache (enforce reload css)
        if (!_debug && (isset($_POST['save']) || isset($_POST['reset']))) {
            DB::update(_setting_table, "var=" . DB::val('cacheid'), ['val' => DB::raw('val+1')]);
        }
        return parent::execute();
    }

    protected function getFields(): array
    {
        // load all available styles
        $styles = [];
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . "Resources/styles/*.css") as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $styles[$name] = $name;
        }

        $cfg = $this->plugin->getConfig();

        return [
            'style' => [
                'label' => _lang('highlighter.style'),
                'input' => $this->createSelect('style', $styles, $cfg->offsetGet('style')),
                'type' => 'text'
            ],
            'in_section' => [
                'label' => _lang('highlighter.in_section'),
                'input' => '<input type="checkbox" name="config[in_section]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_section')) . '>',
                'type' => 'checkbox'
            ],
            'in_category' => [
                'label' => _lang('highlighter.in_category'),
                'input' => '<input type="checkbox" name="config[in_category]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_category')) . '>',
                'type' => 'checkbox'
            ],
            'in_book' => [
                'label' => _lang('highlighter.in_book'),
                'input' => '<input type="checkbox" name="config[in_book]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_book')) . '>',
                'type' => 'checkbox'
            ],
            'in_group' => [
                'label' => _lang('highlighter.in_group'),
                'input' => '<input type="checkbox" name="config[in_group]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_group')) . '>',
                'type' => 'checkbox'
            ],
            'in_forum' => [
                'label' => _lang('highlighter.in_forum'),
                'input' => '<input type="checkbox" name="config[in_forum]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_forum')) . '>',
                'type' => 'checkbox'
            ],
            'in_plugin' => [
                'label' => _lang('highlighter.in_plugin'),
                'input' => '<input type="checkbox" name="config[in_plugin]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_plugin')) . '>',
                'type' => 'checkbox'
            ],
            'in_module' => [
                'label' => _lang('highlighter.in_module'),
                'input' => '<input type="checkbox" name="config[in_module]" value="1"' . Form::activateCheckbox($cfg->offsetGet('in_module')) . '>',
                'type' => 'checkbox'
            ],
        ];
    }

    private function createSelect($name, $options, $default): string
    {
        $result = "<select name='config[" . $name . "]'>";
        foreach ($options as $k => $v) {
            $result .= "<option value='" . $v . "'" . ($default == $v ? " selected" : "") . ">" . $k . "</option>";
        }
        $result .= "</select>";
        return $result;
    }
}
