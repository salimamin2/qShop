<?php

final class Pagination {

    public $showlinks = true;
    public $showresults = true;
    public $total = 0;
    public $page = 1;
    public $limit = 20;
    public $num_links = 10;
    public $url = '';
    public $text = 'Showing {start} to {end} of {total} ({pages} Pages)';
    public $text_first = '|&lt;';
    public $text_last = '&gt;|';
    public $text_next = 'Next &#8594;';
    public $text_prev = '&#8592; Previous';
    public $style_links = 'links';
    public $style_results = 'results';
    public $enable_np = true;
    public $list_type = "ul";
    public $wrapper = true;
    public $active_class = "active";
    public $active_wrapper = true;
    public $prev_class = "prev";
    public $next_class = "next";
    public $next_links = "";
    public $prev_links = "";
    public $list_class = "";
    public $results_wrapper = false;
    public $links_wrapper = false;
    public $wrapper_class = "";

    public function render() {
	$total = $this->total;

	if ($this->page < 1) {
	    $page = 1;
	} else {
	    $page = $this->page;
	}

	if (!$this->limit) {
	    $limit = 10;
	} else {
	    $limit = $this->limit;
	}

	$num_links = $this->num_links;
	$num_pages = ceil($total / $limit);

	$output = '<' . $this->list_type . ' ' .($this->list_class != "" ? "class='".$this->list_class."'" : "") . '>';

	if ($page > 1) {
	    if ($this->enable_np) {
		$output .= '<li class="' . $this->prev_class . '"><a class="' . $this->prev_links . '" href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a></li>';
	    }
	}
	if ($this->showlinks) {
	    if ($num_pages > 1) {
		if ($num_pages <= $num_links) {
		    $start = 1;
		    $end = $num_pages;
		} else {
		    $start = $page - floor($num_links / 2);
		    $end = $page + floor($num_links / 2);

		    if ($start < 1) {
			$end += abs($start) + 1;
			$start = 1;
		    }
		    if ($end > $num_pages) {
			$start -= ($end - $num_pages);
			$end = $num_pages;
		    }
		}

		if ($start > 1) {
		    $output .= ' .... ';
		}

		for ($i = $start; $i <= $end; $i++) {
		    if ($page == $i) {
			$output .= ' <li class="' . $this->active_class . '">' . ($this->active_wrapper ? '<span>' . $i . '</span>' : $i) . '</li> ';
		    } else {
			$output .= '<li><a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a></li>';
		    }
		}

		if ($end < $num_pages) {
		    $output .= ' .... ';
		}
	    }
	}

	if ($this->enable_np) {
	    if ($page < $num_pages) {
		$output .= '<li class="' . $this->next_class . '"><a id="nextpage" class="' . $this->next_links . '" href="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->text_next . '</a></li>';
	    }
	}

	$find = array(
	    '{start}',
	    '{end}',
	    '{total}',
	    '{pages}'
	);

	$replace = array(
	    ($total) ? (($page - 1) * $limit) + 1 : 0,
	    ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit),
	    $total,
	    $num_pages
	);

	$output .= '</' . $this->list_type . '>';
	if ($this->wrapper) {
        $results = $this->showresults ? '<div class="' . $this->style_results . '">' . str_replace($find, $replace, $this->text) . '</div>' : '';
        $links = ($output ? '<div class="' . $this->style_links . '">' . $output . '</div>' : '');
	    return ($this->results_wrapper ? '<div class="' . $this->wrapper_class . '">' . $results . '</div>' : $results) . ($this->links_wrapper ? '<div class="' . $this->wrapper_class . '">' . $links . '</div>' : $links);
	} elseif ($this->total <= $this->limit) {
	    return "";
	} else {
	    return $output;
	}
    }

}

?>