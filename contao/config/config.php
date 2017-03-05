<?php

$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['mailjet'] = array
(
    // Type
    'mailjet_transaktional'   => array
    (
        // Field in tl_nc_language
        'recipients'    => array
        (
            // Valid tokens
            'recipient_email' // The email address of the recipient
        ),
        'attachment_tokens'    => array
        (
            'form_*', // All the order condition form fields
            'document' // The document that should be attached (e.g. an invoice)
        )
    )
);