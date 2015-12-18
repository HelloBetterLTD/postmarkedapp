<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 5:00 PM
 * To change this template use File | Settings | File Templates.
 */


class PostmarkNotifier extends Controller
{

    public function index()
    {
        $this->recordInbound();
    }


    public function recordInbound()
    {
        $strJson = file_get_contents("php://input");

        try {
            $arrResponse = Convert::json2array($strJson);

            if ($savedMessage = PostmarkMessage::get()->filter('MessageID', $arrResponse['MessageID'])->first()) {
                return;
            }

            $hash = $arrResponse['ToFull'][0]['MailboxHash'];
            $hashParts = explode('+', $hash);
            $lastMessage = PostmarkMessage::get()->filter(array(
                'UserHash'            => $hashParts[0],
                'MessageHash'        => $hashParts[1]
            ))->first();


            $fromCustomer = PostmarkHelper::find_or_make_client($arrResponse['From']);

            $inboundSignature = null;
            if ($lastMessage) {
                $inboundSignature = $lastMessage->From();
            } elseif (!$lastMessage && isset($arrResponse['To'])) {
                $inboundSignature = PostmarkSignature::get()->filter('Email', $arrResponse['To'])->first();
            }

            if (!$inboundSignature) {
                $inboundSignature = PostmarkSignature::get()->filter('IsDefault', 1)->first();
            }



            $message = new PostmarkMessage(array(
                'Subject'            => $arrResponse['Subject'],
                'Message'            => $arrResponse['HtmlBody'],
                'ToID'                => 0,
                'MessageID'            => $arrResponse['MessageID'],
                'InReplyToID'        => $lastMessage ? $lastMessage->ID : 0,
                'FromCustomerID'    => $fromCustomer ? $fromCustomer->ID : 0,
                'InboundToID'        => $inboundSignature ? $inboundSignature->ID : 0
            ));
            $message->write();

            if (isset($arrResponse['Attachments']) && count($arrResponse['Attachments'])) {
                foreach ($arrResponse['Attachments'] as $attachment) {
                    $attachmentObject = new Attachment(array(
                        'Content'                => $attachment['Content'],
                        'FileName'                => $attachment['Name'],
                        'ContentType'            => $attachment['ContentType'],
                        'Length'                => $attachment['ContentLength'],
                        'ContentID'                => $attachment['ContentID'],
                        'PostmarkMessageID'        => $message->ID
                    ));
                    $attachmentObject->write();
                }
            }
        } catch (Exception $e) {
        }

        return 'OK';
    }
}
