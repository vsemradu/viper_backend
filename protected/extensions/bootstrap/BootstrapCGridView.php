<?php

Yii::import('zii.widgets.grid.CGridView');

class BootstrapCGridView extends CGridView {

    public $afterAjaxUpdate = 'function(id, data){jQuery(\'[data-toggle="tooltip"]\').tooltip()}';
    public $itemsCssClass = 'table table-hover table-bordered table-striped';
    public $template = "<div class=\"row\"><div class=\"span6\">{summary}</div></div>\n{items}\n<div class=\"row\"><div class=\"span6\">{pager}</div></div>";
    public $pager = array('class' => 'BootstrapCLinkPager');
    public $pagerCssClass = 'bootstrapCLinkPager';
    public $scrollToOnPagination = false;

    public function registerClientScript() {
        $id = $this->getId();

        if ($this->scrollToOnPagination) {
            $this->afterAjaxUpdate = 'function(id, data){jQuery.scrollTo(\'#' . $id . '\', 350); jQuery(\'[data-toggle="tooltip"]\').tooltip();}';
        }

        if ($this->ajaxUpdate === false)
            $ajaxUpdate = false;
        else
            $ajaxUpdate = array_unique(preg_split('/\s*,\s*/', $this->ajaxUpdate . ',' . $id, -1, PREG_SPLIT_NO_EMPTY));
        $options = array(
            'ajaxUpdate' => $ajaxUpdate,
            'ajaxVar' => $this->ajaxVar,
            'pagerClass' => $this->pagerCssClass,
            'loadingClass' => $this->loadingCssClass,
            'filterClass' => $this->filterCssClass,
            'tableClass' => $this->itemsCssClass,
            'selectableRows' => $this->selectableRows,
            'enableHistory' => $this->enableHistory,
            'updateSelector' => $this->updateSelector,
            'filterSelector' => $this->filterSelector
        );
        if ($this->ajaxUrl !== null)
            $options['url'] = CHtml::normalizeUrl($this->ajaxUrl);
        if ($this->ajaxType !== null) {
            $options['ajaxType'] = strtoupper($this->ajaxType);
            $request = Yii::app()->getRequest();
            if ($options['ajaxType'] == 'POST' && $request->enableCsrfValidation) {
                $options['csrfTokenName'] = $request->csrfTokenName;
                $options['csrfToken'] = $request->getCsrfToken();
            }
        }
        if ($this->enablePagination)
            $options['pageVar'] = $this->dataProvider->getPagination()->pageVar;
        foreach (array('beforeAjaxUpdate', 'afterAjaxUpdate', 'ajaxUpdateError', 'selectionChanged') as $event) {
            if ($this->$event !== null) {
                if ($this->$event instanceof CJavaScriptExpression)
                    $options[$event] = $this->$event;
                else
                    $options[$event] = new CJavaScriptExpression($this->$event);
            }
        }

        $options = CJavaScript::encode($options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bbq');
        if ($this->enableHistory)
            $cs->registerCoreScript('history');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.yiigridview.js', CClientScript::POS_END);
        $cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#$id').yiiGridView($options);");
    }

    protected function initColumns() {
        if ($this->columns === array()) {
            if ($this->dataProvider instanceof CActiveDataProvider)
                $this->columns = $this->dataProvider->model->attributeNames();
            elseif ($this->dataProvider instanceof IDataProvider) {
                // use the keys of the first row of data as the default columns
                $data = $this->dataProvider->getData();
                if (isset($data[0]) && is_array($data[0]))
                    $this->columns = array_keys($data[0]);
            }
        }
        $id = $this->getId();
        foreach ($this->columns as $i => $column) {
            if (is_string($column))
                $column = $this->createDataColumn($column);
            else {
                if (!isset($column['class']))
                    $column['class'] = 'CDataColumn';
                $column = Yii::createComponent($column, $this);
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            if ($column->id === null)
                $column->id = $id . '_c' . $i;
            $this->columns[$i] = $column;
        }

        foreach ($this->columns as $column)
            $column->init();
    }

}
