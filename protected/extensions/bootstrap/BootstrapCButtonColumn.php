<?php

Yii::import('zii.widgets.grid.CButtonColumn');

class BootstrapCButtonColumn extends CButtonColumn {

    public $template = '{update} {delete}';
    public $header = 'Опции';
    public $deleteButtonOptions = array('class' => 'btn btn-default delete', 'type' => 'button');
    public $updateButtonOptions = array('class' => 'btn btn-default', 'type' => 'button');

    public function getDataCellContent($row) {
        $data = $this->grid->dataProvider->data[$row];
        $tr = array();
        ob_start();
        foreach ($this->buttons as $id => $button) {
            $this->renderButton($id, $button, $row, $data);
            $tr['{' . $id . '}'] = ob_get_contents();
            ob_clean();
        }
        ob_end_clean();
        return '<div class="btn-group" role="group">'.strtr($this->template, $tr).'</div>';
    }

    protected function renderButton($id, $button, $row, $data) {
        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data)))
            return;
        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row)) : '#';
        $options = isset($button['options']) ? $button['options'] : array();
        fb($options);
        if (!isset($options['title']))
            $options['title'] = $label;
        if (isset($button['imageUrl']) && is_string($button['imageUrl']))
            echo CHtml::link(CHtml::image($button['imageUrl'], $label), $url, $options);
        else
            echo CHtml::link($label, $url, $options);
    }

    protected function initDefaultButtons() {
        if ($this->viewButtonLabel === null)
            $this->viewButtonLabel = 'glyphicon glyphicon-search';
        $this->viewButtonOptions['title'] = Yii::t('zii', 'View');
        $this->viewButtonOptions['data-toggle'] = 'tooltip';
        $this->viewButtonOptions['data-placement'] = 'top';
        if ($this->updateButtonLabel === null)
            $this->updateButtonLabel = 'glyphicon glyphicon-pencil';
        $this->updateButtonOptions['title'] = Yii::t('zii', 'Update');
        $this->updateButtonOptions['data-toggle'] = 'tooltip';
        $this->updateButtonOptions['data-placement'] = 'top';
        if ($this->deleteButtonLabel === null)
            $this->deleteButtonLabel = 'glyphicon glyphicon-remove';
        $this->deleteButtonOptions['title'] = Yii::t('zii', 'Delete');
        $this->deleteButtonOptions['data-toggle'] = 'tooltip';
        $this->deleteButtonOptions['data-placement'] = 'top';
        if ($this->viewButtonImageUrl === null)
            $this->viewButtonImageUrl = $this->grid->baseScriptUrl . '/view.png';
        if ($this->updateButtonImageUrl === null)
            $this->updateButtonImageUrl = $this->grid->baseScriptUrl . '/update.png';
        if ($this->deleteButtonImageUrl === null)
            $this->deleteButtonImageUrl = $this->grid->baseScriptUrl . '/delete.png';
        if ($this->deleteConfirmation === null)
            $this->deleteConfirmation = Yii::t('zii', 'Are you sure you want to delete this item?');

        foreach (array('view', 'update', 'delete') as $id) {
            $button = array(
                'label' => '<span class="' . $this->{$id . 'ButtonLabel'} . '"></span>',
                'url' => $this->{$id . 'ButtonUrl'},
                'options' => $this->{$id . 'ButtonOptions'},
            );

            if (isset($this->buttons[$id]))
                $this->buttons[$id] = array_merge($button, $this->buttons[$id]);
            else
                $this->buttons[$id] = $button;
        }

        if (!isset($this->buttons['delete']['click'])) {
            if (is_string($this->deleteConfirmation))
                $confirmation = "if(!confirm(" . CJavaScript::encode($this->deleteConfirmation) . ")) return false;";
            else
                $confirmation = '';

            if (Yii::app()->request->enableCsrfValidation) {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            } else
                $csrf = '';

            if ($this->afterDelete === null)
                $this->afterDelete = 'function(){}';

            $this->buttons['delete']['click'] = <<<EOD
function() {
	$confirmation
	var th = this,
		afterDelete = $this->afterDelete;
	jQuery('#{$this->grid->id}').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#{$this->grid->id}').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
	});
	return false;
}
EOD;
        }
    }

    protected function registerClientScript() {
        $js = array();
        foreach ($this->buttons as $id => $button) {
            if (isset($button['click'])) {
                $function = CJavaScript::encode($button['click']);
                $class = preg_replace('/\s+/', '.', $button['options']['class']);
                $js[] = "jQuery(document).on('click','#{$this->grid->id} a.{$class}',$function);";
            }
        }
        $js[] = "jQuery(document).ready(function(){
    jQuery('[data-toggle=\"tooltip\"]').tooltip();   
});";
        if ($js !== array())
            Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->id, implode("\n", $js));
    }

}
