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
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . "../public/styles/*.css") as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $styles[$name] = $name;
        }

        $config = $this->plugin->getConfig();

        return [
            'style' => [
                'label' => _lang('highlighter.style'),
                'input' => Form::select('config[style]', $styles, $config['style'], ['class' => 'inputsmall']),
                'type' => 'text'
            ],
            'in_section' => [
                'label' => _lang('highlighter.in_section'),
                'input' => Form::input('checkbox', 'config[in_section]', '1', ['checked' => Form::loadCheckbox('config', $config['in_section'], 'in_section')]),
                'type' => 'checkbox'
            ],
            'in_category' => [
                'label' => _lang('highlighter.in_category'),
                'input' => Form::input('checkbox', 'config[in_category]', '1', ['checked' => Form::loadCheckbox('config', $config['in_category'], 'in_category')]),
                'type' => 'checkbox'
            ],
            'in_book' => [
                'label' => _lang('highlighter.in_book'),
                'input' => Form::input('checkbox', 'config[in_book]', '1', ['checked' => Form::loadCheckbox('config', $config['in_book'], 'in_book')]),
                'type' => 'checkbox'
            ],
            'in_group' => [
                'label' => _lang('highlighter.in_group'),
                'input' => Form::input('checkbox', 'config[in_group]', '1', ['checked' => Form::loadCheckbox('config', $config['in_group'], 'in_group')]),
                'type' => 'checkbox'
            ],
            'in_forum' => [
                'label' => _lang('highlighter.in_forum'),
                'input' => Form::input('checkbox', 'config[in_forum]', '1', ['checked' => Form::loadCheckbox('config', $config['in_forum'], 'in_forum')]),
                'type' => 'checkbox'
            ],
            'in_plugin' => [
                'label' => _lang('highlighter.in_plugin'),
                'input' => Form::input('checkbox', 'config[in_plugin]', '1', ['checked' => Form::loadCheckbox('config', $config['in_plugin'], 'in_plugin')]),
                'type' => 'checkbox'
            ],
            'in_module' => [
                'label' => _lang('highlighter.in_module'),
                'input' => Form::input('checkbox', 'config[in_module]', '1', ['checked' => Form::loadCheckbox('config', $config['in_module'], 'in_module')]),
                'type' => 'checkbox'
            ],
        ];
    }
}