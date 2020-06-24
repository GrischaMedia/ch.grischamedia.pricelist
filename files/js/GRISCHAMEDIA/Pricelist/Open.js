/**
 * Opens a description in table.
 * 
 * @author		GrischaMedia.ch
 * @copyright	2019 GrischaMedia.ch
 * @license		GrischaMedia.ch Commercial License <https://GrischaMedia.ch.de>
 * @package		ch.grischamedia.pricelist
 */
define(['Ajax'], function(Ajax) {
	"use strict";
	
	function PricelistOpen() { this.init(); }
	
	PricelistOpen.prototype = {
			init: function() {
				var buttons = elBySelAll('.jsOpenButton');
				for (var i = 0, length = buttons.length; i < length; i++) {
					buttons[i].addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
				}
			},
			
			_ajaxSetup: function() {
				return {
					data: {
						actionName:	'open',
						className:	'wcf\\data\\pricelist\\PricelistAction'
					}
				};
			},
			
			_ajaxSuccess: function(data) {
				// set full description
				var row = document.getElementById(data.returnValues.id);
				row.innerHTML = data.returnValues.text;
			},
			
			_click: function(event) {
				var objectID = elData(event.currentTarget, 'object-id');
				
				Ajax.api(this, {
					parameters: {
						objectID: objectID
					}
				});
			}
		};
	return PricelistOpen;
});
