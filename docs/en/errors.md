# RPC Errors

?> Note, Here we will not cover all errors but we will introduce you to the most common ones

---

## SEE_OTHER 303

- NETWORK_MIGRATE_%d : Your IP address is associated to DC %d, please re-send the query to that DC.


- PHONE_MIGRATE_%d : Your phone number is associated to DC %d, please re-send the query to that DC.


- STATS_MIGRATE_%d : Channel statistics for the specified channel are stored on DC %d, please re-send the query to that DC.


- USER_MIGRATE_%d : Your account is associated to DC %d, please re-send the query to that DC.


## BAD_REQUEST 400

- ABOUT_TOO_LONG : About string too long.

  - [account.updateProfile](https://core.telegram.org/method/account.updateProfile)

- ACCESS_TOKEN_EXPIRED : Access token expired.

  - [auth.importBotAuthorization](https://core.telegram.org/method/auth.importBotAuthorization)

- ACCESS_TOKEN_INVALID : Access token invalid.

  - [auth.importBotAuthorization](https://core.telegram.org/method/auth.importBotAuthorization)

- AD_EXPIRED : The ad has expired (too old or not found).

  - [channels.reportSponsoredMessage](https://core.telegram.org/method/channels.reportSponsoredMessage)

- ADDRESS_INVALID : The specified geopoint address is invalid.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)

- ADMIN_ID_INVALID : The specified admin ID is invalid.

  - [messages.deleteRevokedExportedChatInvites](https://core.telegram.org/method/messages.deleteRevokedExportedChatInvites)
  - [messages.getExportedChatInvites](https://core.telegram.org/method/messages.getExportedChatInvites)

- ADMIN_RANK_EMOJI_NOT_ALLOWED : An admin rank cannot contain emojis.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- ADMIN_RANK_INVALID : The specified admin rank is invalid.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- ADMIN_RIGHTS_EMPTY : The chatAdminRights constructor passed in keyboardButtonRequestPeer.peer_type.user_admin_rights has no rights set (i.e. flags is 0).

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- ADMINS_TOO_MUCH : There are too many admins.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- ALBUM_PHOTOS_TOO_MANY : You have uploaded too many profile photos, delete some before retrying.

  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- API_ID_INVALID : API ID invalid.

  - [auth.exportLoginToken](https://core.telegram.org/method/auth.exportLoginToken)
  - [auth.importBotAuthorization](https://core.telegram.org/method/auth.importBotAuthorization)
  - [auth.importWebTokenAuthorization](https://core.telegram.org/method/auth.importWebTokenAuthorization)
  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- API_ID_PUBLISHED_FLOOD : This API id was published somewhere, you can't use it now.

  - [auth.exportLoginToken](https://core.telegram.org/method/auth.exportLoginToken)
  - [auth.importBotAuthorization](https://core.telegram.org/method/auth.importBotAuthorization)
  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- ARTICLE_TITLE_EMPTY : The title of the article is empty.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- AUDIO_CONTENT_URL_EMPTY : The remote URL specified in the content field is empty.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- AUDIO_TITLE_EMPTY : An empty audio title was provided.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- AUTH_BYTES_INVALID : The provided authorization is invalid.

  - [auth.importAuthorization](https://core.telegram.org/method/auth.importAuthorization)
  - [invokeWithLayer](https://core.telegram.org/method/invokeWithLayer)

- AUTH_TOKEN_ALREADY_ACCEPTED : The specified auth token was already accepted.

  - [auth.acceptLoginToken](https://core.telegram.org/method/auth.acceptLoginToken)
  - [auth.importLoginToken](https://core.telegram.org/method/auth.importLoginToken)

- AUTH_TOKEN_EXCEPTION : An error occurred while importing the auth token.

  - [auth.acceptLoginToken](https://core.telegram.org/method/auth.acceptLoginToken)

- AUTH_TOKEN_EXPIRED : The authorization token has expired.

  - [auth.acceptLoginToken](https://core.telegram.org/method/auth.acceptLoginToken)
  - [auth.importLoginToken](https://core.telegram.org/method/auth.importLoginToken)

- AUTH_TOKEN_INVALID : The specified auth token is invalid.

  - [auth.importLoginToken](https://core.telegram.org/method/auth.importLoginToken)

- AUTH_TOKEN_INVALIDX : The specified auth token is invalid.

  - [auth.acceptLoginToken](https://core.telegram.org/method/auth.acceptLoginToken)
  - [auth.importLoginToken](https://core.telegram.org/method/auth.importLoginToken)

- AUTOARCHIVE_NOT_AVAILABLE : The autoarchive setting is not available at this time: please check the value of the [autoarchive_setting_available field in client config &raquo;](https://core.telegram.org/api/config#client-configuration) before calling this method.

  - [account.setGlobalPrivacySettings](https://core.telegram.org/method/account.setGlobalPrivacySettings)

- BALANCE_TOO_LOW : The transaction cannot be completed because the current [Telegram Stars balance](https://core.telegram.org/api/stars) is too low.

  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)

- BANK_CARD_NUMBER_INVALID : The specified card number is invalid.

  - [payments.getBankCardData](https://core.telegram.org/method/payments.getBankCardData)

- BANNED_RIGHTS_INVALID : You provided some invalid flags in the banned rights.

  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)

- BIRTHDAY_INVALID : An invalid age was specified, must be between 0 and 150 years.

  - [account.updateBirthday](https://core.telegram.org/method/account.updateBirthday)

- BOOST_NOT_MODIFIED : You're already [boosting](https://core.telegram.org/api/boost) the specified channel.

  - [messages](https://core.telegram.org/method/messages)
  - [stories.applyBoost](https://core.telegram.org/method/stories.applyBoost)
  - [stories.canApplyBoost](https://core.telegram.org/method/stories.canApplyBoost)

- BOOST_PEER_INVALID : The specified `boost_peer` is invalid.

  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)

- BOOSTS_EMPTY : No boost slots were specified.

  - [premium.applyBoost](https://core.telegram.org/method/premium.applyBoost)

- BOOSTS_REQUIRED : The specified channel must first be [boosted by its users](https://core.telegram.org/api/boost) in order to perform this action.

  - [channels.updateColor](https://core.telegram.org/method/channels.updateColor)
  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)
  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- BOT_ALREADY_DISABLED : The connected business bot was already disabled for the specified peer.

  - [account.disablePeerConnectedBot](https://core.telegram.org/method/account.disablePeerConnectedBot)

- BOT_APP_BOT_INVALID : The bot_id passed in the inputBotAppShortName constructor is invalid.

  - [messages.getBotApp](https://core.telegram.org/method/messages.getBotApp)
  - [messages.requestAppWebView](https://core.telegram.org/method/messages.requestAppWebView)

- BOT_APP_INVALID : The specified bot app is invalid.

  - [messages.getBotApp](https://core.telegram.org/method/messages.getBotApp)
  - [messages.requestAppWebView](https://core.telegram.org/method/messages.requestAppWebView)

- BOT_APP_SHORTNAME_INVALID : The specified bot app short name is invalid.

  - [messages.getBotApp](https://core.telegram.org/method/messages.getBotApp)
  - [messages.requestAppWebView](https://core.telegram.org/method/messages.requestAppWebView)

- BOT_BUSINESS_MISSING : The specified bot is not a business bot (the [user](https://core.telegram.org/constructor/user).`bot_business` flag is not set).

  - [account.updateConnectedBot](https://core.telegram.org/method/account.updateConnectedBot)

- BOT_CHANNELS_NA : Bots can't edit admin privileges.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- BOT_COMMAND_DESCRIPTION_INVALID : The specified command description is invalid.

  - [bots.setBotCommands](https://core.telegram.org/method/bots.setBotCommands)

- BOT_COMMAND_INVALID : The specified command is invalid.

  - [bots.setBotCommands](https://core.telegram.org/method/bots.setBotCommands)

- BOT_DOMAIN_INVALID : Bot domain invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- BOT_GAMES_DISABLED : Games can't be sent to channels.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- BOT_GROUPS_BLOCKED : This bot can't be added to groups.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)

- BOT_INLINE_DISABLED : This bot can't be used in inline mode.

  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)

- BOT_INVALID : This is not a valid bot.

  - [account.acceptAuthorization](https://core.telegram.org/method/account.acceptAuthorization)
  - [account.getAuthorizationForm](https://core.telegram.org/method/account.getAuthorizationForm)
  - [bots.addPreviewMedia](https://core.telegram.org/method/bots.addPreviewMedia)
  - [bots.allowSendMessage](https://core.telegram.org/method/bots.allowSendMessage)
  - [bots.canSendMessage](https://core.telegram.org/method/bots.canSendMessage)
  - [bots.checkDownloadFileParams](https://core.telegram.org/method/bots.checkDownloadFileParams)
  - [bots.deletePreviewMedia](https://core.telegram.org/method/bots.deletePreviewMedia)
  - [bots.editPreviewMedia](https://core.telegram.org/method/bots.editPreviewMedia)
  - [bots.getBotInfo](https://core.telegram.org/method/bots.getBotInfo)
  - [bots.getBotRecommendations](https://core.telegram.org/method/bots.getBotRecommendations)
  - [bots.getPreviewInfo](https://core.telegram.org/method/bots.getPreviewInfo)
  - [bots.getPreviewMedias](https://core.telegram.org/method/bots.getPreviewMedias)
  - [bots.invokeWebViewCustomMethod](https://core.telegram.org/method/bots.invokeWebViewCustomMethod)
  - [bots.reorderPreviewMedias](https://core.telegram.org/method/bots.reorderPreviewMedias)
  - [bots.reorderUsernames](https://core.telegram.org/method/bots.reorderUsernames)
  - [bots.setBotInfo](https://core.telegram.org/method/bots.setBotInfo)
  - [bots.setCustomVerification](https://core.telegram.org/method/bots.setCustomVerification)
  - [bots.toggleUserEmojiStatusPermission](https://core.telegram.org/method/bots.toggleUserEmojiStatusPermission)
  - [bots.toggleUsername](https://core.telegram.org/method/bots.toggleUsername)
  - [bots.updateStarRefProgram](https://core.telegram.org/method/bots.updateStarRefProgram)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.getAttachMenuBot](https://core.telegram.org/method/messages.getAttachMenuBot)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)
  - [messages.prolongWebView](https://core.telegram.org/method/messages.prolongWebView)
  - [messages.requestMainWebView](https://core.telegram.org/method/messages.requestMainWebView)
  - [messages.requestSimpleWebView](https://core.telegram.org/method/messages.requestSimpleWebView)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendWebViewData](https://core.telegram.org/method/messages.sendWebViewData)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [messages.toggleBotInAttachMenu](https://core.telegram.org/method/messages.toggleBotInAttachMenu)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- BOT_INVOICE_INVALID : The specified invoice is invalid.

  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)
  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)

- BOT_NOT_CONNECTED_YET : No [business bot](https://core.telegram.org/api/business#connected-bots) is connected to the currently logged in user.

  - [account.disablePeerConnectedBot](https://core.telegram.org/method/account.disablePeerConnectedBot)

- BOT_ONESIDE_NOT_AVAIL : Bots can't pin messages in PM just for themselves.

  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)

- BOT_PAYMENTS_DISABLED : Please enable bot payments in botfather before calling this method.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- BOT_RESPONSE_TIMEOUT : A timeout occurred while fetching data from the bot.

  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)

- BOT_SCORE_NOT_MODIFIED : The score wasn't modified.

  - [messages.setGameScore](https://core.telegram.org/method/messages.setGameScore)

- BOT_WEBVIEW_DISABLED : A webview cannot be opened in the specified conditions: emitted for example if `from_bot_menu` or `url` are set and `peer` is not the chat with the bot.

  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)

- BOTS_TOO_MUCH : There are too many bots in this chat/channel.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)

- BROADCAST_ID_INVALID : Broadcast ID invalid.

  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)

- BROADCAST_PUBLIC_VOTERS_FORBIDDEN : You can't forward polls with public voters.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- BROADCAST_REQUIRED : This method can only be called on a channel, please use stats.getMegagroupStats for supergroups.

  - [stats.getBroadcastStats](https://core.telegram.org/method/stats.getBroadcastStats)

- BUSINESS_PEER_INVALID : Messages can't be set to the specified peer through the current [business connection](https://core.telegram.org/api/business#connected-bots).

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)

- BUSINESS_PEER_USAGE_MISSING : You cannot send a message to a user through a [business connection](https://core.telegram.org/api/business#connected-bots) if the user hasn't recently contacted us.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)

- BUSINESS_RECIPIENTS_EMPTY : You didn't set any flag in inputBusinessBotRecipients, thus the bot cannot work with *any* peer.

  - [account.updateConnectedBot](https://core.telegram.org/method/account.updateConnectedBot)

- BUSINESS_WORK_HOURS_EMPTY : No work hours were specified.

  - [account.updateBusinessWorkHours](https://core.telegram.org/method/account.updateBusinessWorkHours)

- BUSINESS_WORK_HOURS_PERIOD_INVALID : The specified work hours are invalid, see [here &raquo;](https://core.telegram.org/api/business#opening-hours) for the exact requirements.

  - [account.updateBusinessWorkHours](https://core.telegram.org/method/account.updateBusinessWorkHours)

- BUTTON_COPY_TEXT_INVALID : The specified [keyboardButtonCopy](https://core.telegram.org/constructor/keyboardButtonCopy).`copy_text` is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- BUTTON_DATA_INVALID : The data of one or more of the buttons you provided is invalid.

  - [messages.editInlineBotMessage](https://core.telegram.org/method/messages.editInlineBotMessage)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- BUTTON_ID_INVALID : The specified button ID is invalid.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- BUTTON_POS_INVALID : The position of one of the keyboard buttons is invalid (i.e. a Game or Pay button not in the first position, and so on...).

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- BUTTON_TEXT_INVALID : The specified button text is invalid.

  - [bots.setBotMenuButton](https://core.telegram.org/method/bots.setBotMenuButton)

- BUTTON_TYPE_INVALID : The type of one or more of the buttons you provided is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- BUTTON_URL_INVALID : Button URL invalid.

  - [bots.setBotMenuButton](https://core.telegram.org/method/bots.setBotMenuButton)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [test.test](https://core.telegram.org/method/test.test)

- BUTTON_USER_INVALID : The `user_id` passed to inputKeyboardButtonUserProfile is invalid!

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- BUTTON_USER_PRIVACY_RESTRICTED : The privacy setting of the user specified in a [inputKeyboardButtonUserProfile](https://core.telegram.org/constructor/inputKeyboardButtonUserProfile) button do not allow creating such a button.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- CALL_ALREADY_ACCEPTED : The call was already accepted.

  - [phone.acceptCall](https://core.telegram.org/method/phone.acceptCall)
  - [phone.discardCall](https://core.telegram.org/method/phone.discardCall)

- CALL_ALREADY_DECLINED : The call was already declined.

  - [phone.acceptCall](https://core.telegram.org/method/phone.acceptCall)
  - [phone.confirmCall](https://core.telegram.org/method/phone.confirmCall)
  - [phone.receivedCall](https://core.telegram.org/method/phone.receivedCall)

- CALL_OCCUPY_FAILED : The call failed because the user is already making another call.

  - [phone.discardCall](https://core.telegram.org/method/phone.discardCall)

- CALL_PEER_INVALID : The provided call peer object is invalid.

  - [phone.acceptCall](https://core.telegram.org/method/phone.acceptCall)
  - [phone.confirmCall](https://core.telegram.org/method/phone.confirmCall)
  - [phone.discardCall](https://core.telegram.org/method/phone.discardCall)
  - [phone.receivedCall](https://core.telegram.org/method/phone.receivedCall)
  - [phone.saveCallDebug](https://core.telegram.org/method/phone.saveCallDebug)
  - [phone.saveCallLog](https://core.telegram.org/method/phone.saveCallLog)
  - [phone.sendSignalingData](https://core.telegram.org/method/phone.sendSignalingData)
  - [phone.setCallRating](https://core.telegram.org/method/phone.setCallRating)

- CALL_PROTOCOL_FLAGS_INVALID : Call protocol flags invalid.

  - [phone.acceptCall](https://core.telegram.org/method/phone.acceptCall)
  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)

- CDN_METHOD_INVALID : You can't call this method in a CDN DC.

  - [invokeWithLayer](https://core.telegram.org/method/invokeWithLayer)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)
  - [upload.getCdnFileHashes](https://core.telegram.org/method/upload.getCdnFileHashes)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)
  - [upload.reuploadCdnFile](https://core.telegram.org/method/upload.reuploadCdnFile)

- CHANNEL_FORUM_MISSING : This supergroup is not a forum.

  - [channels.createForumTopic](https://core.telegram.org/method/channels.createForumTopic)
  - [channels.deleteTopicHistory](https://core.telegram.org/method/channels.deleteTopicHistory)
  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)
  - [channels.getForumTopics](https://core.telegram.org/method/channels.getForumTopics)
  - [channels.getForumTopicsByID](https://core.telegram.org/method/channels.getForumTopicsByID)

- CHANNEL_ID_INVALID : The specified supergroup ID is invalid.

  - [channels.convertToGigagroup](https://core.telegram.org/method/channels.convertToGigagroup)

- CHANNEL_INVALID : The provided channel is invalid.

  - [account.getNotifySettings](https://core.telegram.org/method/account.getNotifySettings)
  - [account.updateNotifySettings](https://core.telegram.org/method/account.updateNotifySettings)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.clickSponsoredMessage](https://core.telegram.org/method/channels.clickSponsoredMessage)
  - [channels.convertToGigagroup](https://core.telegram.org/method/channels.convertToGigagroup)
  - [channels.createForumTopic](https://core.telegram.org/method/channels.createForumTopic)
  - [channels.deactivateAllUsernames](https://core.telegram.org/method/channels.deactivateAllUsernames)
  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)
  - [channels.deleteHistory](https://core.telegram.org/method/channels.deleteHistory)
  - [channels.deleteMessages](https://core.telegram.org/method/channels.deleteMessages)
  - [channels.deleteParticipantHistory](https://core.telegram.org/method/channels.deleteParticipantHistory)
  - [channels.deleteTopicHistory](https://core.telegram.org/method/channels.deleteTopicHistory)
  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editAbout](https://core.telegram.org/method/channels.editAbout)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)
  - [channels.editLocation](https://core.telegram.org/method/channels.editLocation)
  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [channels.exportInvite](https://core.telegram.org/method/channels.exportInvite)
  - [channels.exportMessageLink](https://core.telegram.org/method/channels.exportMessageLink)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.getChannelRecommendations](https://core.telegram.org/method/channels.getChannelRecommendations)
  - [channels.getChannels](https://core.telegram.org/method/channels.getChannels)
  - [channels.getForumTopics](https://core.telegram.org/method/channels.getForumTopics)
  - [channels.getForumTopicsByID](https://core.telegram.org/method/channels.getForumTopicsByID)
  - [channels.getFullChannel](https://core.telegram.org/method/channels.getFullChannel)
  - [channels.getMessages](https://core.telegram.org/method/channels.getMessages)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.getParticipants](https://core.telegram.org/method/channels.getParticipants)
  - [channels.getSendAs](https://core.telegram.org/method/channels.getSendAs)
  - [channels.getSponsoredMessages](https://core.telegram.org/method/channels.getSponsoredMessages)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [channels.readHistory](https://core.telegram.org/method/channels.readHistory)
  - [channels.readMessageContents](https://core.telegram.org/method/channels.readMessageContents)
  - [channels.reorderPinnedForumTopics](https://core.telegram.org/method/channels.reorderPinnedForumTopics)
  - [channels.reorderUsernames](https://core.telegram.org/method/channels.reorderUsernames)
  - [channels.reportAntiSpamFalsePositive](https://core.telegram.org/method/channels.reportAntiSpamFalsePositive)
  - [channels.reportSpam](https://core.telegram.org/method/channels.reportSpam)
  - [channels.reportSponsoredMessage](https://core.telegram.org/method/channels.reportSponsoredMessage)
  - [channels.restrictSponsoredMessages](https://core.telegram.org/method/channels.restrictSponsoredMessages)
  - [channels.setBoostsToUnblockRestrictions](https://core.telegram.org/method/channels.setBoostsToUnblockRestrictions)
  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)
  - [channels.setEmojiStickers](https://core.telegram.org/method/channels.setEmojiStickers)
  - [channels.setStickers](https://core.telegram.org/method/channels.setStickers)
  - [channels.toggleAntiSpam](https://core.telegram.org/method/channels.toggleAntiSpam)
  - [channels.toggleForum](https://core.telegram.org/method/channels.toggleForum)
  - [channels.toggleInvites](https://core.telegram.org/method/channels.toggleInvites)
  - [channels.toggleJoinRequest](https://core.telegram.org/method/channels.toggleJoinRequest)
  - [channels.toggleJoinToSend](https://core.telegram.org/method/channels.toggleJoinToSend)
  - [channels.toggleParticipantsHidden](https://core.telegram.org/method/channels.toggleParticipantsHidden)
  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)
  - [channels.toggleSignatures](https://core.telegram.org/method/channels.toggleSignatures)
  - [channels.toggleSlowMode](https://core.telegram.org/method/channels.toggleSlowMode)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)
  - [channels.toggleViewForumAsMessages](https://core.telegram.org/method/channels.toggleViewForumAsMessages)
  - [channels.updateColor](https://core.telegram.org/method/channels.updateColor)
  - [channels.updateEmojiStatus](https://core.telegram.org/method/channels.updateEmojiStatus)
  - [channels.updatePinnedForumTopic](https://core.telegram.org/method/channels.updatePinnedForumTopic)
  - [channels.updatePinnedMessage](https://core.telegram.org/method/channels.updatePinnedMessage)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [channels.viewSponsoredMessage](https://core.telegram.org/method/channels.viewSponsoredMessage)
  - [chatlists.editExportedInvite](https://core.telegram.org/method/chatlists.editExportedInvite)
  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)
  - [messages.getDiscussionMessage](https://core.telegram.org/method/messages.getDiscussionMessage)
  - [messages.getExportedChatInvites](https://core.telegram.org/method/messages.getExportedChatInvites)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)
  - [messages.getMessagesReactions](https://core.telegram.org/method/messages.getMessagesReactions)
  - [messages.getMessagesViews](https://core.telegram.org/method/messages.getMessagesViews)
  - [messages.getPeerDialogs](https://core.telegram.org/method/messages.getPeerDialogs)
  - [messages.getPeerSettings](https://core.telegram.org/method/messages.getPeerSettings)
  - [messages.getReplies](https://core.telegram.org/method/messages.getReplies)
  - [messages.getUnreadMentions](https://core.telegram.org/method/messages.getUnreadMentions)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)
  - [messages.readMentions](https://core.telegram.org/method/messages.readMentions)
  - [messages.report](https://core.telegram.org/method/messages.report)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [premium.getBoostsStatus](https://core.telegram.org/method/premium.getBoostsStatus)
  - [stats.getBroadcastRevenueStats](https://core.telegram.org/method/stats.getBroadcastRevenueStats)
  - [stats.getBroadcastRevenueTransactions](https://core.telegram.org/method/stats.getBroadcastRevenueTransactions)
  - [stats.getBroadcastStats](https://core.telegram.org/method/stats.getBroadcastStats)
  - [stats.getMegagroupStats](https://core.telegram.org/method/stats.getMegagroupStats)
  - [stats.getMessagePublicForwards](https://core.telegram.org/method/stats.getMessagePublicForwards)
  - [stats.getMessageStats](https://core.telegram.org/method/stats.getMessageStats)
  - [stories.getBoostersList](https://core.telegram.org/method/stories.getBoostersList)
  - [stories.getPeerStories](https://core.telegram.org/method/stories.getPeerStories)
  - [stories.getStoriesByID](https://core.telegram.org/method/stories.getStoriesByID)
  - [stories.getStoriesViews](https://core.telegram.org/method/stories.getStoriesViews)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)
  - [users.getFullUser](https://core.telegram.org/method/users.getFullUser)
  - [users.getUsers](https://core.telegram.org/method/users.getUsers)

- CHANNEL_PARICIPANT_MISSING : The current user is not in the channel.

  - [channels.deleteHistory](https://core.telegram.org/method/channels.deleteHistory)

- CHANNEL_PRIVATE : You haven't joined this channel/supergroup.

  - [account.getNotifySettings](https://core.telegram.org/method/account.getNotifySettings)
  - [account.reportPeer](https://core.telegram.org/method/account.reportPeer)
  - [account.updateNotifySettings](https://core.telegram.org/method/account.updateNotifySettings)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)
  - [channels.deleteHistory](https://core.telegram.org/method/channels.deleteHistory)
  - [channels.deleteMessages](https://core.telegram.org/method/channels.deleteMessages)
  - [channels.deleteParticipantHistory](https://core.telegram.org/method/channels.deleteParticipantHistory)
  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [channels.exportMessageLink](https://core.telegram.org/method/channels.exportMessageLink)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.getChannelRecommendations](https://core.telegram.org/method/channels.getChannelRecommendations)
  - [channels.getChannels](https://core.telegram.org/method/channels.getChannels)
  - [channels.getForumTopics](https://core.telegram.org/method/channels.getForumTopics)
  - [channels.getFullChannel](https://core.telegram.org/method/channels.getFullChannel)
  - [channels.getMessages](https://core.telegram.org/method/channels.getMessages)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.getParticipants](https://core.telegram.org/method/channels.getParticipants)
  - [channels.getSendAs](https://core.telegram.org/method/channels.getSendAs)
  - [channels.getSponsoredMessages](https://core.telegram.org/method/channels.getSponsoredMessages)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [channels.readHistory](https://core.telegram.org/method/channels.readHistory)
  - [channels.readMessageContents](https://core.telegram.org/method/channels.readMessageContents)
  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [channels.viewSponsoredMessage](https://core.telegram.org/method/channels.viewSponsoredMessage)
  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)
  - [contacts.addContact](https://core.telegram.org/method/contacts.addContact)
  - [contacts.block](https://core.telegram.org/method/contacts.block)
  - [contacts.unblock](https://core.telegram.org/method/contacts.unblock)
  - [folders.editPeerFolders](https://core.telegram.org/method/folders.editPeerFolders)
  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)
  - [messages.getDiscussionMessage](https://core.telegram.org/method/messages.getDiscussionMessage)
  - [messages.getExportedChatInvite](https://core.telegram.org/method/messages.getExportedChatInvite)
  - [messages.getExportedChatInvites](https://core.telegram.org/method/messages.getExportedChatInvites)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)
  - [messages.getMessagesReactions](https://core.telegram.org/method/messages.getMessagesReactions)
  - [messages.getMessagesViews](https://core.telegram.org/method/messages.getMessagesViews)
  - [messages.getOnlines](https://core.telegram.org/method/messages.getOnlines)
  - [messages.getPeerDialogs](https://core.telegram.org/method/messages.getPeerDialogs)
  - [messages.getPeerSettings](https://core.telegram.org/method/messages.getPeerSettings)
  - [messages.getReplies](https://core.telegram.org/method/messages.getReplies)
  - [messages.getUnreadMentions](https://core.telegram.org/method/messages.getUnreadMentions)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)
  - [messages.readHistory](https://core.telegram.org/method/messages.readHistory)
  - [messages.readMentions](https://core.telegram.org/method/messages.readMentions)
  - [messages.report](https://core.telegram.org/method/messages.report)
  - [messages.reportSpam](https://core.telegram.org/method/messages.reportSpam)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.toggleDialogPin](https://core.telegram.org/method/messages.toggleDialogPin)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [phone.createGroupCall](https://core.telegram.org/method/phone.createGroupCall)
  - [stats.getBroadcastStats](https://core.telegram.org/method/stats.getBroadcastStats)
  - [stories.getPeerStories](https://core.telegram.org/method/stories.getPeerStories)
  - [stories.getStoriesByID](https://core.telegram.org/method/stories.getStoriesByID)
  - [stories.getStoriesViews](https://core.telegram.org/method/stories.getStoriesViews)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)
  - [users.getFullUser](https://core.telegram.org/method/users.getFullUser)
  - [users.getUsers](https://core.telegram.org/method/users.getUsers)

- CHANNEL_TOO_BIG : This channel has too many participants (>1000) to be deleted.

  - [channels.deleteHistory](https://core.telegram.org/method/channels.deleteHistory)

- CHANNEL_TOO_LARGE : Channel is too large to be deleted; this error is issued when trying to delete channels with more than 1000 members (subject to change).

  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)

- CHANNELS_ADMIN_LOCATED_TOO_MUCH : The user has reached the limit of public geogroups.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [channels.getAdminedPublicChannels](https://core.telegram.org/method/channels.getAdminedPublicChannels)

- CHANNELS_ADMIN_PUBLIC_TOO_MUCH : You're admin of too many public channels, make some channels private to change the username of this channel.

  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [channels.getAdminedPublicChannels](https://core.telegram.org/method/channels.getAdminedPublicChannels)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)

- CHANNELS_TOO_MUCH : You have joined too many channels/supergroups.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)
  - [messages.migrateChat](https://core.telegram.org/method/messages.migrateChat)

- CHARGE_ALREADY_REFUNDED : The transaction was already refunded.

  - [payments.refundStarsCharge](https://core.telegram.org/method/payments.refundStarsCharge)

- CHAT_ABOUT_NOT_MODIFIED : About text has not changed.

  - [channels.editAbout](https://core.telegram.org/method/channels.editAbout)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)

- CHAT_ABOUT_TOO_LONG : Chat about too long.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [channels.editAbout](https://core.telegram.org/method/channels.editAbout)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)

- CHAT_ADMIN_REQUIRED : You must be an admin in this chat to do this.

  - [channels.convertToGigagroup](https://core.telegram.org/method/channels.convertToGigagroup)
  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)
  - [channels.deleteHistory](https://core.telegram.org/method/channels.deleteHistory)
  - [channels.deleteParticipantHistory](https://core.telegram.org/method/channels.deleteParticipantHistory)
  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editAbout](https://core.telegram.org/method/channels.editAbout)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [channels.editLocation](https://core.telegram.org/method/channels.editLocation)
  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [channels.exportInvite](https://core.telegram.org/method/channels.exportInvite)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.getParticipants](https://core.telegram.org/method/channels.getParticipants)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.reportSpam](https://core.telegram.org/method/channels.reportSpam)
  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)
  - [channels.toggleInvites](https://core.telegram.org/method/channels.toggleInvites)
  - [channels.toggleJoinRequest](https://core.telegram.org/method/channels.toggleJoinRequest)
  - [channels.toggleJoinToSend](https://core.telegram.org/method/channels.toggleJoinToSend)
  - [channels.toggleParticipantsHidden](https://core.telegram.org/method/channels.toggleParticipantsHidden)
  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)
  - [channels.toggleSignatures](https://core.telegram.org/method/channels.toggleSignatures)
  - [channels.toggleSlowMode](https://core.telegram.org/method/channels.toggleSlowMode)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)
  - [channels.updatePinnedMessage](https://core.telegram.org/method/channels.updatePinnedMessage)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.checkHistoryImportPeer](https://core.telegram.org/method/messages.checkHistoryImportPeer)
  - [messages.deleteChat](https://core.telegram.org/method/messages.deleteChat)
  - [messages.deleteChatUser](https://core.telegram.org/method/messages.deleteChatUser)
  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [messages.editChatTitle](https://core.telegram.org/method/messages.editChatTitle)
  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getAdminsWithInvites](https://core.telegram.org/method/messages.getAdminsWithInvites)
  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)
  - [messages.getExportedChatInvite](https://core.telegram.org/method/messages.getExportedChatInvite)
  - [messages.getExportedChatInvites](https://core.telegram.org/method/messages.getExportedChatInvites)
  - [messages.getMessageEditData](https://core.telegram.org/method/messages.getMessageEditData)
  - [messages.getScheduledHistory](https://core.telegram.org/method/messages.getScheduledHistory)
  - [messages.getScheduledMessages](https://core.telegram.org/method/messages.getScheduledMessages)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.initHistoryImport](https://core.telegram.org/method/messages.initHistoryImport)
  - [messages.migrateChat](https://core.telegram.org/method/messages.migrateChat)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.setChatAvailableReactions](https://core.telegram.org/method/messages.setChatAvailableReactions)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [messages.toggleNoForwards](https://core.telegram.org/method/messages.toggleNoForwards)
  - [messages.unpinAllMessages](https://core.telegram.org/method/messages.unpinAllMessages)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)
  - [messages.uploadImportedMedia](https://core.telegram.org/method/messages.uploadImportedMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [payments.getStarsTransactions](https://core.telegram.org/method/payments.getStarsTransactions)
  - [phone.createGroupCall](https://core.telegram.org/method/phone.createGroupCall)
  - [phone.getGroupCallStreamRtmpUrl](https://core.telegram.org/method/phone.getGroupCallStreamRtmpUrl)
  - [premium.getBoostsList](https://core.telegram.org/method/premium.getBoostsList)
  - [stats.getBroadcastRevenueStats](https://core.telegram.org/method/stats.getBroadcastRevenueStats)
  - [stats.getBroadcastStats](https://core.telegram.org/method/stats.getBroadcastStats)
  - [stats.getMegagroupStats](https://core.telegram.org/method/stats.getMegagroupStats)
  - [stats.getMessagePublicForwards](https://core.telegram.org/method/stats.getMessagePublicForwards)
  - [stats.getMessageStats](https://core.telegram.org/method/stats.getMessageStats)
  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)
  - [stories.getBoostersList](https://core.telegram.org/method/stories.getBoostersList)
  - [stories.getStoriesArchive](https://core.telegram.org/method/stories.getStoriesArchive)

- CHAT_DISCUSSION_UNALLOWED : You can't enable forum topics in a discussion group linked to a channel.

  - [channels.toggleForum](https://core.telegram.org/method/channels.toggleForum)

- CHAT_FORWARDS_RESTRICTED : You can't forward messages from a protected chat.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- CHAT_ID_EMPTY : The provided chat ID is empty.

  - [messages.discardEncryption](https://core.telegram.org/method/messages.discardEncryption)

- CHAT_ID_INVALID : The provided chat id is invalid.

  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.getSendAs](https://core.telegram.org/method/channels.getSendAs)
  - [channels.setStickers](https://core.telegram.org/method/channels.setStickers)
  - [channels.toggleJoinRequest](https://core.telegram.org/method/channels.toggleJoinRequest)
  - [channels.toggleJoinToSend](https://core.telegram.org/method/channels.toggleJoinToSend)
  - [channels.toggleParticipantsHidden](https://core.telegram.org/method/channels.toggleParticipantsHidden)
  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)
  - [channels.toggleSignatures](https://core.telegram.org/method/channels.toggleSignatures)
  - [channels.toggleSlowMode](https://core.telegram.org/method/channels.toggleSlowMode)
  - [channels.updatePinnedMessage](https://core.telegram.org/method/channels.updatePinnedMessage)
  - [folders.editPeerFolders](https://core.telegram.org/method/folders.editPeerFolders)
  - [messages.acceptEncryption](https://core.telegram.org/method/messages.acceptEncryption)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.deleteChat](https://core.telegram.org/method/messages.deleteChat)
  - [messages.deleteChatUser](https://core.telegram.org/method/messages.deleteChatUser)
  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editChatAdmin](https://core.telegram.org/method/messages.editChatAdmin)
  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [messages.editChatTitle](https://core.telegram.org/method/messages.editChatTitle)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessage](https://core.telegram.org/method/messages.forwardMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getChats](https://core.telegram.org/method/messages.getChats)
  - [messages.getFullChat](https://core.telegram.org/method/messages.getFullChat)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)
  - [messages.getMessagesViews](https://core.telegram.org/method/messages.getMessagesViews)
  - [messages.getOnlines](https://core.telegram.org/method/messages.getOnlines)
  - [messages.migrateChat](https://core.telegram.org/method/messages.migrateChat)
  - [messages.readDiscussion](https://core.telegram.org/method/messages.readDiscussion)
  - [messages.readEncryptedHistory](https://core.telegram.org/method/messages.readEncryptedHistory)
  - [messages.readHistory](https://core.telegram.org/method/messages.readHistory)
  - [messages.reportEncryptedSpam](https://core.telegram.org/method/messages.reportEncryptedSpam)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedFile](https://core.telegram.org/method/messages.sendEncryptedFile)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setEncryptedTyping](https://core.telegram.org/method/messages.setEncryptedTyping)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.toggleChatAdmins](https://core.telegram.org/method/messages.toggleChatAdmins)
  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)
  - [messages.uploadEncryptedFile](https://core.telegram.org/method/messages.uploadEncryptedFile)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- CHAT_INVALID : Invalid chat.

  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [messages.createChat](https://core.telegram.org/method/messages.createChat)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- CHAT_INVITE_PERMANENT : You can't set an expiration date on permanent invite links.

  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)

- CHAT_LINK_EXISTS : The chat is public, you can't hide the history to new users.

  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)

- CHAT_MEMBER_ADD_FAILED : Could not add participants.

  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)

- CHAT_NOT_MODIFIED : No changes were made to chat information because the new information you passed is identical to the current information.

  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [channels.editLocation](https://core.telegram.org/method/channels.editLocation)
  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [channels.getFullChannel](https://core.telegram.org/method/channels.getFullChannel)
  - [channels.reorderUsernames](https://core.telegram.org/method/channels.reorderUsernames)
  - [channels.toggleAntiSpam](https://core.telegram.org/method/channels.toggleAntiSpam)
  - [channels.toggleForum](https://core.telegram.org/method/channels.toggleForum)
  - [channels.toggleInvites](https://core.telegram.org/method/channels.toggleInvites)
  - [channels.toggleJoinRequest](https://core.telegram.org/method/channels.toggleJoinRequest)
  - [channels.toggleJoinToSend](https://core.telegram.org/method/channels.toggleJoinToSend)
  - [channels.toggleParticipantsHidden](https://core.telegram.org/method/channels.toggleParticipantsHidden)
  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)
  - [channels.toggleSignatures](https://core.telegram.org/method/channels.toggleSignatures)
  - [channels.toggleSlowMode](https://core.telegram.org/method/channels.toggleSlowMode)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)
  - [channels.updatePinnedMessage](https://core.telegram.org/method/channels.updatePinnedMessage)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [messages.editChatTitle](https://core.telegram.org/method/messages.editChatTitle)
  - [messages.setChatAvailableReactions](https://core.telegram.org/method/messages.setChatAvailableReactions)
  - [messages.setHistoryTTL](https://core.telegram.org/method/messages.setHistoryTTL)
  - [messages.toggleChatAdmins](https://core.telegram.org/method/messages.toggleChatAdmins)
  - [messages.toggleNoForwards](https://core.telegram.org/method/messages.toggleNoForwards)
  - [messages.unpinAllMessages](https://core.telegram.org/method/messages.unpinAllMessages)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)

- CHAT_PUBLIC_REQUIRED : You can only enable join requests in public groups.

  - [channels.toggleJoinRequest](https://core.telegram.org/method/channels.toggleJoinRequest)

- CHAT_RESTRICTED : You can't send messages in this chat, you were restricted.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- CHAT_REVOKE_DATE_UNSUPPORTED : `min_date` and `max_date` are not available for using with non-user peers.

  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)

- CHAT_SEND_INLINE_FORBIDDEN : You can't send inline messages in this group.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- CHAT_TITLE_EMPTY : No chat title provided.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [messages.createChat](https://core.telegram.org/method/messages.createChat)
  - [messages.editChatTitle](https://core.telegram.org/method/messages.editChatTitle)

- CHAT_TOO_BIG : This method is not available for groups with more than `chat_read_mark_size_threshold` members, [see client configuration &raquo;](https://core.telegram.org/api/config#client-configuration).

  - [messages.getMessageReadParticipants](https://core.telegram.org/method/messages.getMessageReadParticipants)
  - [messages.getMessagesReadParticipants](https://core.telegram.org/method/messages.getMessagesReadParticipants)

- CHATLINK_SLUG_EMPTY : The specified slug is empty.

  - [account.deleteBusinessChatLink](https://core.telegram.org/method/account.deleteBusinessChatLink)
  - [account.editBusinessChatLink](https://core.telegram.org/method/account.editBusinessChatLink)
  - [account.resolveBusinessChatLink](https://core.telegram.org/method/account.resolveBusinessChatLink)

- CHATLINK_SLUG_EXPIRED : The specified [business chat link](https://core.telegram.org/api/business#business-chat-links) has expired.

  - [account.deleteBusinessChatLink](https://core.telegram.org/method/account.deleteBusinessChatLink)
  - [account.resolveBusinessChatLink](https://core.telegram.org/method/account.resolveBusinessChatLink)

- CHATLINKS_TOO_MUCH : Too many [business chat links](https://core.telegram.org/api/business#business-chat-links) were created, please delete some older links.

  - [account.createBusinessChatLink](https://core.telegram.org/method/account.createBusinessChatLink)

- CHATLIST_EXCLUDE_INVALID : The specified `exclude_peers` are invalid.

  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)

- CHATLISTS_TOO_MUCH : You have created too many folder links, hitting the `chatlist_invites_limit_default`/`chatlist_invites_limit_premium` [limits &raquo;](https://core.telegram.org/api/config#chatlist-invites-limit-default).

  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)

- CODE_EMPTY : The provided code is empty.

  - [auth.checkRecoveryPassword](https://core.telegram.org/method/auth.checkRecoveryPassword)
  - [auth.recoverPassword](https://core.telegram.org/method/auth.recoverPassword)

- CODE_HASH_INVALID : Code hash invalid.

  - [account.confirmPhone](https://core.telegram.org/method/account.confirmPhone)

- CODE_INVALID : Code invalid.

  - [account.confirmPasswordEmail](https://core.telegram.org/method/account.confirmPasswordEmail)

- COLLECTIBLE_INVALID : The specified collectible is invalid.

  - [fragment.getCollectibleInfo](https://core.telegram.org/method/fragment.getCollectibleInfo)

- COLLECTIBLE_NOT_FOUND : The specified collectible could not be found.

  - [fragment.getCollectibleInfo](https://core.telegram.org/method/fragment.getCollectibleInfo)

- COLOR_INVALID : The specified color palette ID was invalid.

  - [account.updateColor](https://core.telegram.org/method/account.updateColor)

- CONNECTION_API_ID_INVALID : The provided API id is invalid.

  - [help.getConfig](https://core.telegram.org/method/help.getConfig)
  - [invokeWithLayer](https://core.telegram.org/method/invokeWithLayer)

- CONNECTION_APP_VERSION_EMPTY : App version is empty.

  - [help.getConfig](https://core.telegram.org/method/help.getConfig)

- CONNECTION_ID_INVALID : The specified connection ID is invalid.

  - [account.getBotBusinessConnection](https://core.telegram.org/method/account.getBotBusinessConnection)

- CONNECTION_LAYER_INVALID : Layer invalid.

  - [contacts.resolveUsername](https://core.telegram.org/method/contacts.resolveUsername)
  - [help.getConfig](https://core.telegram.org/method/help.getConfig)
  - [initConnection](https://core.telegram.org/method/initConnection)

- CONTACT_ADD_MISSING : Contact to add is missing.

  - [contacts.acceptContact](https://core.telegram.org/method/contacts.acceptContact)

- CONTACT_ID_INVALID : The provided contact ID is invalid.

  - [contacts.acceptContact](https://core.telegram.org/method/contacts.acceptContact)
  - [contacts.addContact](https://core.telegram.org/method/contacts.addContact)
  - [contacts.block](https://core.telegram.org/method/contacts.block)
  - [contacts.deleteContact](https://core.telegram.org/method/contacts.deleteContact)
  - [contacts.unblock](https://core.telegram.org/method/contacts.unblock)

- CONTACT_MISSING : The specified user is not a contact.

  - [photos.uploadContactProfilePhoto](https://core.telegram.org/method/photos.uploadContactProfilePhoto)

- CONTACT_NAME_EMPTY : Contact name empty.

  - [contacts.addContact](https://core.telegram.org/method/contacts.addContact)

- CONTACT_REQ_MISSING : Missing contact request.

  - [contacts.acceptContact](https://core.telegram.org/method/contacts.acceptContact)

- CREATE_CALL_FAILED : An error occurred while creating the call.

  - [phone.createGroupCall](https://core.telegram.org/method/phone.createGroupCall)

- CURRENCY_TOTAL_AMOUNT_INVALID : The total amount of all prices is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- CUSTOM_REACTIONS_TOO_MANY : Too many custom reactions were specified.

  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)

- DATA_INVALID : Encrypted data invalid.

  - [help.getConfig](https://core.telegram.org/method/help.getConfig)
  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)

- DATA_JSON_INVALID : The provided JSON data is invalid.

  - [bots.answerWebhookJSONQuery](https://core.telegram.org/method/bots.answerWebhookJSONQuery)
  - [bots.invokeWebViewCustomMethod](https://core.telegram.org/method/bots.invokeWebViewCustomMethod)
  - [bots.sendCustomRequest](https://core.telegram.org/method/bots.sendCustomRequest)
  - [help.acceptTermsOfService](https://core.telegram.org/method/help.acceptTermsOfService)
  - [payments.assignPlayMarketTransaction](https://core.telegram.org/method/payments.assignPlayMarketTransaction)
  - [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall)
  - [phone.saveCallDebug](https://core.telegram.org/method/phone.saveCallDebug)

- DATA_TOO_LONG : Data too long.

  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedFile](https://core.telegram.org/method/messages.sendEncryptedFile)

- DATE_EMPTY : Date empty.

  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- DC_ID_INVALID : The provided DC ID is invalid.

  - [auth.exportAuthorization](https://core.telegram.org/method/auth.exportAuthorization)

- DH_G_A_INVALID : g_a invalid.

  - [messages.requestEncryption](https://core.telegram.org/method/messages.requestEncryption)

- DOCUMENT_INVALID : The specified document is invalid.

  - [account.updateEmojiStatus](https://core.telegram.org/method/account.updateEmojiStatus)
  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [upload.getWebFile](https://core.telegram.org/method/upload.getWebFile)

- EMAIL_HASH_EXPIRED : Email hash expired.

  - [account.cancelPasswordEmail](https://core.telegram.org/method/account.cancelPasswordEmail)
  - [account.confirmPasswordEmail](https://core.telegram.org/method/account.confirmPasswordEmail)
  - [account.resendPasswordEmail](https://core.telegram.org/method/account.resendPasswordEmail)

- EMAIL_INVALID : The specified email is invalid.

  - [account.sendVerifyEmailCode](https://core.telegram.org/method/account.sendVerifyEmailCode)
  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)
  - [account.verifyEmail](https://core.telegram.org/method/account.verifyEmail)

- EMAIL_NOT_ALLOWED : The specified email cannot be used to complete the operation.

  - [account.sendVerifyEmailCode](https://core.telegram.org/method/account.sendVerifyEmailCode)
  - [account.verifyEmail](https://core.telegram.org/method/account.verifyEmail)

- EMAIL_NOT_SETUP : In order to change the login email with emailVerifyPurposeLoginChange, an existing login email must already be set using emailVerifyPurposeLoginSetup.

  - [account.sendVerifyEmailCode](https://core.telegram.org/method/account.sendVerifyEmailCode)

- EMAIL_UNCONFIRMED : Email unconfirmed.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)

- EMAIL_UNCONFIRMED_%d : The provided email isn't confirmed, %d is the length of the verification code that was just sent to the email: use [account.verifyEmail](https://core.telegram.org/method/account.verifyEmail) to enter the received verification code and enable the recovery email.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)

- EMAIL_VERIFY_EXPIRED : The verification email has expired.

  - [account.verifyEmail](https://core.telegram.org/method/account.verifyEmail)

- EMOJI_INVALID : The specified theme emoji is valid.

  - [messages.setChatTheme](https://core.telegram.org/method/messages.setChatTheme)

- EMOJI_MARKUP_INVALID : The specified `video_emoji_markup` was invalid.

  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- EMOJI_NOT_MODIFIED : The theme wasn't changed.

  - [messages.setChatTheme](https://core.telegram.org/method/messages.setChatTheme)

- EMOTICON_EMPTY : The emoji is empty.

  - [messages.getStickers](https://core.telegram.org/method/messages.getStickers)
  - [messages.searchCustomEmoji](https://core.telegram.org/method/messages.searchCustomEmoji)

- EMOTICON_INVALID : The specified emoji is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- EMOTICON_STICKERPACK_MISSING : inputStickerSetDice.emoji cannot be empty.

  - [messages.getStickerSet](https://core.telegram.org/method/messages.getStickerSet)

- ENCRYPTED_MESSAGE_INVALID : Encrypted message invalid.

  - [auth.bindTempAuthKey](https://core.telegram.org/method/auth.bindTempAuthKey)

- ENCRYPTION_ALREADY_ACCEPTED : Secret chat already accepted.

  - [messages.acceptEncryption](https://core.telegram.org/method/messages.acceptEncryption)
  - [messages.discardEncryption](https://core.telegram.org/method/messages.discardEncryption)

- ENCRYPTION_ALREADY_DECLINED : The secret chat was already declined.

  - [messages.acceptEncryption](https://core.telegram.org/method/messages.acceptEncryption)
  - [messages.discardEncryption](https://core.telegram.org/method/messages.discardEncryption)

- ENCRYPTION_DECLINED : The secret chat was declined.

  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedFile](https://core.telegram.org/method/messages.sendEncryptedFile)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- ENCRYPTION_ID_INVALID : The provided secret chat ID is invalid.

  - [messages.discardEncryption](https://core.telegram.org/method/messages.discardEncryption)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)

- ENTITIES_TOO_LONG : You provided too many styled message entities.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- ENTITY_BOUNDS_INVALID : A specified [entity offset or length](https://core.telegram.org/api/entities#entity-length) is invalid, see [here &raquo;](https://core.telegram.org/api/entities#entity-length) for info on how to properly compute the entity offset/length.

  - [help.editUserInfo](https://core.telegram.org/method/help.editUserInfo)
  - [messages.editInlineBotMessage](https://core.telegram.org/method/messages.editInlineBotMessage)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.getWebPagePreview](https://core.telegram.org/method/messages.getWebPagePreview)
  - [messages.saveDraft](https://core.telegram.org/method/messages.saveDraft)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- ENTITY_MENTION_USER_INVALID : You mentioned an invalid user.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- ERROR_TEXT_EMPTY : The provided error message is empty.

  - [messages.setBotPrecheckoutResults](https://core.telegram.org/method/messages.setBotPrecheckoutResults)

- EXPIRE_DATE_INVALID : The specified expiration date is invalid.

  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)

- EXPORT_CARD_INVALID : Provided card is invalid.

  - [contacts.importCard](https://core.telegram.org/method/contacts.importCard)

- EXTENDED_MEDIA_AMOUNT_INVALID : The specified `stars_amount` of the passed [inputMediaPaidMedia](https://core.telegram.org/constructor/inputMediaPaidMedia) is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- EXTERNAL_URL_INVALID : External URL invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- FILE_CONTENT_TYPE_INVALID : File content-type is invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- FILE_EMTPY : An empty file was provided.

  - [messages.sendEncryptedFile](https://core.telegram.org/method/messages.sendEncryptedFile)

- FILE_ID_INVALID : The provided file id is invalid.

  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- FILE_PART_EMPTY : The provided file part is empty.

  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)
  - [upload.saveFilePart](https://core.telegram.org/method/upload.saveFilePart)

- FILE_PART_INVALID : The file part number is invalid.

  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)
  - [upload.saveFilePart](https://core.telegram.org/method/upload.saveFilePart)

- FILE_PART_LENGTH_INVALID : The length of a file part is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- FILE_PART_SIZE_CHANGED : Provided file part size has changed.

  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)

- FILE_PART_SIZE_INVALID : The provided file part size is invalid.

  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)

- FILE_PART_TOO_BIG : The uploaded file part is too big.

  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)

- FILE_PART_TOO_SMALL : The size of the uploaded file part is too small, please see the documentation for the allowed sizes.

  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)

- FILE_PARTS_INVALID : The number of file parts is invalid.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)
  - [upload.saveBigFilePart](https://core.telegram.org/method/upload.saveBigFilePart)

- FILE_REFERENCE_%d_EXPIRED : The file reference of the media file at index %d in the passed media array expired, it [must be refreshed](https://core.telegram.org/api/file_reference).

  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- FILE_REFERENCE_%d_INVALID : The file reference of the media file at index %d in the passed media array is invalid.

  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- FILE_REFERENCE_EMPTY : An empty [file reference](https://core.telegram.org/api/file_reference) was specified.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- FILE_REFERENCE_EXPIRED : File reference expired, it must be refetched as described in [the documentation](https://core.telegram.org/api/file_reference).

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- FILE_REFERENCE_INVALID : The specified [file reference](https://core.telegram.org/api/file_reference) is invalid.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)

- FILE_TITLE_EMPTY : An empty file title was specified.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- FILE_TOKEN_INVALID : The master DC did not accept the `file_token` (e.g., the token has expired). Continue downloading the file from the master DC using upload.getFile.

  - [upload.getCdnFile](https://core.telegram.org/method/upload.getCdnFile)
  - [upload.getCdnFileHashes](https://core.telegram.org/method/upload.getCdnFileHashes)
  - [upload.reuploadCdnFile](https://core.telegram.org/method/upload.reuploadCdnFile)

- FILTER_ID_INVALID : The specified filter ID is invalid.

  - [chatlists.deleteExportedInvite](https://core.telegram.org/method/chatlists.deleteExportedInvite)
  - [chatlists.editExportedInvite](https://core.telegram.org/method/chatlists.editExportedInvite)
  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)
  - [chatlists.getChatlistUpdates](https://core.telegram.org/method/chatlists.getChatlistUpdates)
  - [chatlists.getExportedInvites](https://core.telegram.org/method/chatlists.getExportedInvites)
  - [chatlists.getLeaveChatlistSuggestions](https://core.telegram.org/method/chatlists.getLeaveChatlistSuggestions)
  - [chatlists.hideChatlistUpdates](https://core.telegram.org/method/chatlists.hideChatlistUpdates)
  - [chatlists.joinChatlistUpdates](https://core.telegram.org/method/chatlists.joinChatlistUpdates)
  - [chatlists.leaveChatlist](https://core.telegram.org/method/chatlists.leaveChatlist)
  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)

- FILTER_INCLUDE_EMPTY : The include_peers vector of the filter is empty.

  - [chatlists.joinChatlistInvite](https://core.telegram.org/method/chatlists.joinChatlistInvite)
  - [chatlists.joinChatlistUpdates](https://core.telegram.org/method/chatlists.joinChatlistUpdates)
  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)

- FILTER_NOT_SUPPORTED : The specified filter cannot be used in this context.

  - [chatlists.deleteExportedInvite](https://core.telegram.org/method/chatlists.deleteExportedInvite)
  - [chatlists.editExportedInvite](https://core.telegram.org/method/chatlists.editExportedInvite)
  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)
  - [chatlists.getChatlistUpdates](https://core.telegram.org/method/chatlists.getChatlistUpdates)
  - [chatlists.getLeaveChatlistSuggestions](https://core.telegram.org/method/chatlists.getLeaveChatlistSuggestions)
  - [chatlists.hideChatlistUpdates](https://core.telegram.org/method/chatlists.hideChatlistUpdates)
  - [messages.getSearchResultsCalendar](https://core.telegram.org/method/messages.getSearchResultsCalendar)
  - [messages.searchSentMedia](https://core.telegram.org/method/messages.searchSentMedia)

- FILTER_TITLE_EMPTY : The title field of the filter is empty.

  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)

- FIRSTNAME_INVALID : The first name is invalid.

  - [account.updateProfile](https://core.telegram.org/method/account.updateProfile)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- FOLDER_ID_EMPTY : An empty folder ID was specified.

  - [folders.deleteFolder](https://core.telegram.org/method/folders.deleteFolder)

- FOLDER_ID_INVALID : Invalid folder ID.

  - [folders.deleteFolder](https://core.telegram.org/method/folders.deleteFolder)
  - [folders.editPeerFolders](https://core.telegram.org/method/folders.editPeerFolders)
  - [messages.getDialogs](https://core.telegram.org/method/messages.getDialogs)
  - [messages.getPinnedDialogs](https://core.telegram.org/method/messages.getPinnedDialogs)
  - [messages.searchGlobal](https://core.telegram.org/method/messages.searchGlobal)

- FORM_EXPIRED : The form was generated more than 10 minutes ago and has expired, please re-generate it using [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm) and pass the new `form_id`.

  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)

- FORM_ID_EMPTY : The specified form ID is empty.

  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)

- FORM_UNSUPPORTED : Please update your client.

  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)

- FORUM_ENABLED : You can't execute the specified action because the group is a [forum](https://core.telegram.org/api/forum), disable forum functionality to continue.

  - [channels.convertToGigagroup](https://core.telegram.org/method/channels.convertToGigagroup)
  - [channels.togglePreHistoryHidden](https://core.telegram.org/method/channels.togglePreHistoryHidden)

- FRESH_CHANGE_ADMINS_FORBIDDEN : You were just elected admin, you can't add or modify other admins yet.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- FROM_MESSAGE_BOT_DISABLED : Bots can't use fromMessage min constructors.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [users.getUsers](https://core.telegram.org/method/users.getUsers)

- FROM_PEER_INVALID : The specified from_id is invalid.

  - [messages.search](https://core.telegram.org/method/messages.search)

- GAME_BOT_INVALID : Bots can't send another bot's game.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- GENERAL_MODIFY_ICON_FORBIDDEN : You can't modify the icon of the "General" topic.

  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)

- GEO_POINT_INVALID : Invalid geoposition provided.

  - [contacts.getLocated](https://core.telegram.org/method/contacts.getLocated)

- GIF_CONTENT_TYPE_INVALID : GIF content-type invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- GIF_ID_INVALID : The provided GIF ID is invalid.

  - [messages.saveGif](https://core.telegram.org/method/messages.saveGif)

- GIFT_SLUG_EXPIRED : The specified gift slug has expired.

  - [payments.applyGiftCode](https://core.telegram.org/method/payments.applyGiftCode)
  - [payments.checkGiftCode](https://core.telegram.org/method/payments.checkGiftCode)

- GIFT_SLUG_INVALID : The specified slug is invalid.

  - [payments.applyGiftCode](https://core.telegram.org/method/payments.applyGiftCode)
  - [payments.checkGiftCode](https://core.telegram.org/method/payments.checkGiftCode)

- GRAPH_EXPIRED_RELOAD : This graph has expired, please obtain a new graph token.

  - [stats.loadAsyncGraph](https://core.telegram.org/method/stats.loadAsyncGraph)

- GRAPH_INVALID_RELOAD : Invalid graph token provided, please reload the stats and provide the updated token.

  - [stats.loadAsyncGraph](https://core.telegram.org/method/stats.loadAsyncGraph)

- GRAPH_OUTDATED_RELOAD : The graph is outdated, please get a new async token using stats.getBroadcastStats.

  - [stats.loadAsyncGraph](https://core.telegram.org/method/stats.loadAsyncGraph)

- GROUPCALL_ALREADY_DISCARDED : The group call was already discarded.

  - [phone.createGroupCall](https://core.telegram.org/method/phone.createGroupCall)
  - [phone.discardGroupCall](https://core.telegram.org/method/phone.discardGroupCall)
  - [phone.discardGroupCallRequest](https://core.telegram.org/method/phone.discardGroupCallRequest)

- GROUPCALL_FORBIDDEN : The group call has already ended.

  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)

- GROUPCALL_INVALID : The specified group call is invalid.

  - [phone.checkGroupCall](https://core.telegram.org/method/phone.checkGroupCall)
  - [phone.discardGroupCall](https://core.telegram.org/method/phone.discardGroupCall)
  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)
  - [phone.editGroupCallTitle](https://core.telegram.org/method/phone.editGroupCallTitle)
  - [phone.exportGroupCallInvite](https://core.telegram.org/method/phone.exportGroupCallInvite)
  - [phone.getGroupCall](https://core.telegram.org/method/phone.getGroupCall)
  - [phone.getGroupCallStreamChannels](https://core.telegram.org/method/phone.getGroupCallStreamChannels)
  - [phone.getGroupParticipants](https://core.telegram.org/method/phone.getGroupParticipants)
  - [phone.inviteToGroupCall](https://core.telegram.org/method/phone.inviteToGroupCall)
  - [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall)
  - [phone.joinGroupCallPresentation](https://core.telegram.org/method/phone.joinGroupCallPresentation)
  - [phone.leaveGroupCall](https://core.telegram.org/method/phone.leaveGroupCall)
  - [phone.leaveGroupCallPresentation](https://core.telegram.org/method/phone.leaveGroupCallPresentation)
  - [phone.startScheduledGroupCall](https://core.telegram.org/method/phone.startScheduledGroupCall)
  - [phone.toggleGroupCallRecord](https://core.telegram.org/method/phone.toggleGroupCallRecord)
  - [phone.toggleGroupCallSettings](https://core.telegram.org/method/phone.toggleGroupCallSettings)
  - [phone.toggleGroupCallStartSubscription](https://core.telegram.org/method/phone.toggleGroupCallStartSubscription)

- GROUPCALL_JOIN_MISSING : You haven't joined this group call.

  - [phone.checkGroupCall](https://core.telegram.org/method/phone.checkGroupCall)
  - [phone.getGroupCallStreamChannels](https://core.telegram.org/method/phone.getGroupCallStreamChannels)

- GROUPCALL_NOT_MODIFIED : Group call settings weren't modified.

  - [phone.toggleGroupCallRecord](https://core.telegram.org/method/phone.toggleGroupCallRecord)
  - [phone.toggleGroupCallSettings](https://core.telegram.org/method/phone.toggleGroupCallSettings)

- GROUPCALL_SSRC_DUPLICATE_MUCH : The app needs to retry joining the group call with a new SSRC value.

  - [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall)

- GROUPED_MEDIA_INVALID : Invalid grouped media.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- HASH_INVALID : The provided hash is invalid.

  - [account.changeAuthorizationSettings](https://core.telegram.org/method/account.changeAuthorizationSettings)
  - [account.resetAuthorization](https://core.telegram.org/method/account.resetAuthorization)
  - [account.resetWebAuthorization](https://core.telegram.org/method/account.resetWebAuthorization)
  - [account.sendConfirmPhoneCode](https://core.telegram.org/method/account.sendConfirmPhoneCode)

- HASHTAG_INVALID : The specified hashtag is invalid.

  - [stories.searchPosts](https://core.telegram.org/method/stories.searchPosts)

- HIDE_REQUESTER_MISSING : The join request was missing or was already handled.

  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)

- ID_EXPIRED : The passed prepared inline message ID has expired.

  - [messages.getPreparedInlineMessage](https://core.telegram.org/method/messages.getPreparedInlineMessage)

- ID_INVALID : The passed ID is invalid.

  - [messages.getPreparedInlineMessage](https://core.telegram.org/method/messages.getPreparedInlineMessage)

- IMAGE_PROCESS_FAILED : Failure while processing image.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)
  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- IMPORT_FILE_INVALID : The specified chat export file is invalid.

  - [messages.initHistoryImport](https://core.telegram.org/method/messages.initHistoryImport)

- IMPORT_FORMAT_DATE_INVALID : The date specified in the import file is invalid.

  - [messages.initHistoryImport](https://core.telegram.org/method/messages.initHistoryImport)

- IMPORT_FORMAT_UNRECOGNIZED : The specified chat export file was exported from an unsupported chat app.

  - [messages.checkHistoryImport](https://core.telegram.org/method/messages.checkHistoryImport)
  - [messages.initHistoryImport](https://core.telegram.org/method/messages.initHistoryImport)

- IMPORT_ID_INVALID : The specified import ID is invalid.

  - [messages.startHistoryImport](https://core.telegram.org/method/messages.startHistoryImport)
  - [messages.uploadImportedMedia](https://core.telegram.org/method/messages.uploadImportedMedia)

- IMPORT_TOKEN_INVALID : The specified token is invalid.

  - [contacts.importContactToken](https://core.telegram.org/method/contacts.importContactToken)

- INLINE_RESULT_EXPIRED : The inline query expired.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- INPUT_CHATLIST_INVALID : The specified folder is invalid.

  - [chatlists.getChatlistUpdates](https://core.telegram.org/method/chatlists.getChatlistUpdates)

- INPUT_FILE_INVALID : The specified [InputFile](https://core.telegram.org/type/InputFile) is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- INPUT_FILTER_INVALID : The specified filter is invalid.

  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.searchGlobal](https://core.telegram.org/method/messages.searchGlobal)

- INPUT_PEERS_EMPTY : The specified peer array is empty.

  - [messages.getPeerDialogs](https://core.telegram.org/method/messages.getPeerDialogs)

- INPUT_TEXT_EMPTY : The specified text is empty.

  - [messages.translateText](https://core.telegram.org/method/messages.translateText)

- INPUT_TEXT_TOO_LONG : The specified text is too long.

  - [messages.translateText](https://core.telegram.org/method/messages.translateText)

- INPUT_USER_DEACTIVATED : The specified user was deleted.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.reportSpam](https://core.telegram.org/method/channels.reportSpam)
  - [contacts.block](https://core.telegram.org/method/contacts.block)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.createChat](https://core.telegram.org/method/messages.createChat)
  - [messages.deleteChatUser](https://core.telegram.org/method/messages.deleteChatUser)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.requestEncryption](https://core.telegram.org/method/messages.requestEncryption)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendScreenshotNotification](https://core.telegram.org/method/messages.sendScreenshotNotification)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)

- INVITE_FORBIDDEN_WITH_JOINAS : If the user has anonymously joined a group call as a channel, they can't invite other users to the group call because that would cause deanonymization, because the invite would be sent using the original user ID, not the anonymized channel ID.

  - [phone.inviteToGroupCall](https://core.telegram.org/method/phone.inviteToGroupCall)

- INVITE_HASH_EMPTY : The invite hash is empty.

  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.checkChatInvite](https://core.telegram.org/method/messages.checkChatInvite)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- INVITE_HASH_EXPIRED : The invite link has expired.

  - [channels.exportInvite](https://core.telegram.org/method/channels.exportInvite)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [invokeWithLayer](https://core.telegram.org/method/invokeWithLayer)
  - [messages.checkChatInvite](https://core.telegram.org/method/messages.checkChatInvite)
  - [messages.deleteExportedChatInvite](https://core.telegram.org/method/messages.deleteExportedChatInvite)
  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)
  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)
  - [messages.getExportedChatInvite](https://core.telegram.org/method/messages.getExportedChatInvite)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- INVITE_HASH_INVALID : The invite hash is invalid.

  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.checkChatInvite](https://core.telegram.org/method/messages.checkChatInvite)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- INVITE_REQUEST_SENT : You have successfully requested to join this chat or channel.

  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- INVITE_REVOKED_MISSING : The specified invite link was already revoked or is invalid.

  - [messages.deleteExportedChatInvite](https://core.telegram.org/method/messages.deleteExportedChatInvite)

- INVITE_SLUG_EMPTY : The specified invite slug is empty.

  - [chatlists.checkChatlistInvite](https://core.telegram.org/method/chatlists.checkChatlistInvite)
  - [chatlists.editExportedInvite](https://core.telegram.org/method/chatlists.editExportedInvite)
  - [chatlists.joinChatlistInvite](https://core.telegram.org/method/chatlists.joinChatlistInvite)

- INVITE_SLUG_EXPIRED : The specified chat folder link has expired.

  - [chatlists.checkChatlistInvite](https://core.telegram.org/method/chatlists.checkChatlistInvite)
  - [chatlists.deleteExportedInvite](https://core.telegram.org/method/chatlists.deleteExportedInvite)
  - [chatlists.editExportedInvite](https://core.telegram.org/method/chatlists.editExportedInvite)
  - [chatlists.joinChatlistInvite](https://core.telegram.org/method/chatlists.joinChatlistInvite)

- INVITE_SLUG_INVALID : The specified invitation slug is invalid.

  - [chatlists.deleteExportedInvite](https://core.telegram.org/method/chatlists.deleteExportedInvite)

- INVITES_TOO_MUCH : The maximum number of per-folder invites specified by the `chatlist_invites_limit_default`/`chatlist_invites_limit_premium` [client configuration parameters &raquo;](https://core.telegram.org/api/config#chatlist-invites-limit-default) was reached.

  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)

- INVOICE_PAYLOAD_INVALID : The specified invoice payload is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- JOIN_AS_PEER_INVALID : The specified peer cannot be used to join a group call.

  - [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall)
  - [phone.saveDefaultGroupCallJoinAs](https://core.telegram.org/method/phone.saveDefaultGroupCallJoinAs)

- LANG_CODE_INVALID : The specified language code is invalid.

  - [bots.getBotInfo](https://core.telegram.org/method/bots.getBotInfo)
  - [bots.resetBotCommands](https://core.telegram.org/method/bots.resetBotCommands)
  - [bots.setBotCommands](https://core.telegram.org/method/bots.setBotCommands)

- LANG_CODE_NOT_SUPPORTED : The specified language code is not supported.

  - [langpack.getLangPack](https://core.telegram.org/method/langpack.getLangPack)
  - [langpack.getLanguage](https://core.telegram.org/method/langpack.getLanguage)
  - [langpack.getStrings](https://core.telegram.org/method/langpack.getStrings)

- LANG_PACK_INVALID : The provided language pack is invalid.

  - [langpack.getDifference](https://core.telegram.org/method/langpack.getDifference)
  - [langpack.getLangPack](https://core.telegram.org/method/langpack.getLangPack)
  - [langpack.getLanguage](https://core.telegram.org/method/langpack.getLanguage)
  - [langpack.getLanguages](https://core.telegram.org/method/langpack.getLanguages)
  - [langpack.getStrings](https://core.telegram.org/method/langpack.getStrings)

- LANGUAGE_INVALID : The specified lang_code is invalid.

  - [langpack.getLangPack](https://core.telegram.org/method/langpack.getLangPack)

- LASTNAME_INVALID : The last name is invalid.

  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- LIMIT_INVALID : The provided limit is invalid.

  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- LINK_NOT_MODIFIED : Discussion link not modified.

  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)

- LOCATION_INVALID : The provided location is invalid.

  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)
  - [upload.getFileHashes](https://core.telegram.org/method/upload.getFileHashes)
  - [upload.getWebFile](https://core.telegram.org/method/upload.getWebFile)
  - [upload.reuploadCdnFile](https://core.telegram.org/method/upload.reuploadCdnFile)

- MAX_DATE_INVALID : The specified maximum date is invalid.

  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.readEncryptedHistory](https://core.telegram.org/method/messages.readEncryptedHistory)

- MAX_ID_INVALID : The provided max ID is invalid.

  - [photos.getUserPhotos](https://core.telegram.org/method/photos.getUserPhotos)
  - [stories.readStories](https://core.telegram.org/method/stories.readStories)

- MAX_QTS_INVALID : The specified max_qts is invalid.

  - [messages.receivedQueue](https://core.telegram.org/method/messages.receivedQueue)

- MD5_CHECKSUM_INVALID : The MD5 checksums do not match.

  - [messages.sendEncryptedFile](https://core.telegram.org/method/messages.sendEncryptedFile)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- MEDIA_CAPTION_TOO_LONG : The caption is too long.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- MEDIA_EMPTY : The provided media object is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getAttachedStickers](https://core.telegram.org/method/messages.getAttachedStickers)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- MEDIA_FILE_INVALID : The specified media file is invalid.

  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- MEDIA_GROUPED_INVALID : You tried to send media of different types in an album.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)

- MEDIA_INVALID : Media invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.uploadImportedMedia](https://core.telegram.org/method/messages.uploadImportedMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- MEDIA_NEW_INVALID : The new media is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)

- MEDIA_PREV_INVALID : Previous media invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)

- MEDIA_TTL_INVALID : The specified media TTL is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)

- MEDIA_TYPE_INVALID : The specified media type cannot be used in stories.

  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- MEDIA_VIDEO_STORY_MISSING : A non-story video cannot be repubblished as a story (emitted when trying to resend a non-story video as a story using inputDocument).

  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- MEGAGROUP_GEO_REQUIRED : This method can only be invoked on a geogroup.

  - [channels.editLocation](https://core.telegram.org/method/channels.editLocation)

- MEGAGROUP_ID_INVALID : Invalid supergroup ID.

  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)

- MEGAGROUP_PREHISTORY_HIDDEN : Group with hidden history for new members can't be set as discussion groups.

  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)

- MEGAGROUP_REQUIRED : You can only use this method on a supergroup.

  - [channels.editLocation](https://core.telegram.org/method/channels.editLocation)
  - [stats.getMegagroupStats](https://core.telegram.org/method/stats.getMegagroupStats)

- MESSAGE_EDIT_TIME_EXPIRED : You can't edit this message anymore, too much time has passed since its creation.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)

- MESSAGE_EMPTY : The provided message is empty.

  - [auth.sendInvites](https://core.telegram.org/method/auth.sendInvites)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.getWebPagePreview](https://core.telegram.org/method/messages.getWebPagePreview)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- MESSAGE_ID_INVALID : The provided message id is invalid.

  - [channels.exportMessageLink](https://core.telegram.org/method/channels.exportMessageLink)
  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.deleteMessages](https://core.telegram.org/method/messages.deleteMessages)
  - [messages.editInlineBotMessage](https://core.telegram.org/method/messages.editInlineBotMessage)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessage](https://core.telegram.org/method/messages.forwardMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [messages.getGameHighScores](https://core.telegram.org/method/messages.getGameHighScores)
  - [messages.getInlineGameHighScores](https://core.telegram.org/method/messages.getInlineGameHighScores)
  - [messages.getMessageEditData](https://core.telegram.org/method/messages.getMessageEditData)
  - [messages.getMessagesReadParticipants](https://core.telegram.org/method/messages.getMessagesReadParticipants)
  - [messages.getOutboxReadDate](https://core.telegram.org/method/messages.getOutboxReadDate)
  - [messages.getPollResults](https://core.telegram.org/method/messages.getPollResults)
  - [messages.sendBotRequestedPeer](https://core.telegram.org/method/messages.sendBotRequestedPeer)
  - [messages.sendPaidReaction](https://core.telegram.org/method/messages.sendPaidReaction)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.sendScheduledMessages](https://core.telegram.org/method/messages.sendScheduledMessages)
  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)
  - [messages.setGameScore](https://core.telegram.org/method/messages.setGameScore)
  - [messages.setInlineGameScore](https://core.telegram.org/method/messages.setInlineGameScore)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)
  - [payments.convertStarGift](https://core.telegram.org/method/payments.convertStarGift)
  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)
  - [payments.getPaymentReceipt](https://core.telegram.org/method/payments.getPaymentReceipt)
  - [payments.saveStarGift](https://core.telegram.org/method/payments.saveStarGift)
  - [payments.sendPaymentForm](https://core.telegram.org/method/payments.sendPaymentForm)
  - [payments.transferStarGift](https://core.telegram.org/method/payments.transferStarGift)
  - [payments.upgradeStarGift](https://core.telegram.org/method/payments.upgradeStarGift)
  - [payments.validateRequestedInfo](https://core.telegram.org/method/payments.validateRequestedInfo)
  - [stats.getMessagePublicForwards](https://core.telegram.org/method/stats.getMessagePublicForwards)
  - [stats.getMessageStats](https://core.telegram.org/method/stats.getMessageStats)

- MESSAGE_IDS_EMPTY : No message ids were provided.

  - [channels.getMessages](https://core.telegram.org/method/channels.getMessages)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- MESSAGE_NOT_MODIFIED : The provided message data is identical to the previous message data, the message wasn't modified.

  - [messages.editInlineBotMessage](https://core.telegram.org/method/messages.editInlineBotMessage)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)

- MESSAGE_NOT_READ_YET : The specified message wasn't read yet.

  - [messages.getOutboxReadDate](https://core.telegram.org/method/messages.getOutboxReadDate)

- MESSAGE_POLL_CLOSED : Poll closed.

  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)

- MESSAGE_TOO_LONG : The provided message is too long.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setBotCallbackAnswer](https://core.telegram.org/method/messages.setBotCallbackAnswer)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- MESSAGE_TOO_OLD : The message is too old, the requested information is not available.

  - [messages.getOutboxReadDate](https://core.telegram.org/method/messages.getOutboxReadDate)

- METHOD_INVALID : The specified method is invalid.

  - [bots.sendCustomRequest](https://core.telegram.org/method/bots.sendCustomRequest)
  - [messages.searchGifs](https://core.telegram.org/method/messages.searchGifs)

- MIN_DATE_INVALID : The specified minimum date is invalid.

  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)

- MSG_ID_INVALID : Invalid message ID provided.

  - [account.updateNotifySettings](https://core.telegram.org/method/account.updateNotifySettings)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.deleteMessages](https://core.telegram.org/method/channels.deleteMessages)
  - [channels.deleteParticipantHistory](https://core.telegram.org/method/channels.deleteParticipantHistory)
  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.exportMessageLink](https://core.telegram.org/method/channels.exportMessageLink)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.getChannels](https://core.telegram.org/method/channels.getChannels)
  - [channels.getFullChannel](https://core.telegram.org/method/channels.getFullChannel)
  - [channels.getMessages](https://core.telegram.org/method/channels.getMessages)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.getParticipants](https://core.telegram.org/method/channels.getParticipants)
  - [channels.getSponsoredMessages](https://core.telegram.org/method/channels.getSponsoredMessages)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [channels.readHistory](https://core.telegram.org/method/channels.readHistory)
  - [channels.readMessageContents](https://core.telegram.org/method/channels.readMessageContents)
  - [channels.reportSpam](https://core.telegram.org/method/channels.reportSpam)
  - [contacts.acceptContact](https://core.telegram.org/method/contacts.acceptContact)
  - [contacts.addContact](https://core.telegram.org/method/contacts.addContact)
  - [contacts.block](https://core.telegram.org/method/contacts.block)
  - [contacts.blockFromReplies](https://core.telegram.org/method/contacts.blockFromReplies)
  - [contacts.deleteContacts](https://core.telegram.org/method/contacts.deleteContacts)
  - [contacts.unblock](https://core.telegram.org/method/contacts.unblock)
  - [help.getConfig](https://core.telegram.org/method/help.getConfig)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getCommonChats](https://core.telegram.org/method/messages.getCommonChats)
  - [messages.getDiscussionMessage](https://core.telegram.org/method/messages.getDiscussionMessage)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)
  - [messages.getMessageReactionsList](https://core.telegram.org/method/messages.getMessageReactionsList)
  - [messages.getMessageReadParticipants](https://core.telegram.org/method/messages.getMessageReadParticipants)
  - [messages.getMessagesViews](https://core.telegram.org/method/messages.getMessagesViews)
  - [messages.getPeerDialogs](https://core.telegram.org/method/messages.getPeerDialogs)
  - [messages.getPeerSettings](https://core.telegram.org/method/messages.getPeerSettings)
  - [messages.getPollVotes](https://core.telegram.org/method/messages.getPollVotes)
  - [messages.getReplies](https://core.telegram.org/method/messages.getReplies)
  - [messages.getUnreadMentions](https://core.telegram.org/method/messages.getUnreadMentions)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)
  - [messages.readDiscussion](https://core.telegram.org/method/messages.readDiscussion)
  - [messages.readHistory](https://core.telegram.org/method/messages.readHistory)
  - [messages.readMentions](https://core.telegram.org/method/messages.readMentions)
  - [messages.reportReaction](https://core.telegram.org/method/messages.reportReaction)
  - [messages.reportSpam](https://core.telegram.org/method/messages.reportSpam)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.saveDraft](https://core.telegram.org/method/messages.saveDraft)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [messages.translateText](https://core.telegram.org/method/messages.translateText)
  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [photos.getUserPhotos](https://core.telegram.org/method/photos.getUserPhotos)
  - [stories.getPeerStories](https://core.telegram.org/method/stories.getPeerStories)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)
  - [upload.saveFilePart](https://core.telegram.org/method/upload.saveFilePart)
  - [users.getFullUser](https://core.telegram.org/method/users.getFullUser)
  - [users.getUsers](https://core.telegram.org/method/users.getUsers)

- MSG_TOO_OLD : [`chat_read_mark_expire_period` seconds](https://core.telegram.org/api/config#chat-read-mark-expire-period) have passed since the message was sent, read receipts were deleted.

  - [messages.getMessageReadParticipants](https://core.telegram.org/method/messages.getMessageReadParticipants)

- MSG_WAIT_FAILED : A waiting call returned an error.

  - [messages.readEncryptedHistory](https://core.telegram.org/method/messages.readEncryptedHistory)
  - [messages.receivedQueue](https://core.telegram.org/method/messages.receivedQueue)
  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedFile](https://core.telegram.org/method/messages.sendEncryptedFile)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)

- MULTI_MEDIA_TOO_LONG : Too many media files for album.

  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- NEW_SALT_INVALID : The new salt is invalid.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)

- NEW_SETTINGS_EMPTY : No password is set on the current account, and no new password was specified in `new_settings`.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)

- NEW_SETTINGS_INVALID : The new password settings are invalid.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)
  - [auth.recoverPassword](https://core.telegram.org/method/auth.recoverPassword)

- NEXT_OFFSET_INVALID : The specified offset is longer than 64 bytes.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- NOT_ELIGIBLE : The current user is not eligible to join the Peer-to-Peer Login Program.

  - [smsjobs.join](https://core.telegram.org/method/smsjobs.join)

- NOT_JOINED : The current user hasn't joined the Peer-to-Peer Login Program.

  - [smsjobs.getStatus](https://core.telegram.org/method/smsjobs.getStatus)
  - [smsjobs.leave](https://core.telegram.org/method/smsjobs.leave)
  - [smsjobs.updateSettings](https://core.telegram.org/method/smsjobs.updateSettings)

- OFFSET_INVALID : The provided offset is invalid.

  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- OFFSET_PEER_ID_INVALID : The provided offset peer is invalid.

  - [messages.getDialogs](https://core.telegram.org/method/messages.getDialogs)

- OPTION_INVALID : Invalid option selected.

  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)

- OPTIONS_TOO_MUCH : Too many options provided.

  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)

- ORDER_INVALID : The specified username order is invalid.

  - [account.reorderUsernames](https://core.telegram.org/method/account.reorderUsernames)

- PACK_SHORT_NAME_INVALID : Short pack name invalid.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- PACK_SHORT_NAME_OCCUPIED : A stickerpack with this name already exists.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- PACK_TITLE_INVALID : The stickerpack title is invalid.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- PARTICIPANT_ID_INVALID : The specified participant ID is invalid.

  - [channels.deleteParticipantHistory](https://core.telegram.org/method/channels.deleteParticipantHistory)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)

- PARTICIPANT_JOIN_MISSING : Trying to enable a presentation, when the user hasn't joined the Video Chat with [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall).

  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)
  - [phone.joinGroupCallPresentation](https://core.telegram.org/method/phone.joinGroupCallPresentation)

- PARTICIPANT_VERSION_OUTDATED : The other participant does not use an up to date telegram client with support for calls.

  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)

- PARTICIPANTS_TOO_FEW : Not enough participants.

  - [channels.convertToGigagroup](https://core.telegram.org/method/channels.convertToGigagroup)
  - [channels.setStickers](https://core.telegram.org/method/channels.setStickers)
  - [channels.toggleParticipantsHidden](https://core.telegram.org/method/channels.toggleParticipantsHidden)

- PASSWORD_EMPTY : The provided password is empty.

  - [account.resetPassword](https://core.telegram.org/method/account.resetPassword)
  - [auth.requestPasswordRecovery](https://core.telegram.org/method/auth.requestPasswordRecovery)

- PASSWORD_HASH_INVALID : The provided password hash is invalid.

  - [account.deleteAccount](https://core.telegram.org/method/account.deleteAccount)
  - [account.getPasswordSettings](https://core.telegram.org/method/account.getPasswordSettings)
  - [account.getTmpPassword](https://core.telegram.org/method/account.getTmpPassword)
  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)
  - [auth.checkPassword](https://core.telegram.org/method/auth.checkPassword)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [payments.getStarsRevenueWithdrawalUrl](https://core.telegram.org/method/payments.getStarsRevenueWithdrawalUrl)
  - [stats.getBroadcastRevenueWithdrawalUrl](https://core.telegram.org/method/stats.getBroadcastRevenueWithdrawalUrl)

- PASSWORD_MISSING : You must [enable 2FA](https://core.telegram.org/api/srp) before executing this operation.

  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [payments.getStarsRevenueWithdrawalUrl](https://core.telegram.org/method/payments.getStarsRevenueWithdrawalUrl)
  - [stats.getBroadcastRevenueWithdrawalUrl](https://core.telegram.org/method/stats.getBroadcastRevenueWithdrawalUrl)

- PASSWORD_RECOVERY_EXPIRED : The recovery code has expired.

  - [auth.checkRecoveryPassword](https://core.telegram.org/method/auth.checkRecoveryPassword)

- PASSWORD_RECOVERY_NA : No email was set, can't recover password via email.

  - [auth.requestPasswordRecovery](https://core.telegram.org/method/auth.requestPasswordRecovery)

- PASSWORD_REQUIRED : A [2FA password](https://core.telegram.org/api/srp) must be configured to use Telegram Passport.

  - [account.saveSecureValue](https://core.telegram.org/method/account.saveSecureValue)

- PASSWORD_TOO_FRESH_%d : The password was modified less than 24 hours ago, try again in %d seconds.

  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [payments.getStarsRevenueWithdrawalUrl](https://core.telegram.org/method/payments.getStarsRevenueWithdrawalUrl)
  - [stats.getBroadcastRevenueWithdrawalUrl](https://core.telegram.org/method/stats.getBroadcastRevenueWithdrawalUrl)

- PAYMENT_PROVIDER_INVALID : The specified payment provider is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- PEER_HISTORY_EMPTY : You can't pin an empty chat with a user.

  - [messages.toggleDialogPin](https://core.telegram.org/method/messages.toggleDialogPin)

- PEER_ID_INVALID : The provided peer id is invalid.

  - [account.disablePeerConnectedBot](https://core.telegram.org/method/account.disablePeerConnectedBot)
  - [account.getNotifySettings](https://core.telegram.org/method/account.getNotifySettings)
  - [account.reportPeer](https://core.telegram.org/method/account.reportPeer)
  - [account.reportProfilePhoto](https://core.telegram.org/method/account.reportProfilePhoto)
  - [account.saveAutoSaveSettings](https://core.telegram.org/method/account.saveAutoSaveSettings)
  - [account.toggleConnectedBotPaused](https://core.telegram.org/method/account.toggleConnectedBotPaused)
  - [account.updateNotifySettings](https://core.telegram.org/method/account.updateNotifySettings)
  - [bots.setBotCommands](https://core.telegram.org/method/bots.setBotCommands)
  - [bots.setCustomVerification](https://core.telegram.org/method/bots.setCustomVerification)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.getSendAs](https://core.telegram.org/method/channels.getSendAs)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [contacts.block](https://core.telegram.org/method/contacts.block)
  - [contacts.resetTopPeerRating](https://core.telegram.org/method/contacts.resetTopPeerRating)
  - [contacts.unblock](https://core.telegram.org/method/contacts.unblock)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.checkHistoryImportPeer](https://core.telegram.org/method/messages.checkHistoryImportPeer)
  - [messages.deleteChat](https://core.telegram.org/method/messages.deleteChat)
  - [messages.deleteChatUser](https://core.telegram.org/method/messages.deleteChatUser)
  - [messages.deleteExportedChatInvite](https://core.telegram.org/method/messages.deleteExportedChatInvite)
  - [messages.deleteFactCheck](https://core.telegram.org/method/messages.deleteFactCheck)
  - [messages.deleteHistory](https://core.telegram.org/method/messages.deleteHistory)
  - [messages.deleteRevokedExportedChatInvites](https://core.telegram.org/method/messages.deleteRevokedExportedChatInvites)
  - [messages.deleteSavedHistory](https://core.telegram.org/method/messages.deleteSavedHistory)
  - [messages.deleteScheduledMessages](https://core.telegram.org/method/messages.deleteScheduledMessages)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editChatAdmin](https://core.telegram.org/method/messages.editChatAdmin)
  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [messages.editChatTitle](https://core.telegram.org/method/messages.editChatTitle)
  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)
  - [messages.editFactCheck](https://core.telegram.org/method/messages.editFactCheck)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessage](https://core.telegram.org/method/messages.forwardMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getAdminsWithInvites](https://core.telegram.org/method/messages.getAdminsWithInvites)
  - [messages.getBotCallbackAnswer](https://core.telegram.org/method/messages.getBotCallbackAnswer)
  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)
  - [messages.getChats](https://core.telegram.org/method/messages.getChats)
  - [messages.getDiscussionMessage](https://core.telegram.org/method/messages.getDiscussionMessage)
  - [messages.getExportedChatInvite](https://core.telegram.org/method/messages.getExportedChatInvite)
  - [messages.getExportedChatInvites](https://core.telegram.org/method/messages.getExportedChatInvites)
  - [messages.getFactCheck](https://core.telegram.org/method/messages.getFactCheck)
  - [messages.getFullChat](https://core.telegram.org/method/messages.getFullChat)
  - [messages.getGameHighScores](https://core.telegram.org/method/messages.getGameHighScores)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)
  - [messages.getMessageEditData](https://core.telegram.org/method/messages.getMessageEditData)
  - [messages.getMessageReadParticipants](https://core.telegram.org/method/messages.getMessageReadParticipants)
  - [messages.getMessagesViews](https://core.telegram.org/method/messages.getMessagesViews)
  - [messages.getOnlines](https://core.telegram.org/method/messages.getOnlines)
  - [messages.getOutboxReadDate](https://core.telegram.org/method/messages.getOutboxReadDate)
  - [messages.getPeerDialogs](https://core.telegram.org/method/messages.getPeerDialogs)
  - [messages.getPeerSettings](https://core.telegram.org/method/messages.getPeerSettings)
  - [messages.getPollResults](https://core.telegram.org/method/messages.getPollResults)
  - [messages.getReplies](https://core.telegram.org/method/messages.getReplies)
  - [messages.getSavedHistory](https://core.telegram.org/method/messages.getSavedHistory)
  - [messages.getScheduledHistory](https://core.telegram.org/method/messages.getScheduledHistory)
  - [messages.getScheduledMessages](https://core.telegram.org/method/messages.getScheduledMessages)
  - [messages.getSearchCounters](https://core.telegram.org/method/messages.getSearchCounters)
  - [messages.getSearchResultsCalendar](https://core.telegram.org/method/messages.getSearchResultsCalendar)
  - [messages.getSearchResultsPositions](https://core.telegram.org/method/messages.getSearchResultsPositions)
  - [messages.getStatsURL](https://core.telegram.org/method/messages.getStatsURL)
  - [messages.getUnreadMentions](https://core.telegram.org/method/messages.getUnreadMentions)
  - [messages.getUnreadReactions](https://core.telegram.org/method/messages.getUnreadReactions)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.hidePeerSettingsBar](https://core.telegram.org/method/messages.hidePeerSettingsBar)
  - [messages.hideReportSpam](https://core.telegram.org/method/messages.hideReportSpam)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)
  - [messages.initHistoryImport](https://core.telegram.org/method/messages.initHistoryImport)
  - [messages.markDialogUnread](https://core.telegram.org/method/messages.markDialogUnread)
  - [messages.migrateChat](https://core.telegram.org/method/messages.migrateChat)
  - [messages.readDiscussion](https://core.telegram.org/method/messages.readDiscussion)
  - [messages.readHistory](https://core.telegram.org/method/messages.readHistory)
  - [messages.readMentions](https://core.telegram.org/method/messages.readMentions)
  - [messages.readReactions](https://core.telegram.org/method/messages.readReactions)
  - [messages.reorderPinnedDialogs](https://core.telegram.org/method/messages.reorderPinnedDialogs)
  - [messages.report](https://core.telegram.org/method/messages.report)
  - [messages.reportMessagesDelivery](https://core.telegram.org/method/messages.reportMessagesDelivery)
  - [messages.reportReaction](https://core.telegram.org/method/messages.reportReaction)
  - [messages.reportSpam](https://core.telegram.org/method/messages.reportSpam)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.saveDefaultSendAs](https://core.telegram.org/method/messages.saveDefaultSendAs)
  - [messages.saveDraft](https://core.telegram.org/method/messages.saveDraft)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendBotRequestedPeer](https://core.telegram.org/method/messages.sendBotRequestedPeer)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendQuickReplyMessages](https://core.telegram.org/method/messages.sendQuickReplyMessages)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.sendScheduledMessages](https://core.telegram.org/method/messages.sendScheduledMessages)
  - [messages.sendScreenshotNotification](https://core.telegram.org/method/messages.sendScreenshotNotification)
  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)
  - [messages.setChatAvailableReactions](https://core.telegram.org/method/messages.setChatAvailableReactions)
  - [messages.setChatTheme](https://core.telegram.org/method/messages.setChatTheme)
  - [messages.setChatWallPaper](https://core.telegram.org/method/messages.setChatWallPaper)
  - [messages.setGameScore](https://core.telegram.org/method/messages.setGameScore)
  - [messages.setHistoryTTL](https://core.telegram.org/method/messages.setHistoryTTL)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [messages.toggleDialogPin](https://core.telegram.org/method/messages.toggleDialogPin)
  - [messages.toggleNoForwards](https://core.telegram.org/method/messages.toggleNoForwards)
  - [messages.togglePaidReactionPrivacy](https://core.telegram.org/method/messages.togglePaidReactionPrivacy)
  - [messages.togglePeerTranslations](https://core.telegram.org/method/messages.togglePeerTranslations)
  - [messages.toggleSavedDialogPin](https://core.telegram.org/method/messages.toggleSavedDialogPin)
  - [messages.transcribeAudio](https://core.telegram.org/method/messages.transcribeAudio)
  - [messages.translateText](https://core.telegram.org/method/messages.translateText)
  - [messages.unpinAllMessages](https://core.telegram.org/method/messages.unpinAllMessages)
  - [messages.updateDialogFilter](https://core.telegram.org/method/messages.updateDialogFilter)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [payments.changeStarsSubscription](https://core.telegram.org/method/payments.changeStarsSubscription)
  - [payments.fulfillStarsSubscription](https://core.telegram.org/method/payments.fulfillStarsSubscription)
  - [payments.getConnectedStarRefBot](https://core.telegram.org/method/payments.getConnectedStarRefBot)
  - [payments.getGiveawayInfo](https://core.telegram.org/method/payments.getGiveawayInfo)
  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)
  - [payments.getStarsRevenueAdsAccountUrl](https://core.telegram.org/method/payments.getStarsRevenueAdsAccountUrl)
  - [payments.getStarsRevenueStats](https://core.telegram.org/method/payments.getStarsRevenueStats)
  - [payments.getStarsStatus](https://core.telegram.org/method/payments.getStarsStatus)
  - [payments.getStarsSubscriptions](https://core.telegram.org/method/payments.getStarsSubscriptions)
  - [payments.getStarsTransactions](https://core.telegram.org/method/payments.getStarsTransactions)
  - [payments.getStarsTransactionsByID](https://core.telegram.org/method/payments.getStarsTransactionsByID)
  - [payments.launchPrepaidGiveaway](https://core.telegram.org/method/payments.launchPrepaidGiveaway)
  - [payments.sendPaymentForm](https://core.telegram.org/method/payments.sendPaymentForm)
  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)
  - [payments.validateRequestedInfo](https://core.telegram.org/method/payments.validateRequestedInfo)
  - [phone.createGroupCall](https://core.telegram.org/method/phone.createGroupCall)
  - [phone.getGroupCallJoinAs](https://core.telegram.org/method/phone.getGroupCallJoinAs)
  - [phone.getGroupCallStreamRtmpUrl](https://core.telegram.org/method/phone.getGroupCallStreamRtmpUrl)
  - [phone.saveDefaultGroupCallJoinAs](https://core.telegram.org/method/phone.saveDefaultGroupCallJoinAs)
  - [premium.applyBoost](https://core.telegram.org/method/premium.applyBoost)
  - [premium.getBoostsList](https://core.telegram.org/method/premium.getBoostsList)
  - [premium.getBoostsStatus](https://core.telegram.org/method/premium.getBoostsStatus)
  - [premium.getUserBoosts](https://core.telegram.org/method/premium.getUserBoosts)
  - [stats.getBroadcastRevenueStats](https://core.telegram.org/method/stats.getBroadcastRevenueStats)
  - [stats.getBroadcastRevenueTransactions](https://core.telegram.org/method/stats.getBroadcastRevenueTransactions)
  - [stats.getMessagePublicForwards](https://core.telegram.org/method/stats.getMessagePublicForwards)
  - [stats.getMessageStats](https://core.telegram.org/method/stats.getMessageStats)
  - [stats.getStoryPublicForwards](https://core.telegram.org/method/stats.getStoryPublicForwards)
  - [stats.getStoryStats](https://core.telegram.org/method/stats.getStoryStats)
  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)
  - [stories.applyBoost](https://core.telegram.org/method/stories.applyBoost)
  - [stories.canApplyBoost](https://core.telegram.org/method/stories.canApplyBoost)
  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)
  - [stories.deleteStories](https://core.telegram.org/method/stories.deleteStories)
  - [stories.editStory](https://core.telegram.org/method/stories.editStory)
  - [stories.exportStoryLink](https://core.telegram.org/method/stories.exportStoryLink)
  - [stories.getBoostersList](https://core.telegram.org/method/stories.getBoostersList)
  - [stories.getBoostsStatus](https://core.telegram.org/method/stories.getBoostsStatus)
  - [stories.getPeerStories](https://core.telegram.org/method/stories.getPeerStories)
  - [stories.getPinnedStories](https://core.telegram.org/method/stories.getPinnedStories)
  - [stories.getStoriesArchive](https://core.telegram.org/method/stories.getStoriesArchive)
  - [stories.getStoriesByID](https://core.telegram.org/method/stories.getStoriesByID)
  - [stories.getStoriesViews](https://core.telegram.org/method/stories.getStoriesViews)
  - [stories.getStoryReactionsList](https://core.telegram.org/method/stories.getStoryReactionsList)
  - [stories.getStoryViewsList](https://core.telegram.org/method/stories.getStoryViewsList)
  - [stories.incrementStoryViews](https://core.telegram.org/method/stories.incrementStoryViews)
  - [stories.readStories](https://core.telegram.org/method/stories.readStories)
  - [stories.report](https://core.telegram.org/method/stories.report)
  - [stories.sendReaction](https://core.telegram.org/method/stories.sendReaction)
  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)
  - [stories.togglePeerStoriesHidden](https://core.telegram.org/method/stories.togglePeerStoriesHidden)
  - [stories.togglePinned](https://core.telegram.org/method/stories.togglePinned)
  - [stories.togglePinnedToTop](https://core.telegram.org/method/stories.togglePinnedToTop)
  - [upload.getFile](https://core.telegram.org/method/upload.getFile)
  - [users.getUsers](https://core.telegram.org/method/users.getUsers)

- PEER_ID_NOT_SUPPORTED : The provided peer ID is not supported.

  - [messages.search](https://core.telegram.org/method/messages.search)

- PEER_TYPES_INVALID : The passed [keyboardButtonSwitchInline](https://core.telegram.org/constructor/keyboardButtonSwitchInline).`peer_types` field is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- PEERS_LIST_EMPTY : The specified list of peers is empty.

  - [chatlists.editExportedInvite](https://core.telegram.org/method/chatlists.editExportedInvite)
  - [chatlists.exportChatlistInvite](https://core.telegram.org/method/chatlists.exportChatlistInvite)

- PERSISTENT_TIMESTAMP_EMPTY : Persistent timestamp empty.

  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- PERSISTENT_TIMESTAMP_INVALID : Persistent timestamp invalid.

  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- PHONE_CODE_EMPTY : phone_code is missing.

  - [account.changePhone](https://core.telegram.org/method/account.changePhone)
  - [account.confirmPhone](https://core.telegram.org/method/account.confirmPhone)
  - [account.verifyPhone](https://core.telegram.org/method/account.verifyPhone)
  - [auth.requestFirebaseSms](https://core.telegram.org/method/auth.requestFirebaseSms)
  - [auth.resendCode](https://core.telegram.org/method/auth.resendCode)
  - [auth.signIn](https://core.telegram.org/method/auth.signIn)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_CODE_EXPIRED : The phone code you provided has expired.

  - [account.changePhone](https://core.telegram.org/method/account.changePhone)
  - [account.verifyPhone](https://core.telegram.org/method/account.verifyPhone)
  - [auth.cancelCode](https://core.telegram.org/method/auth.cancelCode)
  - [auth.resendCode](https://core.telegram.org/method/auth.resendCode)
  - [auth.signIn](https://core.telegram.org/method/auth.signIn)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_CODE_HASH_EMPTY : phone_code_hash is missing.

  - [auth.resendCode](https://core.telegram.org/method/auth.resendCode)

- PHONE_CODE_INVALID : The provided phone code is invalid.

  - [auth.signIn](https://core.telegram.org/method/auth.signIn)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_HASH_EXPIRED : An invalid or expired `phone_code_hash` was provided.

  - [account.sendVerifyEmailCode](https://core.telegram.org/method/account.sendVerifyEmailCode)

- PHONE_NOT_OCCUPIED : No user is associated to the specified phone number.

  - [contacts.resolvePhone](https://core.telegram.org/method/contacts.resolvePhone)

- PHONE_NUMBER_APP_SIGNUP_FORBIDDEN : You can't sign up using this app.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- PHONE_NUMBER_BANNED : The provided phone number is banned from telegram.

  - [account.sendChangePhoneCode](https://core.telegram.org/method/account.sendChangePhoneCode)
  - [auth.checkPhone](https://core.telegram.org/method/auth.checkPhone)
  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- PHONE_NUMBER_FLOOD : You asked for the code too many times.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_NUMBER_INVALID : The phone number is invalid.

  - [account.changePhone](https://core.telegram.org/method/account.changePhone)
  - [account.sendChangePhoneCode](https://core.telegram.org/method/account.sendChangePhoneCode)
  - [account.sendVerifyEmailCode](https://core.telegram.org/method/account.sendVerifyEmailCode)
  - [account.sendVerifyPhoneCode](https://core.telegram.org/method/account.sendVerifyPhoneCode)
  - [account.verifyEmail](https://core.telegram.org/method/account.verifyEmail)
  - [account.verifyPhone](https://core.telegram.org/method/account.verifyPhone)
  - [auth.cancelCode](https://core.telegram.org/method/auth.cancelCode)
  - [auth.checkPhone](https://core.telegram.org/method/auth.checkPhone)
  - [auth.reportMissingCode](https://core.telegram.org/method/auth.reportMissingCode)
  - [auth.requestFirebaseSms](https://core.telegram.org/method/auth.requestFirebaseSms)
  - [auth.resendCode](https://core.telegram.org/method/auth.resendCode)
  - [auth.resetLoginEmail](https://core.telegram.org/method/auth.resetLoginEmail)
  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)
  - [auth.signIn](https://core.telegram.org/method/auth.signIn)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_NUMBER_OCCUPIED : The phone number is already in use.

  - [account.changePhone](https://core.telegram.org/method/account.changePhone)
  - [account.sendChangePhoneCode](https://core.telegram.org/method/account.sendChangePhoneCode)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_NUMBER_UNOCCUPIED : The phone number is not yet being used.

  - [auth.signIn](https://core.telegram.org/method/auth.signIn)

- PHONE_PASSWORD_PROTECTED : This phone is password protected.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- PHOTO_CONTENT_TYPE_INVALID : Photo mime-type invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- PHOTO_CONTENT_URL_EMPTY : Photo URL invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- PHOTO_CROP_FILE_MISSING : Photo crop file missing.

  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- PHOTO_CROP_SIZE_SMALL : Photo is too small.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- PHOTO_EXT_INVALID : The extension of the photo is invalid.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- PHOTO_FILE_MISSING : Profile photo file missing.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- PHOTO_ID_INVALID : Photo ID invalid.

  - [photos.updateProfilePhoto](https://core.telegram.org/method/photos.updateProfilePhoto)

- PHOTO_INVALID : Photo invalid.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [messages.editChatPhoto](https://core.telegram.org/method/messages.editChatPhoto)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- PHOTO_INVALID_DIMENSIONS : The photo dimensions are invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- PHOTO_SAVE_FILE_INVALID : Internal issues, try again later.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- PHOTO_THUMB_URL_EMPTY : Photo thumbnail URL is empty.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- PIN_RESTRICTED : You can't pin messages.

  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)

- PINNED_DIALOGS_TOO_MUCH : Too many pinned dialogs.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.toggleDialogPin](https://core.telegram.org/method/messages.toggleDialogPin)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)

- POLL_ANSWER_INVALID : One of the poll answers is not acceptable.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- POLL_ANSWERS_INVALID : Invalid poll answers were provided.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- POLL_OPTION_DUPLICATE : Duplicate poll options provided.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- POLL_OPTION_INVALID : Invalid poll option provided.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- POLL_QUESTION_INVALID : One of the poll questions is not acceptable.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- PREMIUM_ACCOUNT_REQUIRED : A premium account is required to execute this action.

  - [channels.reportSponsoredMessage](https://core.telegram.org/method/channels.reportSponsoredMessage)
  - [stories.activateStealthMode](https://core.telegram.org/method/stories.activateStealthMode)
  - [stories.applyBoost](https://core.telegram.org/method/stories.applyBoost)
  - [stories.canApplyBoost](https://core.telegram.org/method/stories.canApplyBoost)
  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)
  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- PRICING_CHAT_INVALID : The pricing for the [subscription](https://core.telegram.org/api/subscriptions) is invalid, the maximum price is specified in the [`stars_subscription_amount_max` config key &raquo;](https://core.telegram.org/api/config#stars-subscription-amount-max).

  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)

- PRIVACY_KEY_INVALID : The privacy key is invalid.

  - [account.getPrivacy](https://core.telegram.org/method/account.getPrivacy)
  - [account.setPrivacy](https://core.telegram.org/method/account.setPrivacy)

- PRIVACY_TOO_LONG : Too many privacy rules were specified, the current limit is 1000.

  - [account.setPrivacy](https://core.telegram.org/method/account.setPrivacy)

- PRIVACY_VALUE_INVALID : The specified privacy rule combination is invalid.

  - [account.setPrivacy](https://core.telegram.org/method/account.setPrivacy)

- PUBLIC_KEY_REQUIRED : A public key is required.

  - [account.acceptAuthorization](https://core.telegram.org/method/account.acceptAuthorization)
  - [account.getAuthorizationForm](https://core.telegram.org/method/account.getAuthorizationForm)

- QUERY_ID_EMPTY : The query ID is empty.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- QUERY_ID_INVALID : The query ID is invalid.

  - [bots.answerWebhookJSONQuery](https://core.telegram.org/method/bots.answerWebhookJSONQuery)
  - [messages.sendWebViewResultMessage](https://core.telegram.org/method/messages.sendWebViewResultMessage)
  - [messages.setBotCallbackAnswer](https://core.telegram.org/method/messages.setBotCallbackAnswer)
  - [messages.setBotShippingResults](https://core.telegram.org/method/messages.setBotShippingResults)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- QUERY_TOO_SHORT : The query string is too short.

  - [contacts.search](https://core.telegram.org/method/contacts.search)

- QUICK_REPLIES_TOO_MUCH : A maximum of [appConfig.`quick_replies_limit`](https://core.telegram.org/api/config#quick-replies-limit) shortcuts may be created, the limit was reached.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- QUIZ_ANSWER_MISSING : You can forward a quiz while hiding the original author only after choosing an option in the quiz.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- QUIZ_CORRECT_ANSWER_INVALID : An invalid value was provided to the correct_answers field.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- QUIZ_CORRECT_ANSWERS_EMPTY : No correct quiz answer was specified.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- QUIZ_CORRECT_ANSWERS_TOO_MUCH : You specified too many correct answers in a quiz, quizzes can only have one right answer!

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- QUIZ_MULTIPLE_INVALID : Quizzes can't have the multiple_choice flag set!

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- QUOTE_TEXT_INVALID : The specified `reply_to`.`quote_text` field is invalid.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- RAISE_HAND_FORBIDDEN : You cannot raise your hand.

  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)

- RANDOM_ID_EMPTY : Random ID empty.

  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- RANDOM_ID_INVALID : A provided random ID is invalid.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- RANDOM_LENGTH_INVALID : Random length invalid.

  - [messages.getDhConfig](https://core.telegram.org/method/messages.getDhConfig)

- RANGES_INVALID : Invalid range provided.

  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)

- REACTION_EMPTY : Empty reaction provided.

  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)

- REACTION_INVALID : The specified reaction is invalid.

  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.setDefaultReaction](https://core.telegram.org/method/messages.setDefaultReaction)
  - [messages.updateSavedReactionTag](https://core.telegram.org/method/messages.updateSavedReactionTag)
  - [stories.sendReaction](https://core.telegram.org/method/stories.sendReaction)

- REACTIONS_TOO_MANY : The message already has exactly `reactions_uniq_max` reaction emojis, you can't react with a new emoji, see [the docs for more info &raquo;](https://core.telegram.org/api/config#client-configuration).

  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)

- RECEIPT_EMPTY : The specified receipt is empty.

  - [payments.assignAppStoreTransaction](https://core.telegram.org/method/payments.assignAppStoreTransaction)

- REPLY_MARKUP_BUY_EMPTY : Reply markup for buy button empty.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- REPLY_MARKUP_GAME_EMPTY : A game message is being edited, but the newly provided keyboard doesn't have a keyboardButtonGame button.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- REPLY_MARKUP_INVALID : The provided reply markup is invalid.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- REPLY_MARKUP_TOO_LONG : The specified reply_markup is too long.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- REPLY_MESSAGE_ID_INVALID : The specified reply-to message ID is invalid.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendScreenshotNotification](https://core.telegram.org/method/messages.sendScreenshotNotification)

- REPLY_MESSAGES_TOO_MUCH : Each shortcut can contain a maximum of [appConfig.`quick_reply_messages_limit`](https://core.telegram.org/api/config#quick-reply-messages-limit) messages, the limit was reached.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- REPLY_TO_INVALID : The specified `reply_to` field is invalid.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- REPLY_TO_USER_INVALID : The replied-to user is invalid.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- REQUEST_TOKEN_INVALID : The master DC did not accept the `request_token` from the CDN DC. Continue downloading the file from the master DC using upload.getFile.

  - [upload.reuploadCdnFile](https://core.telegram.org/method/upload.reuploadCdnFile)

- RESET_REQUEST_MISSING : No password reset is in progress.

  - [account.declinePasswordReset](https://core.telegram.org/method/account.declinePasswordReset)

- RESULT_ID_DUPLICATE : You provided a duplicate result ID.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- RESULT_ID_EMPTY : Result ID empty.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- RESULT_ID_INVALID : One of the specified result IDs is invalid.

  - [messages.savePreparedInlineMessage](https://core.telegram.org/method/messages.savePreparedInlineMessage)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- RESULT_TYPE_INVALID : Result type invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- RESULTS_TOO_MUCH : Too many results were provided.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- REVOTE_NOT_ALLOWED : You cannot change your vote.

  - [messages.sendVote](https://core.telegram.org/method/messages.sendVote)

- RIGHTS_NOT_MODIFIED : The new admin rights are equal to the old rights, no change was made.

  - [bots.setBotBroadcastDefaultAdminRights](https://core.telegram.org/method/bots.setBotBroadcastDefaultAdminRights)
  - [bots.setBotGroupDefaultAdminRights](https://core.telegram.org/method/bots.setBotGroupDefaultAdminRights)

- RINGTONE_INVALID : The specified ringtone is invalid.

  - [account.saveRingtone](https://core.telegram.org/method/account.saveRingtone)

- RINGTONE_MIME_INVALID : The MIME type for the ringtone is invalid.

  - [account.uploadRingtone](https://core.telegram.org/method/account.uploadRingtone)

- RSA_DECRYPT_FAILED : Internal RSA decryption failed.

  - [upload.getCdnFileHashes](https://core.telegram.org/method/upload.getCdnFileHashes)
  - [upload.reuploadCdnFile](https://core.telegram.org/method/upload.reuploadCdnFile)

- SCHEDULE_BOT_NOT_ALLOWED : Bots cannot schedule messages.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- SCHEDULE_DATE_INVALID : Invalid schedule date provided.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [phone.createGroupCall](https://core.telegram.org/method/phone.createGroupCall)

- SCHEDULE_DATE_TOO_LATE : You can't schedule a message this far in the future.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- SCHEDULE_STATUS_PRIVATE : Can't schedule until user is online, if the user's last seen timestamp is hidden by their privacy settings.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- SCHEDULE_TOO_MUCH : There are too many scheduled messages.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- SCORE_INVALID : The specified game score is invalid.

  - [messages.setGameScore](https://core.telegram.org/method/messages.setGameScore)

- SEARCH_QUERY_EMPTY : The search query is empty.

  - [contacts.search](https://core.telegram.org/method/contacts.search)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.searchGifs](https://core.telegram.org/method/messages.searchGifs)
  - [messages.searchGlobal](https://core.telegram.org/method/messages.searchGlobal)

- SEARCH_WITH_LINK_NOT_SUPPORTED : You cannot provide a search query and an invite link at the same time.

  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)

- SECONDS_INVALID : Invalid duration provided.

  - [channels.toggleSlowMode](https://core.telegram.org/method/channels.toggleSlowMode)

- SECURE_SECRET_REQUIRED : A secure secret is required.

  - [account.saveSecureValue](https://core.telegram.org/method/account.saveSecureValue)

- SEND_AS_PEER_INVALID : You can't send messages as the specified peer.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.saveDefaultSendAs](https://core.telegram.org/method/messages.saveDefaultSendAs)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- SEND_MESSAGE_MEDIA_INVALID : Invalid media provided.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- SEND_MESSAGE_TYPE_INVALID : The message type is invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- SESSION_TOO_FRESH_%d : This session was created less than 24 hours ago, try again in %d seconds.

  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [payments.getStarsRevenueWithdrawalUrl](https://core.telegram.org/method/payments.getStarsRevenueWithdrawalUrl)
  - [stats.getBroadcastRevenueWithdrawalUrl](https://core.telegram.org/method/stats.getBroadcastRevenueWithdrawalUrl)

- SETTINGS_INVALID : Invalid settings were provided.

  - [account.updateNotifySettings](https://core.telegram.org/method/account.updateNotifySettings)

- SHA256_HASH_INVALID : The provided SHA256 hash is invalid.

  - [messages.getDocumentByHash](https://core.telegram.org/method/messages.getDocumentByHash)

- SHORT_NAME_INVALID : The specified short name is invalid.

  - [stickers.checkShortName](https://core.telegram.org/method/stickers.checkShortName)

- SHORT_NAME_OCCUPIED : The specified short name is already in use.

  - [stickers.checkShortName](https://core.telegram.org/method/stickers.checkShortName)

- SHORTCUT_INVALID : The specified shortcut is invalid.

  - [messages.deleteQuickReplyMessages](https://core.telegram.org/method/messages.deleteQuickReplyMessages)
  - [messages.deleteQuickReplyShortcut](https://core.telegram.org/method/messages.deleteQuickReplyShortcut)
  - [messages.editQuickReplyShortcut](https://core.telegram.org/method/messages.editQuickReplyShortcut)
  - [messages.getQuickReplyMessages](https://core.telegram.org/method/messages.getQuickReplyMessages)

- SLOTS_EMPTY : The specified slot list is empty.

  - [premium.applyBoost](https://core.telegram.org/method/premium.applyBoost)

- SLOWMODE_MULTI_MSGS_DISABLED : Slowmode is enabled, you cannot forward multiple messages to this group.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- SLUG_INVALID : The specified invoice slug is invalid.

  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)

- SMS_CODE_CREATE_FAILED : An error occurred while creating the SMS code.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- SMSJOB_ID_INVALID : The specified job ID is invalid.

  - [smsjobs.finishJob](https://core.telegram.org/method/smsjobs.finishJob)
  - [smsjobs.getSmsJob](https://core.telegram.org/method/smsjobs.getSmsJob)

- SRP_A_INVALID : The specified inputCheckPasswordSRP.A value is invalid.

  - [account.getTmpPassword](https://core.telegram.org/method/account.getTmpPassword)

- SRP_ID_INVALID : Invalid SRP ID provided.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)
  - [auth.checkPassword](https://core.telegram.org/method/auth.checkPassword)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)

- SRP_PASSWORD_CHANGED : Password has changed.

  - [account.updatePasswordSettings](https://core.telegram.org/method/account.updatePasswordSettings)
  - [auth.checkPassword](https://core.telegram.org/method/auth.checkPassword)

- STARGIFT_INVALID : The passed [inputInvoiceStarGift](https://core.telegram.org/constructor/inputInvoiceStarGift) is invalid.

  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)

- STARGIFT_USAGE_LIMITED : The gift is sold out.

  - [payments.sendStarsForm](https://core.telegram.org/method/payments.sendStarsForm)

- STARREF_AWAITING_END : The previous referral program was terminated less than 24 hours ago: further changes can be made after the date specified in userFull.starref_program.end_date.

  - [bots.updateStarRefProgram](https://core.telegram.org/method/bots.updateStarRefProgram)

- STARREF_HASH_REVOKED : The specified affiliate link was already revoked.

  - [payments.editConnectedStarRefBot](https://core.telegram.org/method/payments.editConnectedStarRefBot)

- STARREF_PERMILLE_INVALID : The specified commission_permille is invalid: the minimum and maximum values for this parameter are contained in the [starref_min_commission_permille](https://core.telegram.org/api/config#starref-min-commission-permille) and [starref_max_commission_permille](https://core.telegram.org/api/config#starref-max-commission-permille) client configuration parameters.

  - [bots.updateStarRefProgram](https://core.telegram.org/method/bots.updateStarRefProgram)

- STARREF_PERMILLE_TOO_LOW : The specified commission_permille is too low: the minimum and maximum values for this parameter are contained in the [starref_min_commission_permille](https://core.telegram.org/api/config#starref-min-commission-permille) and [starref_max_commission_permille](https://core.telegram.org/api/config#starref-max-commission-permille) client configuration parameters.

  - [bots.updateStarRefProgram](https://core.telegram.org/method/bots.updateStarRefProgram)

- STARS_INVOICE_INVALID : The specified Telegram Star invoice is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- STARS_PAYMENT_REQUIRED : To import this chat invite link, you must first [pay for the associated Telegram Star subscription &raquo;](https://core.telegram.org/api/subscriptions#channel-subscriptions).

  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- START_PARAM_EMPTY : The start parameter is empty.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)

- START_PARAM_INVALID : Start parameter invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)

- START_PARAM_TOO_LONG : Start parameter is too long.

  - [messages.startBot](https://core.telegram.org/method/messages.startBot)

- STICKER_DOCUMENT_INVALID : The specified sticker document is invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- STICKER_EMOJI_INVALID : Sticker emoji invalid.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_FILE_INVALID : Sticker file invalid.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_GIF_DIMENSIONS : The specified video sticker has invalid dimensions.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_ID_INVALID : The provided sticker ID is invalid.

  - [messages.faveSticker](https://core.telegram.org/method/messages.faveSticker)
  - [messages.saveRecentSticker](https://core.telegram.org/method/messages.saveRecentSticker)

- STICKER_INVALID : The provided sticker is invalid.

  - [stickers.changeSticker](https://core.telegram.org/method/stickers.changeSticker)
  - [stickers.changeStickerPosition](https://core.telegram.org/method/stickers.changeStickerPosition)
  - [stickers.removeStickerFromSet](https://core.telegram.org/method/stickers.removeStickerFromSet)
  - [stickers.replaceSticker](https://core.telegram.org/method/stickers.replaceSticker)

- STICKER_MIME_INVALID : The specified sticker MIME type is invalid.

  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- STICKER_PNG_DIMENSIONS : Sticker png dimensions invalid.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_PNG_NOPNG : One of the specified stickers is not a valid PNG file.

  - [stickers.addStickerToSet](https://core.telegram.org/method/stickers.addStickerToSet)
  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_TGS_NODOC : You must send the animated sticker as a document.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_TGS_NOTGS : Invalid TGS sticker provided.

  - [stickers.addStickerToSet](https://core.telegram.org/method/stickers.addStickerToSet)
  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_THUMB_PNG_NOPNG : Incorrect stickerset thumb file provided, PNG / WEBP expected.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)
  - [stickers.setStickerSetThumb](https://core.telegram.org/method/stickers.setStickerSetThumb)

- STICKER_THUMB_TGS_NOTGS : Incorrect stickerset TGS thumb file provided.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)
  - [stickers.setStickerSetThumb](https://core.telegram.org/method/stickers.setStickerSetThumb)

- STICKER_VIDEO_BIG : The specified video sticker is too big.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_VIDEO_NODOC : You must send the video sticker as a document.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKER_VIDEO_NOWEBM : The specified video sticker is not in webm format.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKERPACK_STICKERS_TOO_MUCH : There are too many stickers in this stickerpack, you can't add any more.

  - [stickers.addStickerToSet](https://core.telegram.org/method/stickers.addStickerToSet)

- STICKERS_EMPTY : No sticker provided.

  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)

- STICKERS_TOO_MUCH : There are too many stickers in this stickerpack, you can't add any more.

  - [stickers.addStickerToSet](https://core.telegram.org/method/stickers.addStickerToSet)

- STICKERSET_INVALID : The provided sticker set is invalid.

  - [messages.getStickerSet](https://core.telegram.org/method/messages.getStickerSet)
  - [messages.installStickerSet](https://core.telegram.org/method/messages.installStickerSet)
  - [messages.uninstallStickerSet](https://core.telegram.org/method/messages.uninstallStickerSet)
  - [stickers.addStickerToSet](https://core.telegram.org/method/stickers.addStickerToSet)
  - [stickers.deleteStickerSet](https://core.telegram.org/method/stickers.deleteStickerSet)
  - [stickers.renameStickerSet](https://core.telegram.org/method/stickers.renameStickerSet)
  - [stickers.setStickerSetThumb](https://core.telegram.org/method/stickers.setStickerSetThumb)

- STORIES_NEVER_CREATED : This peer hasn't ever posted any stories.

  - [stats.getStoryStats](https://core.telegram.org/method/stats.getStoryStats)
  - [stories.getStoriesByID](https://core.telegram.org/method/stories.getStoriesByID)
  - [stories.readStories](https://core.telegram.org/method/stories.readStories)

- STORIES_TOO_MUCH : You have hit the maximum active stories limit as specified by the [`story_expiring_limit_*` client configuration parameters](https://core.telegram.org/api/config#story-expiring-limit-default): you should buy a [Premium](https://core.telegram.org/api/premium) subscription, delete an active story, or wait for the oldest story to expire.

  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)
  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- STORY_ID_EMPTY : You specified no story IDs.

  - [stories.deleteStories](https://core.telegram.org/method/stories.deleteStories)
  - [stories.exportStoryLink](https://core.telegram.org/method/stories.exportStoryLink)
  - [stories.getStoriesByID](https://core.telegram.org/method/stories.getStoriesByID)
  - [stories.getStoriesViews](https://core.telegram.org/method/stories.getStoriesViews)
  - [stories.incrementStoryViews](https://core.telegram.org/method/stories.incrementStoryViews)
  - [stories.sendReaction](https://core.telegram.org/method/stories.sendReaction)

- STORY_ID_INVALID : The specified story ID is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendScreenshotNotification](https://core.telegram.org/method/messages.sendScreenshotNotification)
  - [stories.getStoryViewsList](https://core.telegram.org/method/stories.getStoryViewsList)
  - [stories.sendReaction](https://core.telegram.org/method/stories.sendReaction)
  - [stories.togglePinnedToTop](https://core.telegram.org/method/stories.togglePinnedToTop)

- STORY_NOT_MODIFIED : The new story information you passed is equal to the previous story information, thus it wasn't modified.

  - [stories.editStory](https://core.telegram.org/method/stories.editStory)

- STORY_PERIOD_INVALID : The specified story period is invalid for this account.

  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- STORY_SEND_FLOOD_MONTHLY_%d : You've hit the monthly story limit as specified by the [`stories_sent_monthly_limit_*` client configuration parameters](https://core.telegram.org/api/config#stories-sent-monthly-limit-default): wait for the specified number of seconds before posting a new story.

  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)

- STORY_SEND_FLOOD_WEEKLY_%d : You've hit the weekly story limit as specified by the [`stories_sent_weekly_limit_*` client configuration parameters](https://core.telegram.org/api/config#stories-sent-weekly-limit-default): wait for the specified number of seconds before posting a new story.

  - [stories.canSendStory](https://core.telegram.org/method/stories.canSendStory)

- SUBSCRIPTION_EXPORT_MISSING : You cannot send a [bot subscription invoice](https://core.telegram.org/api/subscriptions#bot-subscriptions) directly, you may only create invoice links using [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice).

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- SUBSCRIPTION_PERIOD_INVALID : The specified subscription_pricing.period is invalid.

  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)

- SWITCH_PM_TEXT_EMPTY : The switch_pm.text field was empty.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- SWITCH_WEBVIEW_URL_INVALID : The URL specified in switch_webview.url is invalid!

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- TAKEOUT_INVALID : The specified takeout ID is invalid.

  - [contacts.getSaved](https://core.telegram.org/method/contacts.getSaved)
  - [messages.getDialogs](https://core.telegram.org/method/messages.getDialogs)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)

- TAKEOUT_REQUIRED : A [takeout](https://core.telegram.org/api/takeout) session needs to be initialized first, [see here &raquo; for more info](https://core.telegram.org/api/takeout).

  - [contacts.getSaved](https://core.telegram.org/method/contacts.getSaved)

- TASK_ALREADY_EXISTS : An email reset was already requested.

  - [auth.resetLoginEmail](https://core.telegram.org/method/auth.resetLoginEmail)

- TEMP_AUTH_KEY_ALREADY_BOUND : The passed temporary key is already bound to another **perm_auth_key_id**.

  - [auth.bindTempAuthKey](https://core.telegram.org/method/auth.bindTempAuthKey)

- TEMP_AUTH_KEY_EMPTY : No temporary auth key provided.

  - [auth.bindTempAuthKey](https://core.telegram.org/method/auth.bindTempAuthKey)

- TERMS_URL_INVALID : The specified [invoice](https://core.telegram.org/constructor/invoice).`terms_url` is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- THEME_FILE_INVALID : Invalid theme file provided.

  - [account.uploadTheme](https://core.telegram.org/method/account.uploadTheme)

- THEME_FORMAT_INVALID : Invalid theme format provided.

  - [account.getTheme](https://core.telegram.org/method/account.getTheme)

- THEME_INVALID : Invalid theme provided.

  - [account.getTheme](https://core.telegram.org/method/account.getTheme)
  - [account.saveTheme](https://core.telegram.org/method/account.saveTheme)
  - [account.updateTheme](https://core.telegram.org/method/account.updateTheme)

- THEME_MIME_INVALID : The theme's MIME type is invalid.

  - [account.createTheme](https://core.telegram.org/method/account.createTheme)
  - [account.uploadTheme](https://core.telegram.org/method/account.uploadTheme)

- THEME_PARAMS_INVALID : The specified `theme_params` field is invalid.

  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)

- THEME_TITLE_INVALID : The specified theme title is invalid.

  - [account.createTheme](https://core.telegram.org/method/account.createTheme)

- TIMEZONE_INVALID : The specified timezone does not exist.

  - [account.updateBusinessWorkHours](https://core.telegram.org/method/account.updateBusinessWorkHours)

- TITLE_INVALID : The specified stickerpack title is invalid.

  - [stickers.suggestShortName](https://core.telegram.org/method/stickers.suggestShortName)

- TMP_PASSWORD_DISABLED : The temporary password is disabled.

  - [account.getTmpPassword](https://core.telegram.org/method/account.getTmpPassword)

- TMP_PASSWORD_INVALID : The passed tmp_password is invalid.

  - [payments.sendPaymentForm](https://core.telegram.org/method/payments.sendPaymentForm)

- TO_LANG_INVALID : The specified destination language is invalid.

  - [messages.translateText](https://core.telegram.org/method/messages.translateText)

- TOKEN_EMPTY : The specified token is empty.

  - [account.registerDevice](https://core.telegram.org/method/account.registerDevice)

- TOKEN_INVALID : The provided token is invalid.

  - [account.registerDevice](https://core.telegram.org/method/account.registerDevice)
  - [account.unregisterDevice](https://core.telegram.org/method/account.unregisterDevice)

- TOKEN_TYPE_INVALID : The specified token type is invalid.

  - [account.registerDevice](https://core.telegram.org/method/account.registerDevice)

- TOPIC_CLOSE_SEPARATELY : The `close` flag cannot be provided together with any of the other flags.

  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)

- TOPIC_CLOSED : This topic was closed, you can't send messages to it anymore.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- TOPIC_DELETED : The specified topic was deleted.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- TOPIC_HIDE_SEPARATELY : The `hide` flag cannot be provided together with any of the other flags.

  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)

- TOPIC_ID_INVALID : The specified topic ID is invalid.

  - [channels.deleteTopicHistory](https://core.telegram.org/method/channels.deleteTopicHistory)
  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)
  - [channels.updatePinnedForumTopic](https://core.telegram.org/method/channels.updatePinnedForumTopic)
  - [messages.getDiscussionMessage](https://core.telegram.org/method/messages.getDiscussionMessage)
  - [messages.getReplies](https://core.telegram.org/method/messages.getReplies)

- TOPIC_NOT_MODIFIED : The updated topic info is equal to the current topic info, nothing was changed.

  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)

- TOPIC_TITLE_EMPTY : The specified topic title is empty.

  - [channels.createForumTopic](https://core.telegram.org/method/channels.createForumTopic)

- TOPICS_EMPTY : You specified no topic IDs.

  - [channels.getForumTopicsByID](https://core.telegram.org/method/channels.getForumTopicsByID)

- TRANSACTION_ID_INVALID : The specified transaction ID is invalid.

  - [payments.getStarsTransactionsByID](https://core.telegram.org/method/payments.getStarsTransactionsByID)

- TRANSCRIPTION_FAILED : Audio transcription failed.

  - [messages.transcribeAudio](https://core.telegram.org/method/messages.transcribeAudio)

- TRANSLATE_REQ_QUOTA_EXCEEDED : Translation is currently unavailable due to a temporary server-side lack of resources.

  - [messages.translateText](https://core.telegram.org/method/messages.translateText)

- TTL_DAYS_INVALID : The provided TTL is invalid.

  - [account.setAccountTTL](https://core.telegram.org/method/account.setAccountTTL)
  - [account.setAuthorizationTTL](https://core.telegram.org/method/account.setAuthorizationTTL)

- TTL_MEDIA_INVALID : Invalid media Time To Live was provided.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- TTL_PERIOD_INVALID : The specified TTL period is invalid.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [messages.createChat](https://core.telegram.org/method/messages.createChat)
  - [messages.setDefaultHistoryTTL](https://core.telegram.org/method/messages.setDefaultHistoryTTL)
  - [messages.setHistoryTTL](https://core.telegram.org/method/messages.setHistoryTTL)

- TYPES_EMPTY : No top peer type was provided.

  - [contacts.getTopPeers](https://core.telegram.org/method/contacts.getTopPeers)

- UNTIL_DATE_INVALID : Invalid until date provided.

  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [payments.getPaymentForm](https://core.telegram.org/method/payments.getPaymentForm)

- URL_INVALID : Invalid URL provided.

  - [messages.requestSimpleWebView](https://core.telegram.org/method/messages.requestSimpleWebView)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.setBotCallbackAnswer](https://core.telegram.org/method/messages.setBotCallbackAnswer)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- USAGE_LIMIT_INVALID : The specified usage limit is invalid.

  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)

- USER_ADMIN_INVALID : You're not an admin.

  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)

- USER_ALREADY_INVITED : You have already invited this user.

  - [phone.inviteToGroupCall](https://core.telegram.org/method/phone.inviteToGroupCall)

- USER_ALREADY_PARTICIPANT : The user is already in the group.

  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- USER_BANNED_IN_CHANNEL : You're banned from sending messages in supergroups/channels.

  - [channels.getChannels](https://core.telegram.org/method/channels.getChannels)
  - [channels.getMessages](https://core.telegram.org/method/channels.getMessages)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [users.getUsers](https://core.telegram.org/method/users.getUsers)

- USER_BLOCKED : User blocked.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)

- USER_BOT : Bots can only be admins in channels.

  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)

- USER_BOT_INVALID : User accounts must provide the `bot` method parameter when calling this method. If there is no such method parameter, this method can only be invoked by bot accounts.

  - [bots.answerWebhookJSONQuery](https://core.telegram.org/method/bots.answerWebhookJSONQuery)
  - [bots.getBotCommands](https://core.telegram.org/method/bots.getBotCommands)
  - [bots.getBotInfo](https://core.telegram.org/method/bots.getBotInfo)
  - [bots.sendCustomRequest](https://core.telegram.org/method/bots.sendCustomRequest)
  - [bots.setBotInfo](https://core.telegram.org/method/bots.setBotInfo)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- USER_BOT_REQUIRED : This method can only be called by a bot.

  - [bots.answerWebhookJSONQuery](https://core.telegram.org/method/bots.answerWebhookJSONQuery)
  - [bots.getBotCommands](https://core.telegram.org/method/bots.getBotCommands)
  - [bots.getBotMenuButton](https://core.telegram.org/method/bots.getBotMenuButton)
  - [bots.resetBotCommands](https://core.telegram.org/method/bots.resetBotCommands)
  - [bots.sendCustomRequest](https://core.telegram.org/method/bots.sendCustomRequest)
  - [bots.setBotBroadcastDefaultAdminRights](https://core.telegram.org/method/bots.setBotBroadcastDefaultAdminRights)
  - [bots.setBotCommands](https://core.telegram.org/method/bots.setBotCommands)
  - [bots.setBotGroupDefaultAdminRights](https://core.telegram.org/method/bots.setBotGroupDefaultAdminRights)
  - [bots.setBotMenuButton](https://core.telegram.org/method/bots.setBotMenuButton)
  - [bots.updateUserEmojiStatus](https://core.telegram.org/method/bots.updateUserEmojiStatus)
  - [help.setBotUpdatesStatus](https://core.telegram.org/method/help.setBotUpdatesStatus)
  - [messages.getGameHighScores](https://core.telegram.org/method/messages.getGameHighScores)
  - [messages.getInlineGameHighScores](https://core.telegram.org/method/messages.getInlineGameHighScores)
  - [messages.savePreparedInlineMessage](https://core.telegram.org/method/messages.savePreparedInlineMessage)
  - [messages.sendWebViewResultMessage](https://core.telegram.org/method/messages.sendWebViewResultMessage)
  - [messages.setBotCallbackAnswer](https://core.telegram.org/method/messages.setBotCallbackAnswer)
  - [messages.setBotPrecheckoutResults](https://core.telegram.org/method/messages.setBotPrecheckoutResults)
  - [messages.setBotShippingResults](https://core.telegram.org/method/messages.setBotShippingResults)
  - [messages.setGameScore](https://core.telegram.org/method/messages.setGameScore)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [messages.setInlineGameScore](https://core.telegram.org/method/messages.setInlineGameScore)
  - [payments.refundStarsCharge](https://core.telegram.org/method/payments.refundStarsCharge)
  - [users.setSecureValueErrors](https://core.telegram.org/method/users.setSecureValueErrors)

- USER_CHANNELS_TOO_MUCH : One of the users you tried to add is already in too many channels/supergroups.

  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- USER_CREATOR : For channels.editAdmin: you've tried to edit the admin rights of the owner, but you're not the owner; for channels.leaveChannel: you can't leave this channel, because you're its creator.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)

- USER_GIFT_UNAVAILABLE : Gifts are not available in the current region ([stars_gifts_enabled](https://core.telegram.org/api/config#stars-gifts-enabled) is equal to false).

  - [payments.getStarsGiftOptions](https://core.telegram.org/method/payments.getStarsGiftOptions)

- USER_ID_INVALID : The provided user ID is invalid.

  - [auth.importAuthorization](https://core.telegram.org/method/auth.importAuthorization)
  - [bots.setBotCommands](https://core.telegram.org/method/bots.setBotCommands)
  - [bots.updateUserEmojiStatus](https://core.telegram.org/method/bots.updateUserEmojiStatus)
  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.reportSpam](https://core.telegram.org/method/channels.reportSpam)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.deleteChatUser](https://core.telegram.org/method/messages.deleteChatUser)
  - [messages.editChatAdmin](https://core.telegram.org/method/messages.editChatAdmin)
  - [messages.getCommonChats](https://core.telegram.org/method/messages.getCommonChats)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.reportReaction](https://core.telegram.org/method/messages.reportReaction)
  - [messages.requestEncryption](https://core.telegram.org/method/messages.requestEncryption)
  - [messages.savePreparedInlineMessage](https://core.telegram.org/method/messages.savePreparedInlineMessage)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [payments.botCancelStarsSubscription](https://core.telegram.org/method/payments.botCancelStarsSubscription)
  - [payments.convertStarGift](https://core.telegram.org/method/payments.convertStarGift)
  - [payments.getStarsGiftOptions](https://core.telegram.org/method/payments.getStarsGiftOptions)
  - [payments.getUserStarGifts](https://core.telegram.org/method/payments.getUserStarGifts)
  - [payments.refundStarsCharge](https://core.telegram.org/method/payments.refundStarsCharge)
  - [payments.saveStarGift](https://core.telegram.org/method/payments.saveStarGift)
  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)
  - [photos.getUserPhotos](https://core.telegram.org/method/photos.getUserPhotos)
  - [photos.uploadContactProfilePhoto](https://core.telegram.org/method/photos.uploadContactProfilePhoto)
  - [stickers.createStickerSet](https://core.telegram.org/method/stickers.createStickerSet)
  - [stories.getPinnedStories](https://core.telegram.org/method/stories.getPinnedStories)
  - [stories.getUserStories](https://core.telegram.org/method/stories.getUserStories)
  - [users.getFullUser](https://core.telegram.org/method/users.getFullUser)
  - [users.setSecureValueErrors](https://core.telegram.org/method/users.setSecureValueErrors)

- USER_INVALID : Invalid user provided.

  - [help.editUserInfo](https://core.telegram.org/method/help.editUserInfo)
  - [help.getSupportName](https://core.telegram.org/method/help.getSupportName)
  - [help.getUserInfo](https://core.telegram.org/method/help.getUserInfo)

- USER_IS_BLOCKED : You were blocked by this user.

  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)

- USER_IS_BOT : Bots can't send messages to other bots.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)

- USER_KICKED : This user was kicked from this supergroup/channel.

  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)

- USER_NOT_MUTUAL_CONTACT : The provided user is not a mutual contact.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.checkHistoryImportPeer](https://core.telegram.org/method/messages.checkHistoryImportPeer)

- USER_NOT_PARTICIPANT : You're not a member of this supergroup/channel.

  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [messages.deleteChatUser](https://core.telegram.org/method/messages.deleteChatUser)
  - [messages.editChatAdmin](https://core.telegram.org/method/messages.editChatAdmin)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- USER_PUBLIC_MISSING : Cannot generate a link to stories posted by a peer without a username.

  - [stories.exportStoryLink](https://core.telegram.org/method/stories.exportStoryLink)

- USER_VOLUME_INVALID : The specified user volume is invalid.

  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)

- USERNAME_INVALID : The provided username is not valid.

  - [account.checkUsername](https://core.telegram.org/method/account.checkUsername)
  - [account.toggleUsername](https://core.telegram.org/method/account.toggleUsername)
  - [account.updateUsername](https://core.telegram.org/method/account.updateUsername)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [contacts.resolveUsername](https://core.telegram.org/method/contacts.resolveUsername)
  - [help.getConfig](https://core.telegram.org/method/help.getConfig)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- USERNAME_NOT_MODIFIED : The username was not modified.

  - [account.reorderUsernames](https://core.telegram.org/method/account.reorderUsernames)
  - [account.toggleUsername](https://core.telegram.org/method/account.toggleUsername)
  - [account.updateUsername](https://core.telegram.org/method/account.updateUsername)
  - [bots.reorderUsernames](https://core.telegram.org/method/bots.reorderUsernames)
  - [bots.toggleUsername](https://core.telegram.org/method/bots.toggleUsername)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)

- USERNAME_NOT_OCCUPIED : The provided username is not occupied.

  - [contacts.resolveUsername](https://core.telegram.org/method/contacts.resolveUsername)

- USERNAME_OCCUPIED : The provided username is already occupied.

  - [account.checkUsername](https://core.telegram.org/method/account.checkUsername)
  - [account.updateUsername](https://core.telegram.org/method/account.updateUsername)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [users.getFullUser](https://core.telegram.org/method/users.getFullUser)

- USERNAME_PURCHASE_AVAILABLE : The specified username can be purchased on https://fragment.com.

  - [account.checkUsername](https://core.telegram.org/method/account.checkUsername)
  - [account.updateUsername](https://core.telegram.org/method/account.updateUsername)
  - [channels.checkUsername](https://core.telegram.org/method/channels.checkUsername)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)

- USERNAMES_ACTIVE_TOO_MUCH : The maximum number of active usernames was reached.

  - [account.toggleUsername](https://core.telegram.org/method/account.toggleUsername)
  - [channels.toggleUsername](https://core.telegram.org/method/channels.toggleUsername)

- USERPIC_UPLOAD_REQUIRED : You must have a profile picture to publish your geolocation.

  - [contacts.getLocated](https://core.telegram.org/method/contacts.getLocated)

- USERS_TOO_FEW : Not enough users (to create a chat, for example).

  - [messages.createChat](https://core.telegram.org/method/messages.createChat)

- USERS_TOO_MUCH : The maximum number of users has been exceeded (to create a chat, for example).

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- VENUE_ID_INVALID : The specified venue ID is invalid.

  - [stories.sendStory](https://core.telegram.org/method/stories.sendStory)

- VIDEO_CONTENT_TYPE_INVALID : The video's content type is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- VIDEO_FILE_INVALID : The specified video file is invalid.

  - [photos.uploadProfilePhoto](https://core.telegram.org/method/photos.uploadProfilePhoto)

- VIDEO_PAUSE_FORBIDDEN : You cannot pause the video stream.

  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)

- VIDEO_STOP_FORBIDDEN : You cannot stop the video stream.

  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)

- VIDEO_TITLE_EMPTY : The specified video title is empty.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- VOICE_MESSAGES_FORBIDDEN : This user's privacy settings forbid you from sending voice messages.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- WALLPAPER_FILE_INVALID : The specified wallpaper file is invalid.

  - [account.uploadWallPaper](https://core.telegram.org/method/account.uploadWallPaper)

- WALLPAPER_INVALID : The specified wallpaper is invalid.

  - [account.getMultiWallPapers](https://core.telegram.org/method/account.getMultiWallPapers)
  - [account.getWallPaper](https://core.telegram.org/method/account.getWallPaper)
  - [account.installWallPaper](https://core.telegram.org/method/account.installWallPaper)
  - [account.saveWallPaper](https://core.telegram.org/method/account.saveWallPaper)
  - [messages.setChatWallPaper](https://core.telegram.org/method/messages.setChatWallPaper)

- WALLPAPER_MIME_INVALID : The specified wallpaper MIME type is invalid.

  - [account.uploadWallPaper](https://core.telegram.org/method/account.uploadWallPaper)

- WALLPAPER_NOT_FOUND : The specified wallpaper could not be found.

  - [messages.setChatWallPaper](https://core.telegram.org/method/messages.setChatWallPaper)

- WC_CONVERT_URL_INVALID : WC convert URL invalid.

  - [messages.getWebPage](https://core.telegram.org/method/messages.getWebPage)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- WEBDOCUMENT_INVALID : Invalid webdocument URL provided.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- WEBDOCUMENT_MIME_INVALID : Invalid webdocument mime type provided.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- WEBDOCUMENT_SIZE_TOO_BIG : Webdocument is too big!

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- WEBDOCUMENT_URL_EMPTY : The passed web document URL is empty.

  - [payments.exportInvoice](https://core.telegram.org/method/payments.exportInvoice)

- WEBDOCUMENT_URL_INVALID : The specified webdocument URL is invalid.

  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)

- WEBPAGE_CURL_FAILED : Failure while fetching the webpage with cURL.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)

- WEBPAGE_MEDIA_EMPTY : Webpage media empty.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- WEBPAGE_NOT_FOUND : A preview for the specified webpage `url` could not be generated.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- WEBPAGE_URL_INVALID : The specified webpage `url` is invalid.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- WEBPUSH_AUTH_INVALID : The specified web push authentication secret is invalid.

  - [account.registerDevice](https://core.telegram.org/method/account.registerDevice)

- WEBPUSH_KEY_INVALID : The specified web push elliptic curve Diffie-Hellman public key is invalid.

  - [account.registerDevice](https://core.telegram.org/method/account.registerDevice)

- WEBPUSH_TOKEN_INVALID : The specified web push token is invalid.

  - [account.registerDevice](https://core.telegram.org/method/account.registerDevice)

- YOU_BLOCKED_USER : You blocked this user.

  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.forwardMessage](https://core.telegram.org/method/messages.forwardMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendScreenshotNotification](https://core.telegram.org/method/messages.sendScreenshotNotification)

- BOT_METHOD_INVALID : The specified method cannot be used by bots.


- CONNECTION_DEVICE_MODEL_EMPTY : The specified device model is empty.


- CONNECTION_LANG_PACK_INVALID : The specified language pack is empty.


- CONNECTION_NOT_INITED : Please initialize the connection using initConnection before making queries.


- CONNECTION_SYSTEM_EMPTY : The specified system version is empty.


- CONNECTION_SYSTEM_LANG_CODE_EMPTY : The specified system language code is empty.


- FILE_MIGRATE_%d : The file currently being accessed is stored in DC %d, please re-send the query to that DC.


- FILE_PART_%d_MISSING : Part %d of the file is missing from storage. Try repeating the method call to resave the part.


- INPUT_CONSTRUCTOR_INVALID : The specified TL constructor is invalid.


- INPUT_FETCH_ERROR : An error occurred while parsing the provided TL constructor.


- INPUT_FETCH_FAIL : An error occurred while parsing the provided TL constructor.


- INPUT_LAYER_INVALID : The specified layer is invalid.


- INPUT_METHOD_INVALID : The specified method is invalid.


- INPUT_REQUEST_TOO_LONG : The request payload is too long.


- PEER_FLOOD : The current account is spamreported, you cannot execute this action, check @spambot for more info.


- STICKERSET_NOT_MODIFIED : The passed stickerset information is equal to the current information.


## UNAUTHORIZED 401

- AUTH_KEY_INVALID : The specified auth key is invalid.


- AUTH_KEY_PERM_EMPTY : The method is unavailable for temporary authorization keys, not bound to a permanent authorization key.


- AUTH_KEY_UNREGISTERED : The specified authorization key is not registered in the system (for example, a PFS temporary key has expired).


- SESSION_EXPIRED : The session has expired.


- SESSION_PASSWORD_NEEDED : 2FA is enabled, use a password to login.


- SESSION_REVOKED : The session was revoked by the user.


- USER_DEACTIVATED : The current account was deleted by the user.


- USER_DEACTIVATED_BAN : The current account was deleted and banned by Telegram's antispam system.


## FORBIDDEN 403

- ANONYMOUS_REACTIONS_DISABLED : Sorry, anonymous administrators cannot leave reactions or participate in polls.

  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)

- BROADCAST_FORBIDDEN : Channel poll voters and reactions cannot be fetched to prevent deanonymization.

  - [messages.getMessageReactionsList](https://core.telegram.org/method/messages.getMessageReactionsList)
  - [messages.getPollVotes](https://core.telegram.org/method/messages.getPollVotes)

- CHANNEL_PUBLIC_GROUP_NA : channel/supergroup not available.

  - [channels.getFullChannel](https://core.telegram.org/method/channels.getFullChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)

- CHAT_ACTION_FORBIDDEN : You cannot execute this action.

  - [messages.deleteFactCheck](https://core.telegram.org/method/messages.deleteFactCheck)
  - [messages.editFactCheck](https://core.telegram.org/method/messages.editFactCheck)

- CHAT_ADMIN_INVITE_REQUIRED : You do not have the rights to do this.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- CHAT_ADMIN_REQUIRED : You must be an admin in this chat to do this.

  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.editForumTopic](https://core.telegram.org/method/channels.editForumTopic)
  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.getParticipants](https://core.telegram.org/method/channels.getParticipants)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.migrateChat](https://core.telegram.org/method/messages.migrateChat)
  - [messages.search](https://core.telegram.org/method/messages.search)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [stats.getBroadcastStats](https://core.telegram.org/method/stats.getBroadcastStats)
  - [stats.getMegagroupStats](https://core.telegram.org/method/stats.getMegagroupStats)

- CHAT_GUEST_SEND_FORBIDDEN : You join the discussion group before commenting, see [here &raquo;](https://core.telegram.org/api/discussion#requiring-users-to-join-the-group) for more info.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- CHAT_SEND_AUDIOS_FORBIDDEN : You can't send audio messages in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_SEND_DOCS_FORBIDDEN : You can't send documents in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_SEND_GAME_FORBIDDEN : You can't send a game to this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- CHAT_SEND_GIFS_FORBIDDEN : You can't send gifs in this chat.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_SEND_INLINE_FORBIDDEN : You can't send inline messages in this group.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- CHAT_SEND_MEDIA_FORBIDDEN : You can't send media in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- CHAT_SEND_PHOTOS_FORBIDDEN : You can't send photos in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- CHAT_SEND_PLAIN_FORBIDDEN : You can't send non-media (text) messages in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- CHAT_SEND_POLL_FORBIDDEN : You can't send polls in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_SEND_ROUNDVIDEOS_FORBIDDEN : You can't send round videos to this chat.

  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_SEND_STICKERS_FORBIDDEN : You can't send stickers in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_SEND_VIDEOS_FORBIDDEN : You can't send videos in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- CHAT_SEND_VOICES_FORBIDDEN : You can't send voice recordings in this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)

- CHAT_TYPE_INVALID : The specified user type is invalid.

  - [phone.inviteToGroupCall](https://core.telegram.org/method/phone.inviteToGroupCall)

- CHAT_WRITE_FORBIDDEN : You can't write in this chat.

  - [channels.convertToGigagroup](https://core.telegram.org/method/channels.convertToGigagroup)
  - [channels.createForumTopic](https://core.telegram.org/method/channels.createForumTopic)
  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)
  - [channels.deleteParticipantHistory](https://core.telegram.org/method/channels.deleteParticipantHistory)
  - [channels.deleteUserHistory](https://core.telegram.org/method/channels.deleteUserHistory)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.editCreator](https://core.telegram.org/method/channels.editCreator)
  - [channels.editPhoto](https://core.telegram.org/method/channels.editPhoto)
  - [channels.editTitle](https://core.telegram.org/method/channels.editTitle)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.setDiscussionGroup](https://core.telegram.org/method/channels.setDiscussionGroup)
  - [channels.updateUsername](https://core.telegram.org/method/channels.updateUsername)
  - [invokeWithLayer](https://core.telegram.org/method/invokeWithLayer)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.editChatAbout](https://core.telegram.org/method/messages.editChatAbout)
  - [messages.editChatDefaultBannedRights](https://core.telegram.org/method/messages.editChatDefaultBannedRights)
  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.exportChatInvite](https://core.telegram.org/method/messages.exportChatInvite)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getAdminsWithInvites](https://core.telegram.org/method/messages.getAdminsWithInvites)
  - [messages.getChatInviteImporters](https://core.telegram.org/method/messages.getChatInviteImporters)
  - [messages.getDialogs](https://core.telegram.org/method/messages.getDialogs)
  - [messages.getExportedChatInvite](https://core.telegram.org/method/messages.getExportedChatInvite)
  - [messages.getExportedChatInvites](https://core.telegram.org/method/messages.getExportedChatInvites)
  - [messages.getMessageEditData](https://core.telegram.org/method/messages.getMessageEditData)
  - [messages.hideAllChatJoinRequests](https://core.telegram.org/method/messages.hideAllChatJoinRequests)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [messages.updatePinnedMessage](https://core.telegram.org/method/messages.updatePinnedMessage)
  - [messages.uploadMedia](https://core.telegram.org/method/messages.uploadMedia)
  - [payments.getStarsRevenueAdsAccountUrl](https://core.telegram.org/method/payments.getStarsRevenueAdsAccountUrl)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- EDIT_BOT_INVITE_FORBIDDEN : Normal users can't edit invites that were created by bots.

  - [messages.editExportedChatInvite](https://core.telegram.org/method/messages.editExportedChatInvite)

- GROUPCALL_ALREADY_STARTED : The groupcall has already started, you can join directly using [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall).

  - [phone.startScheduledGroupCall](https://core.telegram.org/method/phone.startScheduledGroupCall)
  - [phone.toggleGroupCallStartSubscription](https://core.telegram.org/method/phone.toggleGroupCallStartSubscription)

- GROUPCALL_FORBIDDEN : The group call has already ended.

  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [phone.discardGroupCall](https://core.telegram.org/method/phone.discardGroupCall)
  - [phone.editGroupCallParticipant](https://core.telegram.org/method/phone.editGroupCallParticipant)
  - [phone.editGroupCallTitle](https://core.telegram.org/method/phone.editGroupCallTitle)
  - [phone.getGroupCall](https://core.telegram.org/method/phone.getGroupCall)
  - [phone.inviteToGroupCall](https://core.telegram.org/method/phone.inviteToGroupCall)
  - [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall)
  - [phone.toggleGroupCallRecord](https://core.telegram.org/method/phone.toggleGroupCallRecord)

- INLINE_BOT_REQUIRED : Only the inline bot can edit message.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)

- MESSAGE_AUTHOR_REQUIRED : Message author required.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.getMessageEditData](https://core.telegram.org/method/messages.getMessageEditData)

- MESSAGE_DELETE_FORBIDDEN : You can't delete one of the messages you tried to delete, most likely because it is a service message.

  - [channels.deleteMessages](https://core.telegram.org/method/channels.deleteMessages)
  - [messages.deleteMessages](https://core.telegram.org/method/messages.deleteMessages)
  - [messages.deleteScheduledMessages](https://core.telegram.org/method/messages.deleteScheduledMessages)

- NOT_ELIGIBLE : The current user is not eligible to join the Peer-to-Peer Login Program.

  - [smsjobs.isEligibleToJoin](https://core.telegram.org/method/smsjobs.isEligibleToJoin)

- PARTICIPANT_JOIN_MISSING : Trying to enable a presentation, when the user hasn't joined the Video Chat with [phone.joinGroupCall](https://core.telegram.org/method/phone.joinGroupCall).

  - [phone.joinGroupCallPresentation](https://core.telegram.org/method/phone.joinGroupCallPresentation)

- PEER_ID_INVALID : The provided peer id is invalid.

  - [payments.getSuggestedStarRefBots](https://core.telegram.org/method/payments.getSuggestedStarRefBots)

- POLL_VOTE_REQUIRED : Cast a vote in the poll before calling this method.

  - [messages.getPollVotes](https://core.telegram.org/method/messages.getPollVotes)

- PREMIUM_ACCOUNT_REQUIRED : A premium account is required to execute this action.

  - [account.createBusinessChatLink](https://core.telegram.org/method/account.createBusinessChatLink)
  - [account.editBusinessChatLink](https://core.telegram.org/method/account.editBusinessChatLink)
  - [account.setGlobalPrivacySettings](https://core.telegram.org/method/account.setGlobalPrivacySettings)
  - [account.updateColor](https://core.telegram.org/method/account.updateColor)
  - [account.updateConnectedBot](https://core.telegram.org/method/account.updateConnectedBot)
  - [channels.createForumTopic](https://core.telegram.org/method/channels.createForumTopic)
  - [messages.checkQuickReplyShortcut](https://core.telegram.org/method/messages.checkQuickReplyShortcut)
  - [messages.editQuickReplyShortcut](https://core.telegram.org/method/messages.editQuickReplyShortcut)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.reorderQuickReplies](https://core.telegram.org/method/messages.reorderQuickReplies)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendQuickReplyMessages](https://core.telegram.org/method/messages.sendQuickReplyMessages)
  - [messages.sendReaction](https://core.telegram.org/method/messages.sendReaction)
  - [messages.toggleDialogFilterTags](https://core.telegram.org/method/messages.toggleDialogFilterTags)
  - [messages.transcribeAudio](https://core.telegram.org/method/messages.transcribeAudio)
  - [messages.updateSavedReactionTag](https://core.telegram.org/method/messages.updateSavedReactionTag)

- PRIVACY_PREMIUM_REQUIRED : You need a [Telegram Premium subscription](https://core.telegram.org/api/premium) to send a message to this user.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.requestWebView](https://core.telegram.org/method/messages.requestWebView)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- PUBLIC_CHANNEL_MISSING : You can only export group call invite links for public chats or channels.

  - [phone.exportGroupCallInvite](https://core.telegram.org/method/phone.exportGroupCallInvite)

- RIGHT_FORBIDDEN : Your admin rights do not allow you to do this.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- SENSITIVE_CHANGE_FORBIDDEN : You can't change your sensitive content settings.

  - [account.setContentSettings](https://core.telegram.org/method/account.setContentSettings)

- TAKEOUT_REQUIRED : A [takeout](https://core.telegram.org/api/takeout) session needs to be initialized first, [see here &raquo; for more info](https://core.telegram.org/api/takeout).

  - [account.finishTakeoutSession](https://core.telegram.org/method/account.finishTakeoutSession)
  - [channels.getLeftChannels](https://core.telegram.org/method/channels.getLeftChannels)
  - [contacts.getSaved](https://core.telegram.org/method/contacts.getSaved)

- USER_BOT_INVALID : User accounts must provide the `bot` method parameter when calling this method. If there is no such method parameter, this method can only be invoked by bot accounts.

  - [bots.answerWebhookJSONQuery](https://core.telegram.org/method/bots.answerWebhookJSONQuery)
  - [bots.sendCustomRequest](https://core.telegram.org/method/bots.sendCustomRequest)
  - [messages.setInlineBotResults](https://core.telegram.org/method/messages.setInlineBotResults)
  - [users.setSecureValueErrors](https://core.telegram.org/method/users.setSecureValueErrors)

- USER_CHANNELS_TOO_MUCH : One of the users you tried to add is already in too many channels/supergroups.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [messages.hideChatJoinRequest](https://core.telegram.org/method/messages.hideChatJoinRequest)

- USER_DELETED : You can't send this secret message because the other participant deleted their account.

  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)

- USER_INVALID : Invalid user provided.

  - [help.editUserInfo](https://core.telegram.org/method/help.editUserInfo)
  - [help.getSupportName](https://core.telegram.org/method/help.getSupportName)
  - [help.getUserInfo](https://core.telegram.org/method/help.getUserInfo)

- USER_IS_BLOCKED : You were blocked by this user.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)

- USER_NOT_MUTUAL_CONTACT : The provided user is not a mutual contact.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)

- USER_NOT_PARTICIPANT : You're not a member of this supergroup/channel.

  - [phone.inviteToGroupCall](https://core.telegram.org/method/phone.inviteToGroupCall)

- USER_PRIVACY_RESTRICTED : The user's privacy settings do not allow you to do this.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [help.getConfig](https://core.telegram.org/method/help.getConfig)
  - [messages.addChatUser](https://core.telegram.org/method/messages.addChatUser)
  - [messages.getOutboxReadDate](https://core.telegram.org/method/messages.getOutboxReadDate)
  - [phone.requestCall](https://core.telegram.org/method/phone.requestCall)

- USER_RESTRICTED : You're spamreported, you can't create channels or chats.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)
  - [messages.createChat](https://core.telegram.org/method/messages.createChat)

- VOICE_MESSAGES_FORBIDDEN : This user's privacy settings forbid you from sending voice messages.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- YOUR_PRIVACY_RESTRICTED : You cannot fetch the read date of this message because you have disallowed other users to do so for *your* messages; to fix, allow other users to see *your* exact last online date OR purchase a [Telegram Premium](https://core.telegram.org/api/premium) subscription.

  - [messages.getOutboxReadDate](https://core.telegram.org/method/messages.getOutboxReadDate)

- CHAT_FORBIDDEN : This chat is not available to the current user.


## NOT_FOUND 404

- PEER_ID_INVALID : The provided peer id is invalid.

  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

## NOT_ACCEPTABLE 406

- BANNED_RIGHTS_INVALID : You provided some invalid flags in the banned rights.

  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)

- BUSINESS_ADDRESS_ACTIVE : The user is currently advertising a [Business Location](https://core.telegram.org/api/business#location), the location may only be changed (or removed) using [account.updateBusinessLocation &raquo;](https://core.telegram.org/method/account.updateBusinessLocation).  .

  - [contacts.getLocated](https://core.telegram.org/method/contacts.getLocated)

- CALL_PROTOCOL_COMPAT_LAYER_INVALID : The other side of the call does not support any of the VoIP protocols supported by the local client, as specified by the `protocol.layer` and `protocol.library_versions` fields.

  - [phone.acceptCall](https://core.telegram.org/method/phone.acceptCall)

- CHANNEL_PRIVATE : You haven't joined this channel/supergroup.

  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)
  - [channels.deleteMessages](https://core.telegram.org/method/channels.deleteMessages)
  - [channels.editBanned](https://core.telegram.org/method/channels.editBanned)
  - [channels.getAdminLog](https://core.telegram.org/method/channels.getAdminLog)
  - [channels.getChannels](https://core.telegram.org/method/channels.getChannels)
  - [channels.getFullChannel](https://core.telegram.org/method/channels.getFullChannel)
  - [channels.getMessages](https://core.telegram.org/method/channels.getMessages)
  - [channels.getParticipant](https://core.telegram.org/method/channels.getParticipant)
  - [channels.getParticipants](https://core.telegram.org/method/channels.getParticipants)
  - [channels.inviteToChannel](https://core.telegram.org/method/channels.inviteToChannel)
  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [channels.leaveChannel](https://core.telegram.org/method/channels.leaveChannel)
  - [channels.readHistory](https://core.telegram.org/method/channels.readHistory)
  - [channels.readMessageContents](https://core.telegram.org/method/channels.readMessageContents)
  - [messages.checkChatInvite](https://core.telegram.org/method/messages.checkChatInvite)
  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.getHistory](https://core.telegram.org/method/messages.getHistory)
  - [messages.getInlineBotResults](https://core.telegram.org/method/messages.getInlineBotResults)
  - [messages.getMessagesViews](https://core.telegram.org/method/messages.getMessagesViews)
  - [messages.getPeerDialogs](https://core.telegram.org/method/messages.getPeerDialogs)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.setTyping](https://core.telegram.org/method/messages.setTyping)
  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)

- CHANNEL_TOO_LARGE : Channel is too large to be deleted; this error is issued when trying to delete channels with more than 1000 members (subject to change).

  - [channels.deleteChannel](https://core.telegram.org/method/channels.deleteChannel)

- CHAT_FORWARDS_RESTRICTED : You can't forward messages from a protected chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)

- FILEREF_UPGRADE_NEEDED : The client has to be updated in order to support [file references](https://core.telegram.org/api/file_reference).

  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- FRESH_CHANGE_ADMINS_FORBIDDEN : You were just elected admin, you can't add or modify other admins yet.

  - [channels.editAdmin](https://core.telegram.org/method/channels.editAdmin)

- FRESH_CHANGE_PHONE_FORBIDDEN : You can't change phone number right after logging in, please wait at least 24 hours.

  - [account.sendChangePhoneCode](https://core.telegram.org/method/account.sendChangePhoneCode)

- FRESH_RESET_AUTHORISATION_FORBIDDEN : You can't logout other sessions if less than 24 hours have passed since you logged on the current session.

  - [account.resetAuthorization](https://core.telegram.org/method/account.resetAuthorization)
  - [account.setAuthorizationTTL](https://core.telegram.org/method/account.setAuthorizationTTL)
  - [auth.resetAuthorizations](https://core.telegram.org/method/auth.resetAuthorizations)

- INVITE_HASH_EXPIRED : The invite link has expired.

  - [channels.joinChannel](https://core.telegram.org/method/channels.joinChannel)
  - [invokeWithLayer](https://core.telegram.org/method/invokeWithLayer)
  - [messages.checkChatInvite](https://core.telegram.org/method/messages.checkChatInvite)
  - [messages.importChatInvite](https://core.telegram.org/method/messages.importChatInvite)

- PAYMENT_UNSUPPORTED : A detailed description of the error will be received separately as described [here &raquo;](https://core.telegram.org/api/errors#406-not-acceptable).

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- PHONE_NUMBER_INVALID : The phone number is invalid.

  - [account.changePhone](https://core.telegram.org/method/account.changePhone)
  - [account.sendChangePhoneCode](https://core.telegram.org/method/account.sendChangePhoneCode)
  - [auth.cancelCode](https://core.telegram.org/method/auth.cancelCode)
  - [auth.checkPhone](https://core.telegram.org/method/auth.checkPhone)
  - [auth.resendCode](https://core.telegram.org/method/auth.resendCode)
  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)
  - [auth.signIn](https://core.telegram.org/method/auth.signIn)
  - [auth.signUp](https://core.telegram.org/method/auth.signUp)

- PHONE_PASSWORD_FLOOD : You have tried logging in too many times.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- PREMIUM_CURRENTLY_UNAVAILABLE : You cannot currently purchase a Premium subscription.

  - [payments.canPurchasePremium](https://core.telegram.org/method/payments.canPurchasePremium)

- PREVIOUS_CHAT_IMPORT_ACTIVE_WAIT_%dMIN : Import for this chat is already in progress, wait %d minutes before starting a new one.

  - [messages.initHistoryImport](https://core.telegram.org/method/messages.initHistoryImport)

- PRIVACY_PREMIUM_REQUIRED : You need a [Telegram Premium subscription](https://core.telegram.org/api/premium) to send a message to this user.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- SEND_CODE_UNAVAILABLE : Returned when all available options for this type of number were already used (e.g. flash-call, then SMS, then this error might be returned to trigger a second resend).

  - [auth.resendCode](https://core.telegram.org/method/auth.resendCode)

- STICKERSET_INVALID : The provided sticker set is invalid.

  - [messages.getStickerSet](https://core.telegram.org/method/messages.getStickerSet)
  - [messages.installStickerSet](https://core.telegram.org/method/messages.installStickerSet)
  - [messages.uninstallStickerSet](https://core.telegram.org/method/messages.uninstallStickerSet)
  - [stickers.addStickerToSet](https://core.telegram.org/method/stickers.addStickerToSet)

- STICKERSET_OWNER_ANONYMOUS : Provided stickerset can't be installed as group stickerset to prevent admin deanonymization.

  - [channels.setStickers](https://core.telegram.org/method/channels.setStickers)

- TOPIC_CLOSED : This topic was closed, you can't send messages to it anymore.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- TOPIC_DELETED : The specified topic was deleted.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- UPDATE_APP_TO_LOGIN : Please update your client to login.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)
  - [auth.signIn](https://core.telegram.org/method/auth.signIn)

- USER_RESTRICTED : You're spamreported, you can't create channels or chats.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [messages.createChat](https://core.telegram.org/method/messages.createChat)

- USERPIC_PRIVACY_REQUIRED : You need to disable privacy settings for your profile picture in order to make your geolocation public.

  - [contacts.getLocated](https://core.telegram.org/method/contacts.getLocated)

- USERPIC_UPLOAD_REQUIRED : You must have a profile picture to publish your geolocation.

  - [contacts.getLocated](https://core.telegram.org/method/contacts.getLocated)

- AUTH_KEY_DUPLICATED : Concurrent usage of the current session from multiple connections was detected, the current session was invalidated by the server for security reasons!


## FLOOD 420

- 2FA_CONFIRM_WAIT_%d : Since this account is active and protected by a 2FA password, we will delete it in 1 week for security purposes. You can cancel this process at any time, you'll be able to reset your account in %d seconds.

  - [account.deleteAccount](https://core.telegram.org/method/account.deleteAccount)

- ADDRESS_INVALID : The specified geopoint address is invalid.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)

- FLOOD_PREMIUM_WAIT_%d : Please wait %d seconds before repeating the action, or purchase a [Telegram Premium subscription](https://core.telegram.org/api/premium) to remove this rate limit.

  - [upload.getFile](https://core.telegram.org/method/upload.getFile)

- PREMIUM_SUB_ACTIVE_UNTIL_%d : You already have a premium subscription active until unixtime %d .

  - [payments.applyGiftCode](https://core.telegram.org/method/payments.applyGiftCode)

- SLOWMODE_WAIT_%d : Slowmode is enabled in this chat: wait %d seconds before sending another message to this chat.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)

- TAKEOUT_INIT_DELAY_%d : Sorry, for security reasons, you will be able to begin downloading your data in %d seconds. We have notified all your devices about the export request to make sure it's authorized and to give you time to react if it's not.

  - [account.initTakeoutSession](https://core.telegram.org/method/account.initTakeoutSession)

- FLOOD_WAIT_%d : Please wait %d seconds before repeating the action.


## INTERNAL 500

- AUTH_KEY_UNSYNCHRONIZED : Internal error, please repeat the method call.

  - [auth.checkPassword](https://core.telegram.org/method/auth.checkPassword)

- AUTH_RESTART : Restart the authorization process.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)
  - [auth.signIn](https://core.telegram.org/method/auth.signIn)

- AUTH_RESTART_%d : Internal error (debug info %d), please repeat the method call.

  - [auth.sendCode](https://core.telegram.org/method/auth.sendCode)

- CALL_OCCUPY_FAILED : The call failed because the user is already making another call.

  - [phone.acceptCall](https://core.telegram.org/method/phone.acceptCall)
  - [phone.discardCall](https://core.telegram.org/method/phone.discardCall)

- CDN_UPLOAD_TIMEOUT : A server-side timeout occurred while reuploading the file to the CDN DC.

  - [upload.reuploadCdnFile](https://core.telegram.org/method/upload.reuploadCdnFile)

- CHAT_ID_GENERATE_FAILED : Failure while generating the chat ID.

  - [messages.createChat](https://core.telegram.org/method/messages.createChat)

- CHAT_INVALID : Invalid chat.

  - [channels.createChannel](https://core.telegram.org/method/channels.createChannel)
  - [messages.migrateChat](https://core.telegram.org/method/messages.migrateChat)

- MSG_WAIT_FAILED : A waiting call returned an error.

  - [messages.editMessage](https://core.telegram.org/method/messages.editMessage)
  - [messages.receivedQueue](https://core.telegram.org/method/messages.receivedQueue)
  - [messages.sendEncrypted](https://core.telegram.org/method/messages.sendEncrypted)
  - [messages.sendEncryptedService](https://core.telegram.org/method/messages.sendEncryptedService)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)

- PERSISTENT_TIMESTAMP_OUTDATED : Channel internal replication issues, try again later (treat this like an RPC_CALL_FAIL).

  - [updates.getChannelDifference](https://core.telegram.org/method/updates.getChannelDifference)

- RANDOM_ID_DUPLICATE : You provided a random ID that was already used.

  - [messages.forwardMessages](https://core.telegram.org/method/messages.forwardMessages)
  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)
  - [messages.sendMedia](https://core.telegram.org/method/messages.sendMedia)
  - [messages.sendMessage](https://core.telegram.org/method/messages.sendMessage)
  - [messages.sendMultiMedia](https://core.telegram.org/method/messages.sendMultiMedia)
  - [messages.sendScheduledMessages](https://core.telegram.org/method/messages.sendScheduledMessages)
  - [messages.startBot](https://core.telegram.org/method/messages.startBot)
  - [updates.getDifference](https://core.telegram.org/method/updates.getDifference)

- SEND_MEDIA_INVALID : The specified media is invalid.

  - [messages.sendInlineBotResult](https://core.telegram.org/method/messages.sendInlineBotResult)

- SIGN_IN_FAILED : Failure while signing in.

  - [auth.signIn](https://core.telegram.org/method/auth.signIn)

- TRANSLATE_REQ_FAILED : Translation failed, please try again later.

  - [messages.translateText](https://core.telegram.org/method/messages.translateText)

# How do we handle rpc errors ?

?> Note, You just need to use class `\Tak\Liveproto\Errors\RpcError` which inherits from Exception

```php

use Tak\Liveproto\Errors\RpcError;

use function Amp\delay;

try {
	sendmessage:
	$text = 'Hello , I wanted to see how the FLOOD_WAIT error works 😜';
	$peer = $client->get_input_peer('@TakNone');
	$client->messages->sendMessage(peer : $peer,message : $text,random_id : random_int(PHP_INT_MIN,PHP_INT_MAX));
} catch(RpcError $error){
	var_dump($error);
	/*
	FLOOD_WAIT_%d is a type of `FLOOD` error that has code 420

	Let's assume we have to wait for 86 seconds ( So %d == 86 )

	$error->getCode() === 420
	$error->getType() === 'FLOOD'
	$error->getMessage() === 'FLOOD_WAIT_86'
	$error->getValue() === 86
	*/
	if($error->getCode() == 420){
		delay($error->getValue()); // Getting the amount of time we have to wait ( means %d from FLOOD_WAIT_%d ) //
		goto sendmessage;
	}
}

```
