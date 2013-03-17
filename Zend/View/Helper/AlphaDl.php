<?php
class G_Zend_View_Helper_AlphaDl
{
	public function alphaDl($list)
	{
		list($k, $c) = each($list);
		$char = strtoupper(substr($c['name'], 0, 1));
		if (is_numeric($char)) {
			$char = "#";
		}
		$lastChar = $char;
		$li = "<dl><dt><strong>$char</strong></dt><dd><ul>\n";
		foreach ($list as $c) {
			$catLink = "/videos/{$c['slug']}-01-" . APP_IPP . "-date.html";
			$char = strtoupper(substr($c['name'], 0, 1));
			if (!is_numeric($char) && $lastChar !== $char) {
				$li .= "</ul></dd><dt><strong>$char</strong></dt><dd><ul>\n";
			}
			$li .= "<li><a href=\"$catLink\">{$c['name']}</a>&nbsp;({$c['itemsCount']})</li>\n";
			$lastChar = $char;
		}
		echo $li. '</ul></dd></dl>';	
	}
}