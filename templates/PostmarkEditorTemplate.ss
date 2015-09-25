<% if $IncludeFormTag %>
    <form $FormAttributes data-layout-type="border">
<% end_if %>
    <div class="cms-content-header north">
        <div class="cms-content-header-info">
            <% include BackLink_Button %>
            <% with $Controller %>
                <% include CMSBreadcrumbs %>
            <% end_with %>
        </div>
    </div>

    <% with $Controller %>
        $EditFormTools
    <% end_with %>

    <div class="cms-content-fields center <% if not $Fields.hasTabset %>cms-panel-padded<% end_if %>">
        <% with $Controller.getRecord %>
            <div class="messages-holder">
            <% loop $Thread %>
                <div class="message-item">
                    <div class="message-header">
                        <div class="message-image">
                            <img src="silverstripe-postmarked/images/icons/user.png">
                        </div>
                        <div class="message-details">
                            <p class="message-from">{$getFromTitle} {$getFromEmail}</p>
                        </div>
                    </div>
                </div>
            <% end_loop %>
            </div>
        <% end_with %>
    </div>

    <div class="cms-content-actions cms-content-controls south"></div>
    <div class="better-buttons-utils">
        <% loop $Utils %>
            $FieldHolder
        <% end_loop %>
    </div>

<% if $IncludeFormTag %>
    </form>
<% end_if %>
