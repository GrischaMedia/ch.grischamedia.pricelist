{capture assign='pageTitle'}{lang}wcf.pricelist.item.{@$action}{/lang}{/capture}

{capture assign='contentTitle'}{lang}wcf.pricelist.item.{@$action}{/lang}{/capture}

{capture assign='contentHeaderNavigation'}
	
	<li><a href="{link controller='Pricelist'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.pricelist.list{/lang}</span></a></li>
{/capture}

{include file='header'}

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{@$action}{/lang}</p>
{/if}

{if $categoryWarning}
	<p class="error">{lang}wcf.pricelist.item.add.category.warning{/lang}</p>
{elseif $currencyWarning}
	<p class="error">{lang}wcf.pricelist.item.add.currency.warning{/lang}</p>
{else}
	<form id="formContainer" class="jsFormGuard" method="post" action="{if $action == 'add'}{link controller='PricelistAdd'}{/link}{else}{link controller='PricelistEdit' id=$priceObj->priceID}{/link}{/if}">
		<div class="section">
			<h2 class="sectionTitle">{lang}wcf.pricelist.item.general{/lang}</h2>
			
			<!-- title and description -->
			<dl{if $errorField == 'title'} class="formError"{/if}>
				<dt><label for="title">{lang}wcf.pricelist.item.title{/lang}</label></dt>
				<dd>
					<input type="text" id="title" name="title" value="{$title}" maxlength="80" class="long" />
					
					{if $errorField == 'title'}
						<small class="innerError">
							{lang}wcf.pricelist.item.title.error.{@$errorType}{/lang}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl{if $errorField == 'description'} class="formError"{/if}>
				<dt><label for="description">{lang}wcf.pricelist.item.description{/lang}</label></dt>
				<dd>
					<textarea id="description" name="description" class="wysiwygTextarea" data-disable-media="1">{$description}</textarea>
					
					{if $errorField == 'description'}
						<small class="innerError">
							{lang}wcf.pricelist.item.description.error.{@$errorType}{/lang}
						</small>
					{/if}
				</dd>
			</dl>
		</div>
		
		{include file='messageFormTabs' wysiwygContainerID='description'}
		<div class="section"></div>
		
		<div class="section">
			<h2 class="sectionTitle">{lang}wcf.pricelist.item.classification{/lang}</h2>
			
			<!-- itemID and category -->
			<dl{if $errorField == 'itemID'} class="formError"{/if}>
				<dt><label for="itemID">{lang}wcf.pricelist.item.itemID{/lang}</label></dt>
				<dd>
					<input type="text" id="itemID" name="itemID" value="{$itemID}" maxlength="50" class="medium" />
					
					{if $errorField == 'itemID'}
						<small class="innerError">
							{lang}wcf.pricelist.item.itemID.error.{@$errorType}{/lang}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl{if $errorField == 'categoryID'} class="formError"{/if}>
				<dt><label for="categoryID">{lang}wcf.pricelist.item.categoryID{/lang}</label></dt>
				<dd>
					<select id="categoryID" name="categoryID">
						
						{include file='categoryOptionList'}
					</select>
					
					{if $errorField == 'categoryID'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.pricelist.item.categoryID.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</div>
		
		<div class="section">
			<h2 class="sectionTitle">{lang}wcf.pricelist.item.cost{/lang}</h2>
			
			<!-- curreny and price -->
			<dl{if $errorField == 'currency'} class="formError"{/if}>
				<dt><label for="currency">{lang}wcf.pricelist.item.currency{/lang}</label></dt>
				<dd>
					<select name="currency" id="currency">
						{htmlOptions output=$availableCurrencies values=$availableCurrencies selected=$currency}
					</select>
					
					{if $errorField == 'currency'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.pricelist.item.currency.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
			
			<dl{if $errorField == 'price'} class="formError"{/if}>
				<dt><label for="price">{lang}wcf.pricelist.item.price{/lang}</label></dt>
				<dd>
					<input type="text" id="price" name="price" value="{@$price|currency}" class="tiny">
					{if $errorField == 'price'}
						<small class="innerError">
							{if $errorType == 'empty'}
								{lang}wcf.global.form.error.empty{/lang}
							{else}
								{lang}wcf.pricelist.item.price.error.{@$errorType}{/lang}
							{/if}
						</small>
					{/if}
				</dd>
			</dl>
		</div>
		
		<div class="section" id="pricelistLabelContainer">
			<header class="sectionHeader">
				<h2 class="sectionTitle">{lang}wcf.pricelist.labels{/lang}</h2>
			</header>
			
			{if $labelGroups|count}
				{foreach from=$labelGroups item=labelGroup}
					{if $labelGroup|count}
						<dl>
							<dt><label>{$labelGroup->getTitle()}</label></dt>
							<dd>
								<ul class="labelList jsOnly" data-object-id="{@$labelGroup->groupID}">
									<li class="dropdown pricelistLabelChooser" id="labelGroup{@$labelGroup->groupID}" data-group-id="{@$labelGroup->groupID}" data-force-selection="false">
										<div class="dropdownToggle" data-toggle="labelGroup{@$labelGroup->groupID}"><span class="badge label">{lang}wcf.label.none{/lang}</span></div>
										<div class="dropdownMenu">
											<ul class="scrollableDropdownMenu">
												{foreach from=$labelGroup item=label}
													<li data-label-id="{@$label->labelID}"><span><span class="badge label{if $label->getClassNames()} {@$label->getClassNames()}{/if}">{lang}{$label->label}{/lang}</span></span></li>
												{/foreach}
											</ul>
										</div>
									</li>
								</ul>
							</dd>
						</dl>
					{/if}
				{/foreach}
			{/if}
		</div>
		
		<div class="formSubmit">
			<input id="saveButton" type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
			{@SECURITY_TOKEN_INPUT_TAG}
		</div>
	</form>
{/if}

<script data-relocate="true" src="{@$__wcf->getPath()}/js/PRICELIST.js"></script>
<script data-relocate="true">
	$(function() {
		WCF.Language.addObject({
			'wcf.label.none': '{lang}wcf.label.none{/lang}'
		});
		new PRICELIST.PricelistLabelChooser({ {implode from=$pricelistLabelIDs key=groupID item=labelID}{@$groupID}: {@$labelID}{/implode} }, '#formContainer');
	});
</script>

{include file='footer'}

{include file='wysiwyg' wysiwygSelector='description'}
