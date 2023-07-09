<?php

namespace SunlightExtend\Highlighter;

use Sunlight\Action\ActionResult;
use Sunlight\Core;
use Sunlight\Database\Database as DB;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\Util\Form;

class ConfigAction extends BaseConfigAction
{

    protected function execute(): ActionResult
    {
        // automatic increment cache (enforce reload css)
        if (!Core::$debug && (isset($_POST['save']) || isset($_POST['reset']))) {
            DB::update('setting', "var=" . DB::val('cacheid'), ['val' => DB::raw('val+1')]);
        }
        return parent::execute();
    }

    protected function getFields(): array
    {
        // load all available styles
        $styles = [];
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . "public/styles/*.css") as $file) {
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