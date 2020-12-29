<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

namespace Module\System\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

/**
 * Asset admin controller
 *
 * Feature list:
 *
 *  - List of asset folders
 *  - Publish a component's asset
 *
 * @author Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
 */
class AssetController extends ActionController
{
    /**
     * List of assets
     *
     * @return void
     */
    public function indexAction()
    {
        // Get module list
        $modules = [];
        $rowset  = Pi::model('module')->select(['active' => 1]);
        foreach ($rowset as $row) {
            $modules[$row->name] = $row->title;
        }

        // Get theme list
        $themes = [];
        $rowset = Pi::model('theme')->select([]);
        foreach ($rowset as $row) {
            $themes[$row->name] = $row->name;
        }

        $this->view()->assign('modules', $modules);
        $this->view()->assign('themes', $themes);
        $this->view()->assign('title', _a('Asset component list'));
        //$this->view()->setTemplate('asset-list');
    }

    /**
     * Publish assets of a component
     *
     * @return array Result pair of status and message
     */
    public function publishAction()
    {
        $type           = $this->params('type', 'module');
        $name           = $this->params('name');
        $result         = true;
        $errors         = [
            'remove'  => [],
            'publish' => [],
        ];
        $canonizeResult = function ($type, $status) use (&$errors, &$result) {
            if (!$status) {
                $errors[$type] = Pi::service('asset')->getErrors();
                $result        = $status;
            }
        };
        switch ($type) {
            case 'module':
                $status = Pi::service('asset')->remove('module/' . $name);
                $canonizeResult('remove', $status);
                $status = Pi::service('asset')->publishModule($name);
                $canonizeResult('publish', $status);
                break;
            case 'theme':
                $status = Pi::service('asset')->remove('theme/' . $name);
                $canonizeResult('remove', $status);
                $status = Pi::service('asset')->publishTheme($name);
                $canonizeResult('publish', $status);
                break;
            default:
                $component = sprintf('%s/%s', $type, $name);
                $status    = Pi::service('asset')->remove($component);
                $canonizeResult('remove', $status);
                $status = Pi::service('asset')->publish($component);
                $canonizeResult('publish', $status);
                break;
        }
        clearstatcache();

        $compiledFilesPath = Pi::host()->path('asset/compiled');



        if (!$result) {
            if (!empty($errors['publish']) && !empty($errors['remove'])) {
                $message = $this->renderMessage(
                    _a('Some old files were not able to remove and asset publish was not completed.'),
                    array_merge($errors['remove'], $errors['publish'])
                );
            } elseif (!empty($errors['publish'])) {
                $message = $this->renderMessage(
                    _a('Asset publish was not completed, please check and copy manually.'),
                    $errors['publish']
                );
            } elseif (!empty($errors['remove'])) {
                $message = $this->renderMessage(
                    _a('Asset publish was not completed because some old files were not able to remove.'),
                    $errors['remove']
                );
            } else {
                $message = $this->renderMessage(
                    _a('Asset files are not published correctly, please copy asset files manually.')
                );
            }
        } else {
            $message = $this->renderMessage(
                _a('Asset files are published correctly.'),
                [],
                'success'
            );
        }

        //$this->redirect()->toRoute('', array('action' => 'index'));
        //return;

        return [
            'status'  => $status,
            'message' => $message,
        ];
    }

    /**
     * Refresh assets of all modules and themes
     *
     * @return array Result pair of status and message
     */
    public function refreshAction()
    {
        $modules = Pi::registry('module')->read();
        $themes  = Pi::registry('theme')->read();

        $result         = true;
        $errors         = [
            'remove'  => [],
            'publish' => [],
        ];
        $canonizeResult = function ($type, $status) use (&$errors, &$result) {
            if (!$status) {
                $errors[$type] = Pi::service('asset')->getErrors();
                $result        = $status;
            }
        };

        // Republish all module/theme components
        $erroneous = [];
        foreach (array_keys($modules) as $name) {
            $status = Pi::service('asset')->remove('module/' . $name);
            if (!$status) {
                $erroneous[] = 'Remove: module-' . $name;
                $canonizeResult('remove', $status);
            }
            $status = Pi::service('asset')->publishModule($name);
            if (!$status) {
                $erroneous[] = 'Publish: module-' . $name;
                $canonizeResult('publish', $status);
            }
        }
        foreach (array_keys($themes) as $name) {
            $status = Pi::service('asset')->remove('theme/' . $name);
            if (!$status) {
                $erroneous[] = 'Remove: theme-' . $name;
                $canonizeResult('remove', $status);
            }
            $status = Pi::service('asset')->publishTheme($name);
            if (!$status) {
                $erroneous[] = 'Publish: theme-' . $name;
                $canonizeResult('publish', $status);
            }
        }
        clearstatcache();

        /**
         * Remove compiled files
         */
        $compiledDir = Pi::host()->path('asset/compiled');
        $files = glob($compiledDir . '/**/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file)) {
                unlink($file); // delete file
            }
        }

        if (!$result) {
            $message = $this->renderMessage(
                _a('Publish is not completed, please check and re-publish one by one.'),
                $erroneous
            );
        } else {
            $message = $this->renderMessage(
                _a('All assets published successfully.'),
                [],
                'success'
            );
        }

        return [
            'status'  => $result,
            'message' => $message,
        ];
    }

    /**
     * Renders errors
     *
     * @param string $title
     * @param array $errors
     *
     * @param string $type
     *
     * @return string
     */
    protected function renderMessage($title, $errors = [], $type = 'error')
    {
        switch ($type) {
            case 'error':
                $class = 'danger';
                break;
            default:
                $class = $type ?: 'info';
                break;
        }

        if (!$errors) {
            $message = _escape($title);
        } else {
            $patternPanel
                = <<<'EOT'
<div class="card card-%s">
  <div class="card-heading">%s</div>

  <ul class="list-group">
    %s
  </ul>
</div>
EOT;
            $patternList
                = <<<'EOT'
    <li class="list-group-item">%s</li>
EOT;

            $list = '';
            foreach ($errors as $error) {
                $list .= sprintf($patternList, _escape($error)) . PHP_EOL;
            }
            $message = sprintf($patternPanel, $class, _escape($title), $list);
        }

        return $message;
    }
}
