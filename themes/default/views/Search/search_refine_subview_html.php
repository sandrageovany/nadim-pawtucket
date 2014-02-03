<?php
/* ----------------------------------------------------------------------
 * views/Search/search_refine_subview_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
	$va_facets 			= $this->getVar('facets');				// array of available search facets
	$va_criteria 		= $this->getVar('criteria');			// array of refine criteria
	$vs_key 			= $this->getVar('key');					// cache key for current refine
	$va_access_values 	= $this->getVar('access_values');		// list of access values for this user
	$vs_view			= $this->getVar('view');
	
	$vn_facet_display_length_initial = 7;
	$vn_facet_display_length_maximum = 60;
	
	if(is_array($va_facets) && sizeof($va_facets)){
		print "<div id='bMorePanel'><!-- long lists of facets are loaded here --></div>";
		print "<div id='bRefine'>";
		print "<H1>"._t("Filter by")."</H1>";
		foreach($va_facets as $vs_facet_name => $va_facet_info) {
			if (!is_array($va_facet_info['content']) || !sizeof($va_facet_info['content'])) { continue; }
			
			print "<H2>".$va_facet_info['label_singular']."</H2>"; 
			
			$vn_facet_size = sizeof($va_facet_info['content']);
			$vn_c = 0;
			foreach($va_facet_info['content'] as $va_item) {
				print "<div>".caNavLink($this->request, $va_item['label'], '', '*', '*','*', array('key' => $vs_key, 'facet' => $vs_facet_name, 'id' => $va_item['id'], 'view' => $vs_view))."</div>";
				$vn_c++;
				
				if (($vn_c == $vn_facet_display_length_initial) && ($vn_facet_size > $vn_facet_display_length_initial) && ($vn_facet_size <= $vn_facet_display_length_maximum)) {
					print "<div id='{$vs_facet_name}_more' style='display: none;'>";
				} else {
					if(($vn_c == $vn_facet_display_length_initial) && ($vn_facet_size > $vn_facet_display_length_maximum))  {
						break;
					}
				}
			}
			if (($vn_facet_size > $vn_facet_display_length_initial) && ($vn_facet_size <= $vn_facet_display_length_maximum)) {
				print "</div>\n";
				
				$vs_link_open_text = _t("and %1 more", $vn_facet_size - $vn_facet_display_length_initial);
				$vs_link_close_text = _t("close", $vn_facet_size - $vn_facet_display_length_initial);
				print "<div><a href='#' class='more' id='{$vs_facet_name}_more_link' onclick='jQuery(\"#{$vs_facet_name}_more\").slideToggle(250, function() { jQuery(this).is(\":visible\") ? jQuery(\"#{$vs_facet_name}_more_link\").text(\"".addslashes($vs_link_close_text)."\") : jQuery(\"#{$vs_facet_name}_more_link\").text(\"".addslashes($vs_link_open_text)."\")}); return false;'><em>{$vs_link_open_text}</em></a></div>";
			} elseif (($vn_facet_size > $vn_facet_display_length_initial) && ($vn_facet_size > $vn_facet_display_length_maximum)) {
				#print "<div><a href='#' class='more' onclick='caBrowsePanel.showPanel(\"".caNavUrl($this->request, '*', '*', '*', array('getFacet' => 1, 'facet' => $vs_facet_name, 'view' => $vs_view, 'key' => $vs_key))."\"); return false;'><em>"._t("and %1 more", $vn_facet_size - $vn_facet_display_length_initial)."</em></a></div>";
				print "<div><a href='#' class='more' onclick='jQuery(\"#bMorePanel\").load(\"".caNavUrl($this->request, '*', '*', '*', array('getFacet' => 1, 'facet' => $vs_facet_name, 'view' => $vs_view, 'key' => $vs_key))."\", function(){jQuery(\"#bMorePanel\").show(); jQuery(\"#bMorePanel\").mouseleave(function(){jQuery(\"#bMorePanel\").hide();});}); return false;'><em>"._t("and %1 more", $vn_facet_size - $vn_facet_display_length_initial)."</em></a></div>";
			}
		}
		print "</div><!-- end bRefine -->\n";
?>
	<script type="text/javascript">
		/*function moveRefine(originalRefineOffset){
			var offset = $("#bRefine").offset();
			var offsetBrowseResultsContainer = $("h2:first").offset();
			if($(document).scrollTop() < offsetBrowseResultsContainer.top){
				jQuery("#bRefine").offset({top: offsetBrowseResultsContainer.top, left: offset.left});
			}else{
				jQuery("#bRefine").offset({top: $(document).scrollTop(), left: offset.left});
			}
		}*/
		/*jQuery(document).ready(function() {
			var originalOffsetTop;
			var originalOffset = $("#bRefine").offset();
			originalOffsetTop = originalOffset.top;
			$(window).scroll(function() {
				clearTimeout($.data(this, 'scrollTimer'));
				$.data(this, 'scrollTimer', setTimeout(function() {
					// do something
					var offset = $("#bRefine").offset();
					if(($(document).scrollTop() > (offset.top + ($("#bRefine").height() - ($(window).height()/2)))) || (($(document).scrollTop() > $(window).height()) && ($(document).scrollTop() < offset.top))){
						//jQuery("#bRefine").offset({top: $(document).scrollTop(), left: offset.left});
						jQuery("#bRefine").animate({top: ($(document).scrollTop() - originalOffsetTop)});
					}
					if($(document).scrollTop() < $(window).height()){
						var offsetBrowseResultsContainer = $("h2:first").offset();
						jQuery("#bRefine").offset({top: offsetBrowseResultsContainer.top, left: offset.left});
					}
				}, 50));
			});
		});*/
		jQuery(document).ready(function() {
			var offsetBrowseResultsContainer = $("h2:first").offset();
			var lastOffset = $("#bRefine").offset();
			$("body").data("lastOffsetTop", lastOffset.top);
			$(window).scroll(function() {
				if(($(document).scrollTop() < $(document).height() - ($("#bRefine").height() + 250)) && (($(document).scrollTop() < $("body").data("lastOffsetTop")) || ($(document).scrollTop() > ($("body").data("lastOffsetTop") + ($("#bRefine").height() - ($(window).height()/3)))))){
					var offset = $("#bRefine").offset();
					if($(document).scrollTop() < offsetBrowseResultsContainer.top){
						jQuery("#bRefine").offset({top: offsetBrowseResultsContainer.top, left: offset.left});
					}else{
						jQuery("#bRefine").offset({top: $(document).scrollTop(), left: offset.left});
					}
				}
				clearTimeout($.data(this, 'scrollTimer'));
				$.data(this, 'scrollTimer', setTimeout(function() {
					// do something
					var lastOffset = $("#bRefine").offset();
					$("body").data("lastOffsetTop", lastOffset.top);
					
				}, 250));
			});
		});
	</script>
<?php	
	}
?>