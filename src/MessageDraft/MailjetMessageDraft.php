<?php


namespace Mindbird\Contao\MailjetNotification\MessageDraft;


use Contao\File;
use Contao\FilesModel;
use NotificationCenter\MessageDraft\MessageDraftInterface;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Util\StringUtil;

class MailjetMessageDraft implements MessageDraftInterface
{
    /**
     * Message
     * @var Message
     */
    protected $objMessage = null;
    /**
     * Language
     * @var Language
     */
    protected $objLanguage = null;
    /**
     * Tokens
     * @var array
     */
    protected $arrTokens = array();
    /**
     * File path attachments
     * @var array
     */
    protected $attachments = null;

    /**
     * String attachments
     * @var array
     */
    protected $stringAttachments = null;

    /**
     * Construct the object
     * @param Message  $objMessage
     * @param Language $objLanguage
     * @param          $arrTokens
     */
    public function __construct(Message $objMessage, Language $objLanguage, $arrTokens)
    {
        $this->arrTokens   = $arrTokens;
        $this->objLanguage = $objLanguage;
        $this->objMessage  = $objMessage;
    }

    /**
     * Returns the paths to attachments as an array
     * @return  array
     */
    public function getAttachments()
    {
        if ($this->attachments === null) {
            // Token attachments
            $this->attachments = StringUtil::getTokenAttachments($this->objLanguage->attachment_tokens, $this->arrTokens);

            // Add static attachments
            $arrStaticAttachments = deserialize($this->objLanguage->attachments, true);
            if (!empty($arrStaticAttachments)) {
                $objFiles = \FilesModel::findMultipleByUuids($arrStaticAttachments);
                if ($objFiles !== null) {
                    while ($objFiles->next()) {
                        $file = new File($objFiles->path);
                        if (!$file->exists()) {
                            continue;
                        }
                        $attachment = new \stdClass();
                        $attachment->Base64Content = base64_encode($file->getContent());
                        $attachment->Filename = $file->name;
                        $attachment->ContentType = $file->mime;
                        $this->attachments[] = $attachment;
                    }
                }
            }
        }
        return $this->attachments;
    }


    /**
     * Returns the contents of attachments as an array (the key being the desired file name).
     * @return  array
     * @throws \Exception
     */
    public function getStringAttachments()
    {
        if ($this->stringAttachments === null) {
            // Add attachment templates
            $arrTemplateAttachments = deserialize($this->objLanguage->attachment_templates, true);
            if (!empty($arrTemplateAttachments)) {
                $objFiles = \FilesModel::findMultipleByUuids($arrTemplateAttachments);
                if ($objFiles !== null) {
                    while ($objFiles->next()) {
                        $file = new File($objFiles->path, true);
                        if (!$file->exists()) {
                            continue;
                        }
                        $this->stringAttachments[$objFiles->name] = [
                            'Base64Content' => base64_encode(\Haste\Util\StringUtil::recursiveReplaceTokensAndTags($file->getContent(), $this->arrTokens)),
                            'Filename' => $file->name,
                            'ContentType' => $file->mime
                        ];
                    }
                }
            }
        }
        return $this->stringAttachments;
    }

    /**
     * Set the attachments
     *
     * @param array $attachments
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokens()
    {
        return $this->arrTokens;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->objMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage()
    {
        return $this->objLanguage->language;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageObject()
    {
        return $this->objLanguage;
    }
}