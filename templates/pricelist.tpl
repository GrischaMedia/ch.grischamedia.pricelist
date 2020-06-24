{capture assign='pageTitle'}{$__wcf->getActivePage()->getTitle()}{/capture}

{capture assign='contentTitle'}{$__wcf->getActivePage()->getTitle()} <span class="badge">{$items|count}</span>{/capture}

{capture assign='contentHeaderNavigation'}
	{assign var='linkParameters' value=''}
	{if $search}{capture append=linkParameters}&search={@$search|rawurlencode}{/capture}{/if}
	
	<li><a href="{link controller='PricelistAdd'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>{lang}wcf.pricelist.item.add{/lang}</span></a></li>
{/capture}

{include file='header'}

<form method="post" action="{link controller='Pricelist'}{/link}">
	<div class="formSubmit" style="text-align:left;">
		<input type="text" id="search" name="search" value="{$search}" placeholder="{lang}wcf.pricelist.search.string{/lang}" class="medium">
		<input type="submit" value="{lang}wcf.pricelist.search{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{if $items}
	<div class="section tabularBox">
		<table class="table">
			<thead>
				<tr>
					{if $__wcf->session->getPermission('user.pricelist.canEdit')}
						<th class="columnID columnPriceID{if $sortField == 'priceID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='Pricelist'}sortField=priceID&sortOrder={if $sortField == 'priceID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.pricelist.item.id{/lang}</a></th>
					{/if}
					<th class="columnText columnTitle{if $sortField == 'title'} active {@$sortOrder}{/if}"><a href="{link controller='Pricelist'}sortField=title&sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.pricelist.item.title{/lang}</a></th>
					<th class="columnText columnDescription{if $sortField == 'description'} active {@$sortOrder}{/if}"><a href="{link controller='Pricelist'}sortField=description&sortOrder={if $sortField == 'description' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.pricelist.item.description{/lang}</a></th>
					<th class="columnText columnItemID{if $sortField == 'itemID'} active {@$sortOrder}{/if}"><a href="{link controller='Pricelist'}sortField=itemID&sortOrder={if $sortField == 'itemID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.pricelist.item.itemID{/lang}</a></th>
					<th class="columnText columnCurrency{if $sortField == 'currency'} active {@$sortOrder}{/if}"><a href="{link controller='Pricelist'}sortField=currency&sortOrder={if $sortField == 'currency' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.pricelist.item.currency{/lang}</a></th>
					<th class="columnText columnPrice{if $sortField == 'price'} active {@$sortOrder}{/if}"><a href="{link controller='Pricelist'}sortField=price&sortOrder={if $sortField == 'price' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@$linkParameters}{/link}">{lang}wcf.pricelist.item.price{/lang}</a></th>
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$itemsToCategory key='categoryID' item='items'}
					<tr>
						{if $__wcf->session->getPermission('user.pricelist.canEdit')}
							<th class="pricelistSubtitle" colspan="7">{if $categoryID}{lang}{$categories[$categoryID]->title}{/lang}{if $categories[$categoryID]->description} - {$categories[$categoryID]->description}{/if}{else}{lang}wcf.pricelist.category.none{/lang}{/if}</th>
						{else}
							<th class="pricelistSubtitle" colspan="5">{if $categoryID}{lang}{$categories[$categoryID]->title}{/lang}{if $categories[$categoryID]->description} - {$categories[$categoryID]->description}{/if}{else}{lang}wcf.pricelist.category.none{/lang}{/if}</th>
						{/if}
					</tr>
						{foreach from=$items item=item}
							<tr class="jsItemRow">
								{if $__wcf->session->getPermission('user.pricelist.canEdit')}
									<td class="columnIcon">
										<span class="icon icon16 fa-{if !$item->isDisabled}check-{/if}square-o jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $item->isDisabled}enable{else}disable{/if}{/lang}" data-object-id="{@$item->priceID}" data-disable-message="{lang}wcf.global.button.disable{/lang}" data-enable-message="{lang}wcf.global.button.enable{/lang}"></span>
										<a href="{link controller='PricelistEdit' object=$item}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 fa-pencil"></span></a>
										<span class="icon icon16 fa-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$item->priceID}" data-confirm-message="{lang}wcf.pricelist.item.delete.sure{/lang}"></span>
									</td>
									<td class="columnID">{@$item->priceID}</td>
								{/if}
								
								<td class="columnText columnTitle pricelistProduct">
									{if $item->hasLabels}
										<span class="pricelistLabels">
										{foreach from=$item->getLabels() item=label}
											<span class="label badge{if $label->getClassNames()} {$label->getClassNames()}{/if}">{lang}{$label->label}{/lang}</span>
										{/foreach}
										</span>
										<br>
									{/if}
									{$item->title}
								</td>
								<td class="columnText columnDescription htmlContent" id="{@$item->priceID}">
									{if $item->truncated}
										{@$item->truncated} <span class="icon icon16 fa-arrow-right jsOpenButton jsTooltip pointer" title="{lang}wcf.pricelist.show{/lang}" data-object-id="{@$item->priceID}"></span>
									{else}
										{@$item->description}
									{/if}
								</td>
								<td class="columnText columnItemID">{$item->itemID}</td>
								<td class="columnText columnCurrency">{$item->currency}</td>
								<td class="columnText columnPrice">{$item->price|currency}</td>
							</tr>
						{/foreach}
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

<footer class="contentFooter">
	{hascontent}
		<nav class="contentFooterNavigation">
			<ul>
				{content}
				
				{/content}
			</ul>
		</nav>
	{/hascontent}
</footer>

<script data-relocate="true">
	require(['GRISCHAMEDIA/Pricelist/Open'], function(PricelistOpen) {
		new PricelistOpen();
	});
</script>

<script data-relocate="true">
	$(function() {
		new WCF.Action.Delete('wcf\\data\\pricelist\\PricelistAction', '.jsItemRow');
		new WCF.Action.Toggle('wcf\\data\\pricelist\\PricelistAction', $('.jsItemRow'));
	});
</script>

{include file='footer'}
