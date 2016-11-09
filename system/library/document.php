<?php

final class Document {

    /**
     * The script is rendered in the head section right before the title element.
     */
    const POS_HEAD = 0;

    /**
     * The script is rendered at the beginning of the body section.
     */
    const POS_BEGIN = 1;

    /**
     * The script is rendered at the end of the body section.
     */
    const POS_END = 2;

    /**
     * The script is rendered inside window onload function.
     */
    const POS_LOAD = 3;

    /**
     * The body script is rendered inside a jQuery ready function.
     */
    const POS_READY = 4;

    public $title;
    public $description;
    public $keywords;
    public $category_description;
    public $base;
    public $charset = 'utf-8';
    public $language = 'en-gb';
    public $direction = 'ltr';
    public $links = array();
    public $styles = array();
    public $scripts = array();
    public $script_inline = array();
    public $breadcrumbs = array();
    public $loadKnow = true;
    public $meta = array();

    public function setTitle($title) {
	$this->title = $title;
    }

    public function getTitle() {
	return metaLink($this->title);
    }

    public function setKeywords($keywords) {
	$this->keywords = $keywords;
    }

    public function getKeywords() {
	return metaLink($this->keywords);
    }

    public function setDescription($description) {
	$this->description = $description;
    }

    public function getDescription() {
	return metaLink($this->description);
    }

    public function setBase($base) {
	$this->base = $base;
    }

    public function getBase() {
	return $this->base;
    }

    public function setCharset($charset) {
	$this->charset = $charset;
    }

    public function getCharset() {
	return $this->charset;
    }

    public function setLanguage($language) {
	$this->language = $language;
    }

    public function getLanguage() {
	return $this->language;
    }

    public function setDirection($direction) {
	$this->direction = $direction;
    }

    public function getDirection() {
	return $this->direction;
    }

    public function setCategoryDescription($description) {
	$this->category_description = $description;
    }

    public function getCategoryDescription() {
	return $this->category_description;
    }

    public function addLink($href, $rel) {
	$this->links[] = array(
	    'href' => $href,
	    'rel' => $rel
	);
    }

    public function getLinks() {
	return $this->links;
    }

    public function addStyle($href, $rel = 'stylesheet', $media = 'screen') {
	$this->styles[] = array(
	    'href' => $href,
	    'rel' => $rel,
	    'media' => $media
	);
    }

    public function getStyles() {
	return $this->styles;
    }

    public function addScript($script, $pos = self::POS_END) {
	$this->scripts[$pos][] = $script;
    }

    public function addScriptInline($script, $pos = self::POS_END) {
	$this->script_inline[$pos][] = $script;
    }

    public function getScriptInline($pos = self::POS_END) {
	return isset($this->script_inline[$pos]) ? $this->script_inline[$pos] : '';
    }

    public function getScripts() {
	return $this->scripts;
    }

    public function addBreadcrumb($text, $href, $separator = ' &gt; ') {
	$this->breadcrumbs[] = array(
	    'text' => $text,
	    'href' => $href,
	    'separator' => $separator
	);
    }

    public function getBreadcrumbs() {
	$this->breadcrumbs = array_merge($home, $this->breadcrumbs);
	return $this->breadcrumbs;
    }

    /**
     * Inserts the scripts in the head section.
     * @param string the output to be inserted with scripts.
     */
    public function renderHead() {
	$html = '';
	if (isset($this->scripts[self::POS_HEAD])) {
	    foreach ($this->scripts[self::POS_HEAD] as $scriptFile)
		$html.=CHtml::scriptFile($scriptFile) . "\n";
	}

	if (isset($this->script_inline[self::POS_HEAD]))
	    $html.=CHtml::script(implode("\n", $this->script_inline[self::POS_HEAD])) . "\n";

	return $html;
    }

    /**
     * Inserts the scripts at the beginning of the body section.
     * @param string the output to be inserted with scripts.
     */
    public function renderBodyBegin() {
	$html = '';
	if (isset($this->scripts[self::POS_BEGIN])) {
	    foreach ($this->scripts[self::POS_BEGIN] as $scriptFile)
		$html.=CHtml::scriptFile($scriptFile) . "\n";
	}
	if (isset($this->scripts[self::POS_BEGIN]))
	    $html.=CHtml::script(implode("\n", $this->script_inline[self::POS_BEGIN])) . "\n";
	return $html;
    }

    /**
     * Inserts the scripts at the end of the body section.
     * @param string the output to be inserted with scripts.
     */
    public function renderBodyEnd() {
	if (!isset($this->scripts[self::POS_END]) && !isset($this->script_inline[self::POS_END]) && !isset($this->script_inline[self::POS_READY]) && !isset($this->script_inline[self::POS_LOAD]))
	    return;

	$html = '';
	if (isset($this->scripts[self::POS_END])) {
	    foreach ($this->scripts[self::POS_END] as $scriptFile)
		$html.=CHtml::scriptFile($scriptFile) . "\n";
	}
	$scripts = isset($this->script_inline[self::POS_END]) ? $this->script_inline[self::POS_END] : array();
	if (isset($this->script_inline[self::POS_READY])) {
	    $scripts[] = "jQuery(function($) {\n" . implode("\n", $this->script_inline[self::POS_READY]) . "\n});";
	}

	if (isset($this->script_inline[self::POS_LOAD])) {
	    if ($fullPage)
		$scripts[] = "window.onload=function() {\n" . implode("\n", $this->script_inline[self::POS_LOAD]) . "\n};";
	    else
		$scripts[] = implode("\n", $this->script_inline[self::POS_LOAD]);
	}
	if (!empty($scripts))
	    $html.=CHtml::script(implode("\n", $scripts)) . "\n";
	return $html;
    }

    public function setLoadKnow($load) {
        $this->loadKnow = $load;
    }

    public function getLoadKnow() {
        return $this->loadKnow;
    }

}

?>