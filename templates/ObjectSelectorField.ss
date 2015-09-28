<div class="option-selector-folder-holder" data-name="{$Name}" data-link="{$Link('find')}">
    <div class="values">
        <% if $SelectedValues %>
            <% loop $SelectedValues %>
                <div class="item">
                    <label>{$Title}</label>
                    <input type="hidden" name="{$Top.Name}[]" value="{$ID}">
                    <span class="remove-selected-object icon-close"></span>
                </div>
            <% end_loop %>
        <% end_if %>
    </div>
    <input type="text" class="lookup text">
    <div class="suggesters">

    </div>
</div>