<?php

Yii::import('system.web.widgets.pagers.CLinkPager');

class BootstrapCLinkPager extends CLinkPager {

    const CSS_FIRST_PAGE = '';
    const CSS_LAST_PAGE = '';
    const CSS_PREVIOUS_PAGE = '';
    const CSS_NEXT_PAGE = '';
    const CSS_INTERNAL_PAGE = '';
    const CSS_SELECTED_PAGE = 'active';
    const CSS_HIDDEN_PAGE = 'disabled';

    public $firstPageCssClass = self::CSS_FIRST_PAGE;
    public $lastPageCssClass = self::CSS_LAST_PAGE;
    public $previousPageCssClass = self::CSS_PREVIOUS_PAGE;
    public $nextPageCssClass = self::CSS_NEXT_PAGE;
    public $internalPageCssClass = self::CSS_INTERNAL_PAGE;
    public $hiddenPageCssClass = self::CSS_HIDDEN_PAGE;
    public $selectedPageCssClass = self::CSS_SELECTED_PAGE;
    public $header = '';
    public $footer = '';
    public $nextPageText;
    public $prevPageText;
    public $firstPageText;
    public $lastPageText;
    public $cssFile = '';
    public function init() {

        if ($this->nextPageText === null)
            $this->nextPageText = Yii::t('yii', 'Следующая');
        if ($this->prevPageText === null)
            $this->prevPageText = Yii::t('yii', 'Предыдущая');
        if ($this->firstPageText === null)
            $this->firstPageText = Yii::t('yii', 'Первая');
        if ($this->lastPageText === null)
            $this->lastPageText = Yii::t('yii', 'Последняя');

        if ($this->nextPageLabel === null)
            $this->nextPageLabel = Yii::t('yii', '<span aria-hidden="true">&gt;</span>');
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = Yii::t('yii', '<span aria-hidden="true">&lt;</span>');
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = Yii::t('yii', '<span aria-hidden="true">&lt;&lt;</span>');
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = Yii::t('yii', '<span aria-hidden="true">&gt;&gt;</span>');
        if ($this->header === null)
            $this->header = Yii::t('yii', 'Go to page: ');

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'pagination';
    }

    protected function createPageButtons() {
        if (($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons = array();

// first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->createPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false, $this->firstPageText);
        }
// prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0)
                $page = 0;
            $buttons[] = $this->createPageButton($this->prevPageLabel, $page, $this->previousPageCssClass, $currentPage <= 0, false, $this->prevPageText);
        }

// internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i)
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $i == $currentPage);

// next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1)
                $page = $pageCount - 1;
            $buttons[] = $this->createPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false, $this->nextPageText);
        }
// last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false, $this->lastPageText);
        }

        return $buttons;
    }

    protected function createPageButton($label, $page, $class, $hidden, $selected, $title = '') {
        $options = [];

        if (!empty($title)) {
            $options['title'] = $title;
            $options['data-toggle'] = 'tooltip';
            $options['data-placement'] = 'top';
        }
        if ($hidden || $selected)
            $class.=' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
        return '<li class="' . $class . '">' . CHtml::link($label, $this->createPageUrl($page), $options) . '</li>';
    }

    public function run() {
//        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        echo $this->header;
        echo '<nav>' . CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons)) . '</nav>';
        echo $this->footer;
    }

}
