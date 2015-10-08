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
                <div class="message-item {$FirstLast} <% if $Last %>$updateAsRead<% end_if %>">
                    <div class="message-header" <% if not $Read %>data-readlink="{$Up.Up.ReadLink('read')}" data-id="{$ID}"<% end_if %>>
                        <div class="message-image">
                            <img src="silverstripe-postmarked/images/icons/user.png">
                        </div>
                        <div class="message-details <% if $getFromTitle || $getFromEmail %>has-sender<% end_if %><% if $Last %> ex<% end_if %>">
                            <p class="message-from">{$getFromTitle} {$getFromEmail}</p>
                            <% if $First %>
                                <h3>{$Subject}</h3>
                            <% else %>
                                <h3>{$SummaryLine}</h3>
                            <% end_if %>
                        </div>
                        <% if $ShowReplyButton %>
                            <div class="message-reply">
                                <a href="{$MessagePopupLink}" class="icon-mail-reply open-message-popup" data-from="{$ReplyFromID}" data-to="{$ReplyToID}" data-subject="{$ReplyToSubject}">Reply</a>
                            </div>
                        <% end_if %>



                    </div>
                    <div class="message-contents">
                        <div class="message-body">
                            {$Message}
                        </div>
                        <% if $Attachments %>
                            <div class="message-attachments">
                                <h3>Attachments</h3>
                                <div class="message-attachment-list">
                                <% loop $Attachments %>
                                    <a href='{$Link}' target="_blank">
                                        <img src="{$Icon}">
                                        {$Title}
                                    </a>
                                <% end_loop %>
                                </div>
                            </div>
                        <% end_if %>
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
