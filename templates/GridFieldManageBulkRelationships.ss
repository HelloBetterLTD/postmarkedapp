<div class="grid-field-bulk-operation tabbed-block" data-fromclass="$FromClass" data-relationship="{$Relationship}">

    <label class="tab-title {$Relationship}" data-tab="tab-{$Relationship}">{$Title}</label>

		<div id="tab-{$Relationship}" class="tab-content">
    		{$ObjectSelectorField}

			<div class="tab-buttons">
    			<a href="{$AddLink}" class="ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all grid-field-bulk-op add-to-relation">
	        		{$ButtonName}
	    		</a>

	    		<a href="{$RemoveLink}" class="ss-ui-action-constructive ss-ui-button ui-button ui-widget ui-state-default ui-corner-all grid-field-bulk-op remove-from-relation">
	        		Remove
	    		</a>
			</div>
	</div>
</div>
