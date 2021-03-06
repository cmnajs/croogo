<?php
/**
 * AclActions Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class AclActionsController extends AclAppController {
    var $name = 'AclActions';
    var $uses = array('Acl.AclAco');
    var $components = array('Acl.AclGenerate');

	function admin_index() {
		$this->pageTitle = __('Actions', true);

        $conditions = array(
                       'NOT' => array(
                                 'parent_id' => $this->topLevelAcos,
                                 'id'        => $this->topLevelAcos,
                                ),
                      );
		$this->set('acos', $this->Acl->Aco->generatetreelist($conditions, '{n}.Aco.id', '{n}.Aco.alias'));
	}

	function admin_add() {
		$this->pageTitle = __("Add Action", true);

		if (!empty($this->data)) {
            $this->Acl->Aco->create();
            
            // if parent_id is null, assign 'controllers' as parent
            if ($this->data['Aco']['parent_id'] == null) {
                $this->data['Aco']['parent_id'] = 1;
                $acoType = 'Controller';
            } else {
                $acoType = 'Action';
            }

			if ($this->Acl->Aco->save($this->data['Aco'])) {
				$this->Session->setFlash(__('The '. $acoType .' has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The '. $acoType .' could not be saved. Please, try again.', true));
			}
		}

        $conditions = array(
                       'NOT' => array(
                                 'alias' => array('nodes', 'blocks'),
                                ),
                       'parent_id' => array(null, 1),
                      );
        //$conditions = null;
        $acos = $this->Acl->Aco->generatetreelist($conditions, '{n}.Aco.id', '{n}.Aco.alias');
        $this->set(compact('acos'));
	}

	function admin_edit($id = null) {
		$this->pageTitle = __("Edit Action", true);

		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Action', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Acl->Aco->save($this->data['Aco'])) {
				$this->Session->setFlash(__('The Action has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Action could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Acl->Aco->read(null, $id);
		}

        $acos = $this->Acl->Aco->generatetreelist("Aco.id = '1' OR Aco.parent_id = '1'", '{n}.Aco.id', '{n}.Aco.alias');
        $this->set(compact('acos'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Action', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Acl->Aco->del($id)) {
			$this->Session->setFlash(__('Action deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

    function admin_move($id, $direction = 'up', $step = '1') {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Action', true));
			$this->redirect(array('action'=>'index'));
		}
        if ($direction == 'up') {
            if ($this->Acl->Aco->moveUp($id)) {
                $this->Session->setFlash(__('Action moved up', true));
                $this->redirect(array('action'=>'index'));
            }
        } else {
            if ($this->Acl->Aco->moveDown($id)) {
                $this->Session->setFlash(__('Action moved down', true));
                $this->redirect(array('action'=>'index'));
            }
        }
    }

    function admin_generate() {
        $aco =& $this->Acl->Aco;
        $root = $aco->node('controllers');
        if (!$root) {
            $aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
            $root = $aco->save();
            $root['Aco']['id'] = $aco->id;
        } else {
            $root = $root[0];
        }

        $controllerPaths = $this->AclGenerate->listControllers();

        foreach ($controllerPaths AS $controllerName => $controllerPath) {
            // find / make controller node
            $controllerNode = $aco->node('controllers/'.$controllerName);
            if (!$controllerNode) {
                $aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $controllerName));
                $controllerNode = $aco->save();
                $controllerNode['Aco']['id'] = $aco->id;
                $log[] = 'Created Aco node for '.$controllerName;
            } else {
                $controllerNode = $controllerNode[0];
            }

            $methods = $this->AclGenerate->listActions($controllerName, $controllerPath);
            foreach ($methods AS $method) {
                $methodNode = $aco->node('controllers/'.$controllerName.'/'.$method);
                if (!$methodNode) {
                    $aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
                    $methodNode = $aco->save();
                }
            }
        }

        if (isset($this->params['named']['permissions'])) {
            $this->redirect(array('plugin' => 'acl', 'controller' => 'acl_permissions', 'action' => 'index'));
        } else {
            $this->redirect(array('action' => 'index'));
        }
    }

}
?>